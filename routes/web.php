<?php
// @mago-format-ignore

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Controllers\CampusCalendarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\PrivacypolicyController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\Frontend;
use App\Http\Controllers\HomeSectionController;
use App\Http\Controllers\NavbarItemController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Frontend\ResultController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Frontend::class, 'home'])->name('home');
Route::get('/gallery', [Frontend::class, 'gallery'])->name('gallery');
Route::get('/contact', [Frontend::class, 'contact'])->name('contact');
Route::get('/admission', [Frontend::class, 'admission'])->name('admission.form');
Route::post('/admission', [Frontend::class, 'submitAdmission'])->name('admission.submit');
Route::get('/member', [Frontend::class, 'member'])->name('member');
Route::get('/about/us', [Frontend::class, 'about'])->name('about.us');
Route::get('/secure-login', [Admin::class, 'publicPortal'])->name('secure.login'); // Now acts as portal selection
Route::get('/admin/login', [Admin::class, 'login'])->name('admin.login');

// Accounting Login
Route::get('/accounting/login', [\App\Http\Controllers\Accounting\AccountingAuthController::class, 'showLoginForm'])->name('accounting.login');
Route::post('/accounting/login', [\App\Http\Controllers\Accounting\AccountingAuthController::class, 'login'])->name('accounting.login.submit');
Route::get('/accounting/logout', [\App\Http\Controllers\Accounting\AccountingAuthController::class, 'logout'])->name('accounting.logout');

Route::get('/privacy/policy', [Frontend::class, 'privacy'])->name('privacy.policy');

Route::get('/page/{slug}', [Frontend::class, 'pageDetail'])->name('custom.page');
Route::get('/notices', [Frontend::class, 'noticeIndex'])->name('notices.index');
Route::get('/calendar', [Frontend::class, 'calendar'])->name('calendar');
Route::get('/events', [Frontend::class, 'eventsIndex'])->name('events.index');

Route::get('/course/{slug}', [Frontend::class, 'courseDetail']);
Route::get('/event/{slug}', [Frontend::class, 'eventDetail']);
Route::get('/notice/detail/{id}', [Frontend::class, 'noticeDetail']);

Route::get('/close', function () {
    session()->put('popupClosed', 1);
    return back();
})->name('popup.close');

// Public Result Routes
Route::get('/results', [ResultController::class, 'index'])->name('results.index');
Route::post('/results/search', [ResultController::class, 'search'])->name('results.search');

// Public Document QR Verification
Route::get('/verify/doc/{token}', [\App\Http\Controllers\Frontend\VerificationController::class, 'show'])->name('verification.show');

Route::middleware('webGuard')->group(function () {
    Route::prefix('admin/sms')->group(base_path('routes/sms.php'));
    Route::group([], base_path('routes/cms.php'));

    // Parent Portal Routes
    Route::prefix('parent')->middleware('role:Parent')->group(base_path('routes/parent.php'));
    Route::prefix('student')->middleware('role:Student')->group(base_path('routes/student.php'));



    // Backend Routes
    Route::get('/admin/portal', [Admin::class, 'portal'])->name('admin.portal');
    Route::get('/admin/sms/dashboard', [Admin::class, 'smsDashboard'])->name('sms.dashboard');
    Route::post('/admin/sms/dashboard/preferences', [Admin::class, 'saveDashboardPreferences'])->name('sms.dashboard.preferences');
    Route::post('/admin/sms/dashboard/download-image', [\App\Http\Controllers\SMS\DownloadController::class, 'downloadImage'])->name('sms.dashboard.download-image');
    Route::get('/admin/dashboard', function () {
        return view('backend.pages.index');
    })->name('admin.dashboard');

    // Course Routes

    // Teacher Routes

    // Testimonial Routes

    // Gallery Routes

    // Event Routes

    // Campus Calendar Routes
    Route::get('/admin/dashboard/calendar', [CampusCalendarController::class, 'index'])->name('campus.calendar.index');
    Route::post('/admin/dashboard/calendar/store', [CampusCalendarController::class, 'store'])->name('campus.calendar.store');
    Route::post('/admin/dashboard/calendar/update/{id}', [CampusCalendarController::class, 'update'])->name('campus.calendar.update');
    Route::get('/admin/dashboard/calendar/status/{id}', [CampusCalendarController::class, 'toggleStatus'])->name('campus.calendar.status');
    Route::get('/admin/dashboard/calendar/delete/{id}', [CampusCalendarController::class, 'destroy'])->name('campus.calendar.destroy');


    // Counter Routes
    Route::get('/admin/dashboard/counter/table', [CounterController::class, 'index'])->name('counter.table');
    Route::post('/admin/dashboard/counter/store', [CounterController::class, 'store'])->name('counter.store');
    Route::post('/admin/dashboard/counter/edit/update/{id}', [CounterController::class, 'update'])->name('counter.update');
    Route::get('/admin/dashboard/home-sections', [HomeSectionController::class, 'index'])->name('home.sections.index');
    Route::post('/admin/dashboard/home-sections/update', [HomeSectionController::class, 'update'])->name('home.sections.update');

    // CMS Site Settings
    Route::get('/admin/dashboard/website-settings', [SiteSettingController::class, 'editCms'])->name('site.settings.cms.edit');
    Route::post('/admin/dashboard/website-settings/update', [SiteSettingController::class, 'updateCms'])->name('site.settings.cms.update');

    // SMS Site Settings
    Route::get('/admin/sms/site-settings', [SiteSettingController::class, 'editSms'])->name('site.settings.sms.edit');
    Route::post('/admin/sms/site-settings/update', [SiteSettingController::class, 'updateSms'])->name('site.settings.sms.update');

    // The SMS site-settings routes will be placed further down with the other SMS routes

    // Navbar Builder Routes
    Route::get('/admin/dashboard/navbar', [NavbarItemController::class, 'index'])->name('navbar.index');
    Route::post('/admin/dashboard/navbar/store', [NavbarItemController::class, 'store'])->name('navbar.store');
    Route::post('/admin/dashboard/navbar/reorder', [NavbarItemController::class, 'reorder'])->name('navbar.reorder');
    Route::get('/admin/dashboard/navbar/delete/{id}', [NavbarItemController::class, 'destroy'])->name('navbar.destroy');

    // Exam Results System
    Route::get('/admin/sms/exams', [ExamController::class, 'index'])->name('exam.index');
    Route::post('/admin/sms/exams/store', [ExamController::class, 'store'])->name('exam.store');
    Route::post('/admin/sms/exams/update/{id}', [ExamController::class, 'update'])->name('exam.update');
    Route::get('/admin/sms/exams/delete/{id}', [ExamController::class, 'destroy'])->name('exam.destroy');

    Route::get('/admin/sms/exams/{id}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/admin/sms/exams/{id}/import', [ExamController::class, 'importCsv'])->name('exam.import');
    Route::get('/admin/sms/exams/sample/download', [ExamController::class, 'downloadSample'])->name('exam.sample');

    Route::post('/admin/sms/exam-results/{exam_id}/store', [ExamController::class, 'storeResult'])->name('exam.result.store');
    Route::post('/admin/sms/exam-results/update/{id}', [ExamController::class, 'updateResult'])->name('exam.result.update');
    Route::get('/admin/sms/exam-results/delete/{id}', [ExamController::class, 'destroyResult'])->name('exam.result.destroy');

    // About US  Routes
    Route::get('/admin/dashboard/aboutus/add', [AboutUsController::class, 'create'])->name('aboutus.add');
    Route::post('/admin/dashboard/aboutus/store', [AboutUsController::class, 'store'])->name('aboutus.store');
    Route::post('/admin/dashboard/aboutus/edit/update/{id}', [AboutUsController::class, 'update'])->name('aboutus.update');
    Route::post('/admin/dashboard/aboutus/faq/store', [AboutUsController::class, 'faqStore'])->name('aboutus.faq.store');
    Route::post('/admin/dashboard/aboutus/faq/update/{id}', [AboutUsController::class, 'faqUpdate'])->name('aboutus.faq.update');
    Route::get('/admin/dashboard/aboutus/faq/status/{id}', [AboutUsController::class, 'faqStatus'])->name('aboutus.faq.status');
    Route::get('/admin/dashboard/aboutus/faq/delete/{id}', [AboutUsController::class, 'faqDestroy'])->name('aboutus.faq.destroy');

    // Privacy Policy   Routes
    Route::get('/admin/dashboard/privacy/add', [PrivacypolicyController::class, 'create'])->name('privacy.add');
    Route::post('/admin/dashboard/privacy/store', [PrivacypolicyController::class, 'store'])->name('privacy.store');
    Route::post('/admin/dashboard/privacy/edit/update/{id}', [PrivacypolicyController::class, 'update'])->name('privacy.update');

    // Editor
    Route::get('/admin/dashboard/editor/table', [EditorController::class, 'index'])->name('editor.table');
    Route::get('/admin/dashboard/editor/edit/{id}', [EditorController::class, 'edit'])->name('editor.edit');
    Route::get('/admin/dashboard/editor/delete/{id}', [EditorController::class, 'delete'])->name('editor.delete');
    Route::post('/admin/dashboard/editor/edit/update/{id}', [EditorController::class, 'update'])->name('editor.update');




    // Profile
    Route::get('/admin/dashboard/profile', [Admin::class, 'profile'])->name('admin.profile');

    // Notice Routes

    // Banner Routes

    // Custom Pages (HTML content) Routes

    // College Messages Routes (Principal, Chairman, Coordinator)
});
// Backend Routes login and register
Route::get('/admin/dashboard/login', [Admin::class, 'login'])->name('admin.login');
Route::get('/admin/dashboard/register', [Admin::class, 'register'])->name('admin.register');
Route::post('/admin/dashboard/admin/register', [Admin::class, 'registerAdmin'])->name('admin.store');
Route::post('/admin/dashboard/admin/check', [Admin::class, 'adminCheck'])->name('admin.check');
Route::get('/admin/dashboard/forgot/password', [Admin::class, 'forgotPassword'])->name('forgot.password');
Route::post('/admin/dashboard/email/check', [Admin::class, 'emailCheck'])->name('email.check');
Route::get('/admin/dashboard/reset/password', function () {
    return view('backend.auth.resetpassword');
});
Route::post('/admin/dashboard/reset/password', [Admin::class, 'resetPassword'])->name('resetPassword');
Route::get('/admin/dashboard/logout', function () {
    $user = Auth::user()->password;
    Auth::logout();
    Auth::logoutOtherDevices($user);
    return redirect()->route('home')->with('success', 'Logout');
})->name('admin.logout');
require __DIR__ . '/inventory.php';
