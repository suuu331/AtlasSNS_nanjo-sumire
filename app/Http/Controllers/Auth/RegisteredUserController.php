<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        // バリデーション処理を追加

        $request->validate([
            // User::createにあるため基本ルールを追加
            'username' => ['required', 'string', 'max:12'],

            // メールアドレスのバリデーションルール
            // 必須、文字列、メール形式、5文字以上、40文字以内、usersテーブルでユニーク
            'email' => ['required', 'string', 'email', 'min:5', 'max:40', 'unique:users'],

            // パスワードのバリデーションルール
            // 必須、文字列、英数字のみ、8文字以上、20文字以内、
            // 'confirmed'ルールにより、'password_confirmation'フィールドとの一致がチェックされる
            // また、'password_confirmation'にも'alpha_num', 'min:8', 'max:20'のルールが適用
            'password' => ['required', 'string', 'alpha_num', 'min:8', 'max:20', 'confirmed'],
        ]);


        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session()->put('username', $request->username);


        return redirect('added');
    }

    public function added(): View
    {
        return view('auth.added');
    }
}
