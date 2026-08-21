<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Account;
use App\Models\FeeInvoice;
use App\Models\FeeInvoiceItem;
use App\Models\FeeStructure;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = FeeInvoice::with(['student', 'academicYear'])
            ->latest()
            ->paginate(20);
            
        return view('backend.pages.sms.finance.invoices.index', compact('invoices'));
    }

    public function generateIndex(Request $request)
    {
        $years = AcademicYear::orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        
        $students = collect();
        $structures = collect();
        
        if ($request->filled('academic_year_id') && $request->filled('academic_class_id')) {
            $yearId = $request->academic_year_id;
            $classId = $request->academic_class_id;
            
            // Get students
            $students = Student::where('status', 'Active')->whereHas('enrollments', function($q) use ($yearId, $classId) {
                $q->where('academic_year_id', $yearId)
                  ->where('academic_class_id', $classId)
                  ->whereIn('status', ['Continuing', 'Promoted', 'New']);
            })->orderBy('first_name')->get();
            
            // Calculate previous dues
            foreach ($students as $student) {
                $due = FeeInvoice::where('student_id', $student->id)
                    ->where('status', '!=', 'Paid')
                    ->selectRaw('SUM(total_amount - paid_amount) as due_amount')
                    ->value('due_amount');
                $student->previous_due = $due ?? 0;
            }
            
            // Get fee structures
            $structures = FeeStructure::with('feeType')->where('academic_year_id', $yearId)
                ->where(function($q) use ($classId) {
                    $q->where('academic_class_id', $classId)
                      ->orWhereNull('academic_class_id');
                })->get();
        }
        
        return view('backend.pages.sms.finance.invoices.generate', compact('years', 'classes', 'students', 'structures'));
    }

    public function generateProcess(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'nepali_month' => 'required|string',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'students' => 'required|array',
        ]);

        $yearId = $request->academic_year_id;
        $classId = $request->academic_class_id;
        $nepaliMonth = $request->nepali_month;
        $studentsData = $request->students;

        // Accounts for Journal Entry
        $arAccount = Account::where('code', '1200')->first(); // AR - Students
        $incomeAccount = Account::where('code', '4000')->first(); // Fee Income

        if (!$arAccount || !$incomeAccount) {
            return back()->with('error', 'Critical Accounting Error: Standard accounts (AR or Fee Income) not found. Did you run the AccountingSeeder?');
        }

        DB::beginTransaction();
        try {
            $generatedCount = 0;

            foreach ($studentsData as $studentId => $data) {
                if (!isset($data['generate']) || $data['generate'] != '1') {
                    continue; // Skip unchecked students
                }
                
                $student = Student::find($studentId);
                if (!$student) continue;

                $discount = floatval($data['discount'] ?? 0);
                $previousDue = floatval($data['previous_due'] ?? 0);
                $remarks = $data['remarks'] ?? null;
                
                // Calculate subtotal from selected fees
                $subtotal = 0;
                $invoiceItems = [];
                
                if (isset($data['fees']) && is_array($data['fees'])) {
                    foreach ($data['fees'] as $feeTypeId => $feeData) {
                        if (isset($feeData['include']) && $feeData['include'] == '1') {
                            $amount = floatval($feeData['amount']);
                            $subtotal += $amount;
                            $invoiceItems[] = [
                                'fee_type_id' => $feeTypeId,
                                'amount' => $amount
                            ];
                        }
                    }
                }
                
                if (empty($invoiceItems)) {
                    continue; // Skip if no fees selected
                }

                $totalAmount = max(0, $subtotal - $discount); 

                // We only generate journal entry for the current invoice amount, NOT previous due
                // Create Journal Entry
                $journal = JournalEntry::create([
                    'entry_date' => now(),
                    'reference' => 'INV-GEN',
                    'description' => "Invoice '{$request->title}' for student {$student->first_name} {$student->last_name}",
                ]);

                // Debit AR
                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $arAccount->id,
                    'type' => 'debit',
                    'amount' => $totalAmount,
                ]);

                // Credit Income
                JournalEntryItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $incomeAccount->id,
                    'type' => 'credit',
                    'amount' => $totalAmount,
                ]);

                // Determine status (if previous due is carried over, the new invoice isn't paid yet anyway)
                $status = ($totalAmount <= 0) ? 'Paid' : 'Unpaid';
                
                // Create Invoice
                $invoice = FeeInvoice::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $yearId,
                    'nepali_month' => $nepaliMonth,
                    'title' => $request->title,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_amount' => $totalAmount,
                    'previous_due' => $previousDue,
                    'remarks' => $remarks,
                    'due_date' => $request->due_date,
                    'status' => $status,
                    'journal_entry_id' => $journal->id,
                ]);

                // Create Invoice Items
                foreach ($invoiceItems as $item) {
                    FeeInvoiceItem::create([
                        'fee_invoice_id' => $invoice->id,
                        'fee_type_id' => $item['fee_type_id'],
                        'amount' => $item['amount'],
                    ]);
                }

                $generatedCount++;
            }

            DB::commit();
            
            if ($generatedCount > 0) {
                return redirect()->route('sms.finance.invoices.index')->with('success', "Successfully generated $generatedCount invoices and posted journal entries.");
            } else {
                return back()->with('info', 'No invoices were generated. Ensure you selected students and included fees.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error generating invoices: ' . $e->getMessage());
        }
    }

    public function show(FeeInvoice $invoice)
    {
        $invoice->load(['student', 'academicYear', 'items.feeType', 'payments']);
        return view('backend.pages.sms.finance.invoices.show', compact('invoice'));
    }

    public function print(Request $request, FeeInvoice $invoice)
    {
        $invoice->load(['student', 'academicYear', 'items.feeType', 'payments']);
        
        $paperSize = $request->get('size', 'a4'); // 'a4' or 'a5'
        
        return view('backend.pages.sms.finance.invoices.print', compact('invoice', 'paperSize'));
    }
}
