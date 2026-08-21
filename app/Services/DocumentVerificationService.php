<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Exam;
use Illuminate\Support\Str;

class DocumentVerificationService
{
    /**
     * Generate a unique verification token for any document.
     */
    public static function generateToken(string $prefix = 'DOC'): string
    {
        return strtolower($prefix . '_' . Str::random(24));
    }

    /**
     * Get the absolute public verification URL for a token.
     */
    public static function getVerificationUrl(string $token): string
    {
        return route('verification.show', ['token' => $token]);
    }

    /**
     * Resolve document verification payload from token.
     */
    public static function resolve(string $token): ?array
    {
        // 1. Check Certificates
        $certificate = Certificate::with(['student.currentEnrollment.academicClass', 'issuer'])->where('qr_token', $token)->first();
        if ($certificate) {
            $student = $certificate->student;
            return [
                'type'             => 'Certificate',
                'title'            => $certificate->title,
                'sub_type'         => ucfirst($certificate->type) . ' Certificate',
                'document_no'      => $certificate->certificate_no,
                'status'           => $certificate->status === 'issued' ? 'Valid & Authentic' : 'Revoked / Invalid',
                'status_code'      => $certificate->status,
                'issue_date'       => $certificate->issue_date->format('M d, Y'),
                'recipient_name'   => $student->full_name,
                'recipient_id'     => $student->admission_no ?? '#' . $student->id,
                'class'            => $student->currentEnrollment?->academicClass?->name ?? 'N/A',
                'issued_by'        => $certificate->issuer?->name ?? 'School Administration',
                'metadata'         => $certificate->metadata,
                'revocation_reason'=> $certificate->revocation_reason,
            ];
        }

        // 2. Check Student ID Card Token (format: id_student_{id}_{hash})
        if (str_starts_with($token, 'id_student_')) {
            $parts = explode('_', $token);
            $studentId = $parts[2] ?? null;
            if ($studentId) {
                $student = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section'])->find($studentId);
                if ($student) {
                    return [
                        'type'           => 'Student Identity Card',
                        'title'          => 'Official Student ID Card',
                        'sub_type'       => 'Student Identity Document',
                        'document_no'    => 'ID-' . ($student->admission_no ?? $student->id),
                        'status'         => 'Valid & Authentic Active Student',
                        'status_code'    => 'issued',
                        'issue_date'     => now()->format('Y-m-d'),
                        'recipient_name' => $student->full_name,
                        'recipient_id'   => $student->admission_no ?? '#' . $student->id,
                        'class'          => ($student->currentEnrollment?->academicClass?->name ?? 'N/A') . ' (' . ($student->currentEnrollment?->section?->name ?? 'N/A') . ')',
                        'issued_by'      => 'Office of the Principal',
                        'metadata'       => [
                            'Blood Group'      => $student->blood_group ?? 'N/A',
                            'Emergency Phone'  => $student->phone ?? 'N/A',
                            'Guardian'         => $student->guardian?->guardian_name ?? 'N/A',
                        ],
                    ];
                }
            }
        }

        // 3. Check Admit Card Token (format: admit_{examId}_{studentId}_{hash})
        if (str_starts_with($token, 'admit_')) {
            $parts = explode('_', $token);
            $examId = $parts[1] ?? null;
            $studentId = $parts[2] ?? null;

            if ($examId && $studentId) {
                $exam = Exam::find($examId);
                $student = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section'])->find($studentId);

                if ($exam && $student) {
                    return [
                        'type'           => 'Examination Admit Card',
                        'title'          => $exam->name . ' - Examination Hall Ticket',
                        'sub_type'       => 'Authorized Exam Entry Permit',
                        'document_no'    => 'ADM-' . $exam->id . '-' . $student->id,
                        'status'         => 'Valid for Exam Entry',
                        'status_code'    => 'issued',
                        'issue_date'     => $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') : now()->format('M d, Y'),
                        'recipient_name' => $student->full_name,
                        'recipient_id'   => $student->admission_no ?? '#' . $student->id,
                        'class'          => ($student->currentEnrollment?->academicClass?->name ?? 'N/A') . ' (' . ($student->currentEnrollment?->section?->name ?? 'N/A') . ')',
                        'issued_by'      => 'Examination Controller Board',
                        'metadata'       => [
                            'Examination' => $exam->name,
                            'Session'     => $exam->academicYear?->name ?? 'Current Academic Year',
                        ],
                    ];
                }
            }
        }

        return null;
    }
}
