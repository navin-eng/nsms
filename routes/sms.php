<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMS\AcademicYearController;
use App\Http\Controllers\SMS\StreamController;
use App\Http\Controllers\SMS\AcademicClassController;
use App\Http\Controllers\SMS\SectionController;
use App\Http\Controllers\SMS\SubjectController;
use App\Http\Controllers\SMS\ClassSubjectAssignmentController;
use App\Http\Controllers\SMS\DepartmentController;
use App\Http\Controllers\SMS\DesignationController;
use App\Http\Controllers\SMS\StaffController;
use App\Http\Controllers\SMS\StaffDocumentController;
use App\Http\Controllers\SMS\StudentController;
use App\Http\Controllers\SMS\StudentDocumentController;
use App\Http\Controllers\SMS\PeriodController;
use App\Http\Controllers\SMS\TimetableController;

// --- TIMETABLE MANAGEMENT ---
Route::resource('periods', PeriodController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::get('timetable', [TimetableController::class, 'index'])->name('sms.timetable.index');
Route::post('timetable/save', [TimetableController::class, 'save'])->name('sms.timetable.save');
Route::get('timetable/teacher', [TimetableController::class, 'teacher'])->name('sms.timetable.teacher');

// --- STAFF MANAGEMENT (HR) ---
Route::resource('departments', DepartmentController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('designations', DesignationController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('staff', StaffController::class, ['as' => 'sms']);
Route::post('staff/{staff}/documents', [StaffDocumentController::class, 'store'])->name('sms.staff.documents.store');
Route::delete('staff/documents/{document}', [StaffDocumentController::class, 'destroy'])->name('sms.staff.documents.destroy');

// --- ATTENDANCE ---
Route::get('attendance', [\App\Http\Controllers\SMS\AttendanceController::class, 'index'])->name('sms.attendance.index');
Route::post('attendance/store', [\App\Http\Controllers\SMS\AttendanceController::class, 'store'])->name('sms.attendance.store');
Route::get('attendance/report', [\App\Http\Controllers\SMS\AttendanceController::class, 'report'])->name('sms.attendance.report');

// --- HOMEWORK & MATERIALS ---
Route::get('homework', [\App\Http\Controllers\SMS\HomeworkController::class, 'index'])->name('sms.homework.index');
Route::post('homework/store', [\App\Http\Controllers\SMS\HomeworkController::class, 'store'])->name('sms.homework.store');
Route::get('homework/{id}', [\App\Http\Controllers\SMS\HomeworkController::class, 'show'])->name('sms.homework.show');
Route::put('homework/{id}', [\App\Http\Controllers\SMS\HomeworkController::class, 'update'])->name('sms.homework.update');
Route::delete('homework/{id}', [\App\Http\Controllers\SMS\HomeworkController::class, 'destroy'])->name('sms.homework.destroy');
Route::post('homework/submissions/{submissionId}/grade', [\App\Http\Controllers\SMS\HomeworkController::class, 'gradeSubmission'])->name('sms.homework.grade');

Route::get('materials', [\App\Http\Controllers\SMS\StudyMaterialController::class, 'index'])->name('sms.materials.index');
Route::post('materials/store', [\App\Http\Controllers\SMS\StudyMaterialController::class, 'store'])->name('sms.materials.store');
Route::get('materials/{id}', [\App\Http\Controllers\SMS\StudyMaterialController::class, 'show'])->name('sms.materials.show');
Route::put('materials/{id}', [\App\Http\Controllers\SMS\StudyMaterialController::class, 'update'])->name('sms.materials.update');
Route::delete('materials/{id}', [\App\Http\Controllers\SMS\StudyMaterialController::class, 'destroy'])->name('sms.materials.destroy');

// --- TIMETABLE / ROUTINE ---
Route::get('staff-attendance', [App\Http\Controllers\SMS\StaffAttendanceController::class, 'index'])->name('sms.staff-attendance.index');
Route::post('staff-attendance', [App\Http\Controllers\SMS\StaffAttendanceController::class, 'store'])->name('sms.staff-attendance.store');
Route::get('staff-attendance/report', [App\Http\Controllers\SMS\StaffAttendanceController::class, 'report'])->name('sms.staff-attendance.report');

Route::resource('leave-requests', App\Http\Controllers\SMS\LeaveRequestController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);

// --- STUDENT MANAGEMENT ---
Route::get('students/{student}/print', [\App\Http\Controllers\SMS\StudentController::class, 'print'])->name('sms.students.print');
Route::post('students/{id}/generate-parent-account', [\App\Http\Controllers\SMS\StudentController::class, 'generateParentAccount'])->name('sms.students.generate-parent-account');
Route::post('students/{id}/reset-parent-password', [\App\Http\Controllers\SMS\StudentController::class, 'resetParentPassword'])->name('sms.students.reset-parent-password');
Route::post('students/{id}/generate-student-account', [\App\Http\Controllers\SMS\StudentController::class, 'generateStudentAccount'])->name('sms.students.generate-student-account');
Route::post('students/{id}/reset-student-password', [\App\Http\Controllers\SMS\StudentController::class, 'resetStudentPassword'])->name('sms.students.reset-student-password');
Route::resource('students', \App\Http\Controllers\SMS\StudentController::class, ['as' => 'sms']);
Route::post('students/{student}/documents', [\App\Http\Controllers\SMS\StudentDocumentController::class, 'store'])->name('sms.students.documents.store');
Route::delete('students/documents/{document}', [StudentDocumentController::class, 'destroy'])->name('sms.students.documents.destroy');

// Academic Structure
Route::resource('academic-years', AcademicYearController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::post('academic-years/{id}/make-active', [AcademicYearController::class, 'makeActive'])->name('sms.academic-years.active');

Route::resource('streams', StreamController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('academic-classes', AcademicClassController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::resource('sections', SectionController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);
Route::post('subjects/update-order', [SubjectController::class, 'updateOrder'])->name('sms.subjects.update_order');
Route::resource('subjects', SubjectController::class, ['as' => 'sms'])->except(['create', 'show', 'edit']);

// --- TEACHER-SUBJECT ASSIGNMENTS ---
Route::get('assignments/sections/{academicClass}', [ClassSubjectAssignmentController::class, 'sectionsByClass'])->name('sms.assignments.sections');
Route::get('assignments/subjects/{academicClass}', [ClassSubjectAssignmentController::class, 'subjectsByClass'])->name('sms.assignments.subjects');
Route::get('assignments', [ClassSubjectAssignmentController::class, 'index'])->name('sms.assignments.index');
Route::post('assignments', [ClassSubjectAssignmentController::class, 'store'])->name('sms.assignments.store');
Route::patch('assignments/{assignment}', [ClassSubjectAssignmentController::class, 'update'])->name('sms.assignments.update');
Route::delete('assignments/{assignment}', [ClassSubjectAssignmentController::class, 'destroy'])->name('sms.assignments.destroy');

// --- ADMISSIONS MANAGEMENT ---
Route::prefix('admissions')->name('sms.admissions.')->group(function () {
    Route::resource('enquiries', \App\Http\Controllers\SMS\AdmissionEnquiryController::class)->except(['create', 'show', 'edit']);
    Route::resource('applications', \App\Http\Controllers\SMS\AdmissionApplicationController::class)->except(['edit', 'update']);
    Route::get('applications/{application}/print', [\App\Http\Controllers\SMS\AdmissionApplicationController::class, 'print'])->name('applications.print');
    Route::patch('applications/{application}/status', [\App\Http\Controllers\SMS\AdmissionApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::post('applications/{application}/enroll', [\App\Http\Controllers\SMS\AdmissionApplicationController::class, 'enroll'])->name('applications.enroll');
    Route::post('applications/{application}/documents', [\App\Http\Controllers\SMS\AdmissionDocumentController::class, 'store'])->name('documents.store');
    Route::delete('documents/{document}', [\App\Http\Controllers\SMS\AdmissionDocumentController::class, 'destroy'])->name('documents.destroy');
});

// --- EXAMINATION MANAGEMENT ---
Route::prefix('exams')->name('sms.')->group(function () {
    Route::resource('exams', \App\Http\Controllers\SMS\ExamController::class);
    Route::resource('exam-schedules', \App\Http\Controllers\SMS\ExamScheduleController::class);
    Route::resource('exam-marks', \App\Http\Controllers\SMS\ExamMarkController::class);
    Route::get('exam-results', [\App\Http\Controllers\SMS\ExamResultController::class, 'index'])->name('exam-results.index');
    Route::get('exam-results/print', [\App\Http\Controllers\SMS\ExamResultController::class, 'printMarkSheet'])->name('exam-results.print');
    Route::get('exam-results/print-bulk', [\App\Http\Controllers\SMS\ExamResultController::class, 'printBulkMarkSheets'])->name('exam-results.print-bulk');
    Route::post('exam-results/{id}/publish', [\App\Http\Controllers\SMS\ExamResultController::class, 'publish'])->name('exam-results.publish');

    // --- TRANSCRIPTS ---
    Route::get('transcripts', [\App\Http\Controllers\SMS\TranscriptController::class, 'index'])->name('transcripts.index');
    Route::get('transcripts/print', [\App\Http\Controllers\SMS\TranscriptController::class, 'print'])->name('transcripts.print');
    Route::resource('grading-rules', \App\Http\Controllers\SMS\GradingRuleController::class);

    // --- ADMIT CARDS ---
    Route::get('admit-cards', [\App\Http\Controllers\SMS\AdmitCardController::class, 'index'])->name('admit-cards.index');
});

// --- ID CARDS & CERTIFICATES ---
Route::prefix('id-cards')->name('sms.id-cards.')->group(function () {
    Route::get('students', [\App\Http\Controllers\SMS\IdCardController::class, 'students'])->name('students');
    Route::get('api/students', [\App\Http\Controllers\SMS\IdCardController::class, 'apiStudents'])->name('api.students');
    Route::get('staff', [\App\Http\Controllers\SMS\IdCardController::class, 'staff'])->name('staff');
    Route::resource('templates', \App\Http\Controllers\SMS\IdCardTemplateController::class)->except(['show']);
});

Route::resource('certificates', \App\Http\Controllers\SMS\CertificateController::class, ['as' => 'sms']);
Route::get('certificates/{id}/print', [\App\Http\Controllers\SMS\CertificateController::class, 'print'])->name('sms.certificates.print');
Route::post('certificates/{id}/revoke', [\App\Http\Controllers\SMS\CertificateController::class, 'revoke'])->name('sms.certificates.revoke');

// --- FEE & FINANCE MANAGEMENT ---
Route::prefix('finance')->name('sms.finance.')->group(function () {
    Route::resource('fee-types', \App\Http\Controllers\SMS\FeeTypeController::class)->except(['show']);
    Route::resource('fee-structures', \App\Http\Controllers\SMS\FeeStructureController::class)->except(['show']);
    Route::get('invoices/generate', [\App\Http\Controllers\SMS\FeeInvoiceController::class, 'generateIndex'])->name('invoices.generate');
    Route::post('invoices/generate', [\App\Http\Controllers\SMS\FeeInvoiceController::class, 'generateProcess'])->name('invoices.generate.process');
    Route::get('invoices/{invoice}/print', [\App\Http\Controllers\SMS\FeeInvoiceController::class, 'print'])->name('invoices.print');
    Route::resource('invoices', \App\Http\Controllers\SMS\FeeInvoiceController::class);

    Route::post('fee-structures/copy', [\App\Http\Controllers\SMS\FeeStructureController::class, 'copy'])->name('fee-structures.copy');

    Route::get('payments/receipt/{payment}', [\App\Http\Controllers\SMS\FeePaymentController::class, 'receipt'])->name('payments.receipt');
    Route::resource('payments', \App\Http\Controllers\SMS\FeePaymentController::class)->only(['store', 'destroy']);

    Route::get('reports/outstanding', [\App\Http\Controllers\SMS\FeeReportController::class, 'outstanding'])->name('reports.outstanding');

    // Accounting
    Route::resource('accounting/accounts', \App\Http\Controllers\SMS\AccountController::class)->only(['index', 'store', 'update', 'destroy'])->names('accounting.accounts');
    // We will leave JournalEntry controller for later as manual UI if needed, but accounts is ready.
    Route::get('accounting/daybook', [\App\Http\Controllers\SMS\AccountingReportController::class, 'daybook'])->name('accounting.daybook');
    Route::get('accounting/ledger', [\App\Http\Controllers\SMS\AccountingReportController::class, 'ledger'])->name('accounting.ledger');
    Route::get('accounting/balance-sheet', [\App\Http\Controllers\SMS\AccountingReportController::class, 'balanceSheet'])->name('accounting.balance-sheet');
});

Route::resource('school-notices', \App\Http\Controllers\SMS\NoticeController::class, ['as' => 'sms']);

// --- LIBRARY MANAGEMENT ---
Route::prefix('library')->name('sms.library.')->group(function () {
    Route::resource('categories', \App\Http\Controllers\SMS\LibraryCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('books', \App\Http\Controllers\SMS\LibraryBookController::class);
    Route::get('issues/create', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'create'])->name('issues.create');
    Route::post('issues/store', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'store'])->name('issues.store');
    Route::get('issues', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'index'])->name('issues.index');
    Route::post('issues/{id}/return', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'returnBook'])->name('issues.return');
    Route::get('issues/api/borrower', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'getBorrower'])->name('issues.api.borrower');
    Route::get('issues/api/borrowers-list', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'getBorrowersList'])->name('issues.api.borrowers-list');
    Route::get('issues/api/book', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'getBook'])->name('issues.api.book');
    Route::get('borrowers/{type}/{id}/history', [\App\Http\Controllers\SMS\LibraryIssueController::class, 'borrowerHistory'])->name('borrowers.history');
    
    // Print Barcodes
    Route::get('books/{book}/print-barcodes', [\App\Http\Controllers\SMS\LibraryBookController::class, 'printBarcodes'])->name('books.print-barcodes');
    Route::get('books/copy/{copy}/history', [\App\Http\Controllers\SMS\LibraryBookController::class, 'copyHistory'])->name('books.copy.history');

    Route::get('settings', [\App\Http\Controllers\SMS\LibrarySettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SMS\LibrarySettingController::class, 'update'])->name('settings.update');
});

// Users & Security

Route::resource('users', \App\Http\Controllers\SMS\UserController::class, ['as' => 'sms'])->except(['show']);
Route::resource('roles', \App\Http\Controllers\Backend\RoleController::class, ['as' => 'admin'])->except(['show']);
Route::get('activity-logs', [\App\Http\Controllers\Backend\ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
Route::get('activity-logs/export', [\App\Http\Controllers\Backend\ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
Route::get('activity-logs/{activityLog}', [\App\Http\Controllers\Backend\ActivityLogController::class, 'show'])->name('admin.activity-logs.show');

// Communication Hub
Route::prefix('communications')->name('admin.communications.')->group(function () {
    Route::get('logs', [\App\Http\Controllers\Backend\CommunicationController::class, 'logs'])->name('logs');
    Route::get('compose', [\App\Http\Controllers\Backend\CommunicationController::class, 'compose'])->name('compose');
    Route::post('send', [\App\Http\Controllers\Backend\CommunicationController::class, 'send'])->name('send');
    Route::get('templates', [\App\Http\Controllers\Backend\CommunicationController::class, 'templates'])->name('templates');
    Route::post('templates', [\App\Http\Controllers\Backend\CommunicationController::class, 'storeTemplate'])->name('templates.store');
    Route::put('templates/{id}', [\App\Http\Controllers\Backend\CommunicationController::class, 'updateTemplate'])->name('templates.update');
    Route::delete('templates/{id}', [\App\Http\Controllers\Backend\CommunicationController::class, 'destroyTemplate'])->name('templates.destroy');
    Route::get('sections-by-class', [\App\Http\Controllers\Backend\CommunicationController::class, 'getSectionsByClass'])->name('sections-by-class');
    Route::get('settings', [\App\Http\Controllers\Backend\CommunicationController::class, 'settings'])->name('settings');
    Route::post('settings', [\App\Http\Controllers\Backend\CommunicationController::class, 'updateSettings'])->name('settings.update');
    Route::post('test-sms', [\App\Http\Controllers\Backend\CommunicationController::class, 'testSms'])->name('test-sms');
});

