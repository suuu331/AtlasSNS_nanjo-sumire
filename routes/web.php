<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostsController; // 必要に応じて残す
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// --- ログインなしでOK（新規登録・ログイン） ---
require __DIR__ . '/auth.php';

// --- ★ ログイン中のみアクセス可能なグループ ---
Route::middleware(['auth'])->group(function () {

    // 1. トップページ
    Route::get('/', [ProfileController::class, 'index'])->name('top');
    Route::get('/top', [ProfileController::class, 'index']);

    // 2. プロフィール編集・更新
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // 3. ユーザー検索
    Route::get('/search', [ProfileController::class, 'searchIndex'])->name('user.search');

    // 4. フォロー・フォロワーリスト（ProfileControllerに統一！）
    Route::get('/follow-list', [ProfileController::class, 'followList'])->name('follow.list');
    Route::get('/follower-list', [ProfileController::class, 'followerList'])->name('follower.list');

    // 5. 相手のプロフィールページ
    Route::get('/user/{id}/profile', [ProfileController::class, 'userProfile'])->name('user.profile');

    // 6. フォロー・解除
    Route::post('/follow/{id}', [ProfileController::class, 'follow'])->name('follow.follow');
    Route::post('/unfollow/{id}', [ProfileController::class, 'unfollow'])->name('follow.unfollow');

    // 7. 投稿操作
    Route::post('/post/create', [ProfileController::class, 'postStore'])->name('post.store');
    Route::patch('/post/update/{id}', [PostsController::class, 'update'])->name('post.update');
    Route::delete('/post/delete/{id}', [ProfileController::class, 'delete'])->name('post.delete');

    // 8. ログアウト
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
