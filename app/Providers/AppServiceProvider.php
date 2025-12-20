<?php

namespace App\Providers;


use Illuminate\Support\Facades\View; //追加:View Composerを使うために必要
use Illuminate\Support\Facades\Auth; //追加:認証済みユーザー情報を取得するために必要
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //共通サイドバーにデータを渡すView Composerを設定
        // ここで指定するビュー名は次の「3. サイドバーのBladeファイルの特定」で決定
        View::composer('layouts.login', function ($view) {
            // 認証済みユーザーが存在する場合のみ実行
            if (Auth::check()) {
                $user = Auth::user();

                // N+1問題を避けて効率的にフォロー数とフォロワー数を取得
                $user->loadCount(['followings', 'followers']);

                // ビューに変数を渡す
                $view->with('followingCount', $user->followings_count)
                     ->with('followerCount', $user->followers_count);
            } else {
                 // 未ログイン時は0を渡しておく
                $view->with('followingCount', 0)
                     ->with('followerCount', 0);
            }
        });
    }

}
