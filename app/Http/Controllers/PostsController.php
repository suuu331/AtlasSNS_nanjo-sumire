<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // ★★★ これを追記

class PostsController extends Controller
{
    //
    public function index(){
        return view('posts.index');
    }

    /*** 投稿フォームからデータを受け取り、保存する*/
    public function postStore(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'post' => 'required|string|max:150',
        ]);

        // 2. データベースへ保存
        Post::create([
            'user_id' => Auth::id(), // ログインユーザーのID
            'post' => $request->input('post'), // フォームから受け取った投稿内容
        ]);

        // 3. 投稿後、トップページ（'top'ルート）にリダイレクト
        return redirect()->route('top')->with('success', '投稿が完了しました。');
    }
}
