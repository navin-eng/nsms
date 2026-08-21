<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\DocumentVerificationService;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show($token)
    {
        $payload = DocumentVerificationService::resolve($token);
        $setting = SiteSetting::first();

        if ($payload) {
            $status = $payload['status_code'] === 'issued' ? 'success' : 'error';
            $documentType = $payload['title'];
            $data = [
                'Document No.' => $payload['document_no'],
                'Recipient'    => $payload['recipient_name'] . ' (' . $payload['recipient_id'] . ')',
                'Class'        => $payload['class'],
                'Issued By'    => $payload['issued_by'],
                'Status'       => $payload['status'],
            ];

            return view('frontend.verification.show', compact('status', 'documentType', 'data', 'setting'));
        }

        return view('frontend.verification.show', [
            'status'  => 'error',
            'message' => 'The QR code or link is invalid, expired, or the document has been revoked.',
            'setting' => $setting
        ]);
    }
}
