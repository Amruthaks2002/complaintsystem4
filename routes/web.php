<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CanteenController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Student login

Route::get('/register/student', [StudentController::class, 'showRegister']);
Route::post('/register/student', [StudentController::class, 'register']);
Route::get('/login/student', [StudentController::class, 'showLogin']);
Route::post('/login/student', [StudentController::class, 'login']);
Route::get('/dashboard/student', [StudentController::class, 'dashboard'])->middleware('auth');
Route::get('/logout', [StudentController::class, 'logout']);
use App\Http\Controllers\ComplaintController;

Route::middleware(['auth'])->group(function () {
    Route::get('/student/complaint/create', [ComplaintController::class, 'showForm']);
    Route::post('/student/complaint/store', [ComplaintController::class, 'store']);
});
Route::get('/student/complaints', [StudentController::class, 'viewComplaints'])->middleware('auth');
Route::get('/student/complaint/{id}', [StudentController::class, 'show'])->name('student.complaint.show');

// Admin login
Route::get('/login/admin', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/login/admin', [AdminController::class, 'login'])->name('admin.login.submit');
Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/complaint/{id}', [AdminController::class, 'showComplaint'])->name('admin.complaint.show');
Route::post('/admin/complaint/{id}/respond', [AdminController::class, 'respondToComplaint'])->name('admin.complaint.respond');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
// Department login
use App\Http\Controllers\DepartmentController;

Route::get('/login/department', [DepartmentController::class, 'showLogin']);
Route::post('/login/department', [DepartmentController::class, 'login']);
Route::get('/dashboard/department', [DepartmentController::class, 'dashboard'])->middleware('auth');
Route::get('/department/complaint/{id}', [DepartmentController::class, 'showComplaint']);
Route::post('/department/complaint/respond/{id}', [DepartmentController::class, 'submitResponse']);
Route::post('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');

Route::get('/admin/complaints/download-pdf', [AdminController::class, 'downloadPdf'])->name('admin.download.pdf');
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/dashboard/student', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/dashboard/department', [DepartmentController::class, 'dashboard'])->name('department.dashboard');
Route::post('/admin/complaint/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.complaint.update.status');

Route::get('/librarian/dashboard', [LibrarianController::class, 'dashboard'])->name('librarian.dashboard');

Route::middleware(['role:canteen'])->group(function () {
    Route::get('/canteen/dashboard', [CanteenController::class, 'dashboard'])->name('canteen.dashboard');
});

Route::get('/cleaning/dashboard', [CleaningController::class, 'index'])->name('cleaning.dashboard');
Route::get('/cleaner/complaint/{id}', [CleaningController::class, 'showComplaint']);
Route::post('/cleaner/respond/{id}', [CleaningController::class, 'respond']);
Route::post('/cleaner/status/{id}', [CleaningController::class, 'updateStatus']);

Route::get('/librarian/dashboard', [LibrarianController::class, 'index'])->name('librarian.dashboard');
Route::get('/librarian/complaint/{id}', [LibrarianController::class, 'showComplaint'])->name('librarian.complaint.view');
Route::post('/librarian/respond/{id}', [LibrarianController::class, 'respond'])->name('librarian.respond');
Route::post('/librarian/status/{id}', [LibrarianController::class, 'updateStatus'])->name('librarian.status.update');

Route::get('/canteen/dashboard', [CanteenController::class, 'index'])->name('canteen.dashboard');
Route::get('/canteen/complaint/{id}', [CanteenController::class, 'showComplaint'])->name('canteen.complaint.view');
Route::post('/canteen/respond/{id}', [CanteenController::class, 'respond'])->name('canteen.respond');
Route::post('/canteen/status/{id}', [CanteenController::class, 'updateStatus'])->name('canteen.status.update');

Route::get('/register/student', [StudentController::class, 'showRegister'])->name('student.register.form');
Route::post('/register/student', [StudentController::class, 'register'])->name('student.register');
Route::get('/admin/complaints/csv', [AdminController::class, 'exportCsv'])->name('admin.export.csv');
Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');

Route::get('/admin/email-report', [AdminController::class, 'sendEmailReport'])->name('admin.download.email');
Route::post('/admin/export-csv', [AdminController::class, 'requestCsvExport'])->name('admin.export.csv');
Route::get('/admin/downloads', [AdminController::class, 'downloads'])->name('admin.downloads');
Route::get('/admin/download/email', [AdminController::class, 'sendReportLink'])->name('admin.download.email');
Route::get('/admin/download/pdf', [AdminController::class, 'downloadPdf'])->name('admin.download.pdf');
Route::get('/admin/export/csv', [AdminController::class, 'exportCsv'])->name('admin.export.csv');
Route::get('/admin/download/email', [AdminController::class, 'sendReportLink'])->name('admin.download.email');
