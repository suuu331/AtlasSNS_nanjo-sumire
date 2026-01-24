<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // ★★★ これを追記
use Illuminate\Support\Facades\Auth; // ★【重要】Authを使うためにこれが必要


class PostsController extends Controller
{
    /**
     * 投稿内容の更新処理 (PATCH/PUT)
     * 自分の投稿かチェックし、バリデーションも行う
     */
    public function update(Request $request, $id)
    {
        // 1. 自分の投稿かチェック
        $post = Post::where('id', $id)
                    ->where('user_id', \Auth::id())
                    ->firstOrFail(); // 見つからなければ404エラー

        // 2. バリデーション（新規投稿と同じルールを適用）
        $request->validate([
            'post' => 'required|string|min:1|max:150',
        ]);

        // 3. データベースを更新
        $post->post = $request->input('post');
        $post->save();

        // 4. トップページに戻る
        return redirect()->route('top')->with('success', '投稿を更新しました。');
    }

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
