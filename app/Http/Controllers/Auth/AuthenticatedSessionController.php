<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    // store メソッド
    //新しいレコードをデータベースに保存するために使用。フォームからの POST リクエストを受け取り、バリデーションと保存処理を行う。
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('top');
    }

    // destroyメソッド
    // 特定のレコードを削除するためのDELETEリクエストを受け取る。削除対象のモデルインスタンスをルートモデルバインディング（推奨）またはIDで受け取る処理
    public function destroy(Request $request)
   {
    //    セッション内の認証情報が削除
    Auth::guard('web')->logout();
    //    セッションデータを破棄
    $request->session()->invalidate();
    //    セッション固定攻撃からの保護
    $request->session()->regenerateToken();
    // 　　リダイレクト
    return redirect()->route('login');//ログイン画面へ行くよう定義する
    }

}
