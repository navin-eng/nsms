<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademicYear::orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        $types = FeeType::orderBy('name')->get();

        $query = FeeStructure::with(['academicYear', 'academicClass', 'feeType']);
        
        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->academic_class_id) {
            $query->where('academic_class_id', $request->academic_class_id);
        }
        if ($request->fee_type_id) {
            $query->where('fee_type_id', $request->fee_type_id);
        }

        $structures = $query->latest()->get();

        return view('backend.pages.sms.finance.structures.index', compact('years', 'classes', 'types', 'structures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'fees' => 'required|array',
            'fees.*.fee_type_id' => 'required|exists:fee_types,id',
            'fees.*.amount' => 'nullable|numeric|min:0',
            'fees.*.billing_cycle' => 'nullable|string',
        ]);

        $yearId = $request->academic_year_id;
        $classId = $request->academic_class_id;
        $count = 0;

        foreach ($request->fees as $fee) {
            if (!empty($fee['amount']) && $fee['amount'] > 0) {
                FeeStructure::updateOrCreate(
                    [
                        'academic_year_id' => $yearId,
                        'academic_class_id' => $classId,
                        'fee_type_id' => $fee['fee_type_id']
                    ],
                    [
                        'billing_cycle' => $fee['billing_cycle'] ?? 'Monthly',
                        'amount' => $fee['amount']
                    ]
                );
                $count++;
            }
        }

        if ($count > 0) {
            return back()->with('success', "{$count} Fee Structure(s) updated/created successfully.");
        }

        return back()->with('error', 'No valid fee amounts were provided.');
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $data = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'nullable|exists:academic_classes,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'billing_cycle' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $exists = FeeStructure::where('id', '!=', $feeStructure->id)
            ->where('academic_year_id', $data['academic_year_id'])
            ->where('academic_class_id', $data['academic_class_id'])
            ->where('fee_type_id', $data['fee_type_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'A fee structure for this type and class already exists for the selected year.');
        }

        $feeStructure->update($data);

        return back()->with('success', 'Fee Structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return back()->with('success', 'Fee Structure deleted successfully.');
    }

    public function copy(Request $request)
    {
        $request->validate([
            'source_academic_year_id' => 'required|exists:academic_years,id',
            'source_academic_class_id' => 'required|exists:academic_classes,id',
            'target_academic_year_id' => 'required|exists:academic_years,id',
            'target_academic_class_id' => 'required|array|min:1',
            'target_academic_class_id.*' => 'exists:academic_classes,id',
        ]);

        $sourceStructures = FeeStructure::where('academic_year_id', $request->source_academic_year_id)
            ->where('academic_class_id', $request->source_academic_class_id)
            ->get();

        if ($sourceStructures->isEmpty()) {
            return back()->with('error', 'No fee structures found for the selected source class and year.');
        }

        $count = 0;
        foreach ($request->target_academic_class_id as $targetClassId) {
            foreach ($sourceStructures as $structure) {
                FeeStructure::updateOrCreate(
                    [
                        'academic_year_id' => $request->target_academic_year_id,
                        'academic_class_id' => $targetClassId,
                        'fee_type_id' => $structure->fee_type_id
                    ],
                    [
                        'billing_cycle' => $structure->billing_cycle,
                        'amount' => $structure->amount
                    ]
                );
                $count++;
            }
        }

        return back()->with('success', "Successfully copied {$sourceStructures->count()} fee structures to " . count($request->target_academic_class_id) . " classes.");
    }
}
