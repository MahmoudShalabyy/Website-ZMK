<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;  // ✅ صح كده
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ============================================
    // 🔐 LOGIN
    // ============================================
    
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * تسجيل الدخول
     */
    public function login(Request $request)
    {
        // التحقق من البيانات
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // توجيه المستخدم حسب نوعه
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'مرحباً بك ' . $user->name);
            } else {
                return redirect()->route('student.dashboard')->with('success', 'مرحباً بك ' . $user->name);
            }
        }

        // في حالة فشل تسجيل الدخول
        throw ValidationException::withMessages([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ]);
    }

    // ============================================
    // 📝 REGISTER
    // ============================================
    
    /**
     * عرض صفحة التسجيل
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * تسجيل طالب جديد
     */
    public function register(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم طويل جداً',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'phone.max' => 'رقم الهاتف طويل جداً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'student', // تلقائياً طالب
        ]);

        // تسجيل دخول تلقائي
        Auth::login($user);

        // توجيه للداشبورد
        return redirect()->route('student.dashboard')->with('success', 'مرحباً بك ' . $user->name . '! تم إنشاء حسابك بنجاح');
    }

    // ============================================
    // 🚪 LOGOUT
    // ============================================
    
    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
