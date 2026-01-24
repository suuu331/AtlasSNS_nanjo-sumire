<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController; //行を追加


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//「新規ユーザー登録」と「ログイン」のルーティングに関する処理
require __DIR__ . '/auth.php';

//Route::get('top', [PostsController::class, 'index'])->name('top');//->name('top')追加
// 修正後
Route::get('top', [ProfileController::class, 'index']);//->name('top');

Route::get('profile', [ProfileController::class, 'profile'])->name('profile');//追加

// --- ★ここを付け足す★ ---
// プロフィールを更新する（保存する）ためのルート
Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');


//Route::get('search', [UsersController::class, 'index']);

Route::get('follow-list', [PostsController::class, 'index']);
Route::get('follower-list', [PostsController::class, 'index']);

//  ログアウトルートの追記
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout'); // ★ ログアウト処理用のルート名を定義


// フォローリストページ
Route::get('/follow-list', [App\Http\Controllers\ProfileController::class, 'followList'])->name('follow.list');

// フォロワーリストページ
Route::get('/follower-list', [App\Http\Controllers\ProfileController::class, 'followerList'])->name('follower.list');

// 相手のプロフィールページを表示するルート
Route::get('/user/{id}/profile', [ProfileController::class, 'userProfile'])->name('user.profile');


// ユーザー検索ページ
Route::get('/search', [ProfileController::class, 'searchIndex'])->name('user.search');


// 投稿データの送信先ルートを定義
//Route::post('/post/create', [App\Http\Controllers\ProfileController::class, 'postStore'])->name('post.create');
// 修正後：
//Route::post('/post/create', [App\Http\Controllers\ProfileController::class, 'postStore'])->name('post.store');

// 投稿保存
Route::post('/post/create', [ProfileController::class, 'postStore'])->name('post.store');

// 投稿の編集画面表示（今回はモーダルなので不要な場合もあるが、実装推奨）
Route::get('/post/edit/{id}', [App\Http\Controllers\ProfileController::class, 'edit'])->name('post.edit');

// 投稿の更新処理 (PATCHメソッドを使用)
Route::patch('/post/update/{id}', [App\Http\Controllers\PostsController::class, 'update'])->name('post.update');

// 投稿の削除処理 (DELETEメソッドを使用)
Route::delete('/post/delete/{id}', [App\Http\Controllers\ProfileController::class, 'delete'])->name('post.delete');


// トップページにアクセスしたときに ProfileController の index メソッドを呼び出す
Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('top');

//ユーサー検索のページ
Route::get('/users/search', [App\Http\Controllers\ProfileController::class, 'searchIndex'])->name('user.search');


// フォローする時の送り先
Route::post('/follow/{id}', [App\Http\Controllers\ProfileController::class, 'follow'])->name('follow.follow');
// フォロー解除する時の送り先
Route::post('/unfollow/{id}', [App\Http\Controllers\ProfileController::class, 'unfollow'])->name('follow.unfollow');
