<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;  // ✅ كده صح

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// 🏠 الصفحة الرئيسية - توجيه تلقائي
// ============================================
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// ============================================
// 🔐 Authentication Routes (Guest Only)
// ============================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ============================================
// 🚪 Logout Route (Authenticated Only)
// ============================================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================
// 👨‍💼 Admin Routes (Admin Only)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - مرحباً ' . Auth::user()->name;
    })->name('dashboard');
    
    // هنا هنضيف باقي routes الأدمن (الكورسات، الفيديوهات، إلخ...)
});

// ============================================
// 👨‍🎓 Student Routes (Authenticated Only)
// ============================================
Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return 'Student Dashboard - مرحباً ' . Auth::user()->name;
    })->name('dashboard');
    
    // هنا هنضيف باقي routes الطالب (الكورسات، الشراء، إلخ...)
});
