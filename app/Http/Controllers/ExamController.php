<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::orderBy('created_at', 'desc')->get();
        return view('backend.pages.exams.index', compact('exams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Exam::create($request->all());
        return back()->with('success', 'Exam created successfully.');
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);
        
        $exam->update($request->all());
        return back()->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        Exam::findOrFail($id)->delete();
        return back()->with('success', 'Exam deleted successfully.');
    }

    public function show($id)
    {
        $exam = Exam::with('results')->findOrFail($id);
        return view('backend.pages.exams.show', compact('exam'));
    }

    public function importCsv(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (!$header) {
            return back()->with('error', 'CSV file is empty or invalid.');
        }

        // Normalize headers
        $normalizedHeaders = array_map(function($h) {
            return strtolower(trim(str_replace(' ', '_', $h)));
        }, $header);

        $symbolIdx = array_search('symbol_number', $normalizedHeaders);
        if ($symbolIdx === false) {
            $symbolIdx = array_search('symbolnumber', $normalizedHeaders);
        }

        $nameIdx = array_search('student_name', $normalizedHeaders);
        if ($nameIdx === false) {
            $nameIdx = array_search('name', $normalizedHeaders);
        }

        if ($symbolIdx === false || $nameIdx === false) {
            return back()->with('error', 'CSV must contain Symbol Number and Student Name columns.');
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (empty(trim($row[$symbolIdx]))) continue;

            $marksData = [];
            foreach ($header as $index => $colName) {
                if ($index !== $symbolIdx && $index !== $nameIdx) {
                    $marksData[trim($colName)] = trim($row[$index] ?? '');
                }
            }

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'symbol_number' => trim($row[$symbolIdx])],
                [
                    'student_name' => trim($row[$nameIdx]),
                    'marks_data' => $marksData
                ]
            );
            $imported++;
        }

        fclose($handle);
        return back()->with('success', "Successfully imported {$imported} results.");
    }

    public function downloadSample()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sample_exam_results.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Symbol Number', 'Student Name', 'English', 'Math', 'Science', 'Total', 'GPA', 'Remarks'];
        $data = [
            ['1001', 'John Doe', '80', '90', '85', '255', '3.8', 'Pass'],
            ['1002', 'Jane Smith', '75', '88', '92', '255', '3.7', 'Pass'],
        ];

        $callback = function () use ($columns, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeResult(Request $request, $exam_id)
    {
        $request->validate([
            'symbol_number' => 'required|string|max:100',
            'student_name' => 'required|string|max:255',
            'marks_keys' => 'nullable|array',
            'marks_values' => 'nullable|array',
        ]);

        $marksData = [];
        if ($request->has('marks_keys')) {
            foreach ($request->marks_keys as $index => $key) {
                if (!empty($key)) {
                    $marksData[$key] = $request->marks_values[$index] ?? '';
                }
            }
        }

        ExamResult::updateOrCreate(
            ['exam_id' => $exam_id, 'symbol_number' => $request->symbol_number],
            [
                'student_name' => $request->student_name,
                'marks_data' => $marksData
            ]
        );

        return back()->with('success', 'Result saved successfully.');
    }

    public function updateResult(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);
        
        $request->validate([
            'symbol_number' => 'required|string|max:100',
            'student_name' => 'required|string|max:255',
            'marks_keys' => 'nullable|array',
            'marks_values' => 'nullable|array',
        ]);

        $marksData = [];
        if ($request->has('marks_keys')) {
            foreach ($request->marks_keys as $index => $key) {
                if (!empty($key)) {
                    $marksData[$key] = $request->marks_values[$index] ?? '';
                }
            }
        }

        $result->update([
            'symbol_number' => $request->symbol_number,
            'student_name' => $request->student_name,
            'marks_data' => $marksData
        ]);

        return back()->with('success', 'Result updated successfully.');
    }

    public function destroyResult($id)
    {
        ExamResult::findOrFail($id)->delete();
        return back()->with('success', 'Result deleted successfully.');
    }
}
