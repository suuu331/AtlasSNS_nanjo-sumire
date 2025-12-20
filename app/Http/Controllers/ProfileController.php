<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // これを追記
use App\Models\Post; //投稿フォームの設置するため追記

class ProfileController extends Controller
{

    // ★★★ これを追加することで、このコントローラーのすべてのアクションにログインが必須になる ★★★
    public function __construct()
    {
        $this->middleware('auth');
    }
    // ここまで***


    public function profile(){
        return view('profiles.profile');
    }


    /* フォローリストページを表示　*/
    public function followList() // ここから下追記 ★
    {
        // 認証済みユーザーがフォローしているユーザーを取得
        $followings = Auth::user()->followings()->get();

        return view('follows.followList', [
            'followings' => $followings
        ]);
    }

    /* フォロワーリストページを表示　*/
    public function followerList()
    {
        // 認証済みユーザーをフォローしているユーザーを取得
        $followers = Auth::user()->followers()->get();

        return view('follows.followerList', [
            'followers' => $followers
        ]);
    } // ★★★ ここまで追記 ★★★


    /**ユーザー検索ページを表示 (検索処理はまだ含めない)*/
    // public function searchIndex()
    //{
    // users/search.blade.php を表示する
    // 検索結果はまだないので、$usersは空で渡します。
    //return view('users.search', [
        // 'users' => collect(), // 空のコレクション
        // 'keyword' => null
    // ]);
    // }

    //ここは検索ページの検索処理含んだ　searchIndex メソッド
    public function searchIndex(Request $request)
    {
        // １　検索ワードを取得（最初はnullまたは空）
        $search_word = $request->input('keyword');

        // 後の課題で使うため、初期化　　$users = [];

        // 2. 検索クエリの開始（Userモデルを対象とする）
        $query = User::query();

        // 3. 自分のユーザーを除外する条件 (常に適用)
        $query->where('id', '!=', Auth::id());

        // 4. 【検索ワードがある場合】部分一致検索を適用
        if (!empty($search_word)) {
            // WHERE username LIKE '%[検索ワード]%' を適用
            $query->where('username', 'LIKE', '%' . $search_word . '%');
        }

        // 5. クエリを実行し、結果を取得
        // ※ 検索ワードがない場合は「自分以外の全ユーザー」が取得される
        // ※ 検索ワードがある場合は「部分一致するユーザー」が取得される
        $users = $query->get();

        // ★★★ 追記１　ログインユーザーがフォローしているユーザーのIDリストを取得
        // exists() メソッドの呼び出しを減らすため、あらかじめIDリストを用意する
        //$following_ids = Auth::user()->followings()->pluck('follow', 'followed_id')->keys()->toArray(); 修正↓
        $following_ids = Auth::user()->followings()->pluck('followed_id')->toArray();
        // ここまで ★★★

        // ６　呼び出すビューを 'users.search' に修正
        return view('users.search', [
            'search_word' => $search_word ?? '',
            'users' => $users

            // ★★★ 追記２
            'following_ids' => $following_ids // フォローしているIDリストをビューに渡す
            // ここまで ★★★
        ]);
    }



    // ★★★ postStore メソッドを追記 ★★★
      /* 投稿フォームからデータを受け取り、保存する */
    public function postStore(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'post' => 'required|string|min:1|max:150', // 投稿内容のチェック
        ]);

        // 2. データベースへ保存
        Post::create([
            'user_id' => Auth::id(), // ログインユーザーのID
            'post' => $request->input('post'), // フォームから受け取った投稿内容
        ]);

        // 3. 投稿後、トップページ（'top'ルート）にリダイレクト
        return redirect()->route('top')->with('success', '投稿が完了しました。');
    }
    // ★★★ postStore メソッド追記ここまで ★★★


    /*** 投稿編集モーダル表示用の初期データ取得
        *（今回はモーダルなのでビューを返すのではなく、データをJSONで返すことも多い）
        * 自分の投稿かチェックする */
    public function edit($id)
    {
        $post = Post::where('id', $id)
                    ->where('user_id', Auth::id()) // 自分の投稿のみを対象とする
                    ->first();

        // 投稿が見つからないか、自分のものでなければエラーを返す
        if (!$post) {
            abort(403, '投稿が見つからないか、編集権限がありません。');
        }

        // データを返す（モーダル用なのでJSONで返すのが理想だが、ここでは簡単のため省略）
        return response()->json($post);
    }

    /**
     * 投稿内容の更新処理 (PATCH/PUT)
     * 自分の投稿かチェックし、バリデーションも行う
     */
    public function update(Request $request, $id)
    {
        // 1. 自分の投稿かチェック
        $post = Post::where('id', $id)
                    ->where('user_id', Auth::id())
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


    /**
     * トップページ（タイムライン）を表示
     * 自分の投稿とフォローユーザーの投稿のみを取得する
     */
    public function index()
    {
       // $posts を初期化（ログインしていない場合に備える）
        $posts = [];

        // ユーザーがログインしているかチェック
        if (Auth::check()) {
            // ログインユーザーがフォローしているユーザーIDのリストを取得
            // (Auth::user() は if ブロック内でのみ安全に呼び出す)
            $following_ids = Auth::user()->followings()->pluck('users.id');

            // 取得したIDリストと自分のIDを含む投稿を取得
            $posts = Post::whereIn('user_id', $following_ids) // フォローしているユーザーの投稿
                         ->orWhere('user_id', Auth::id())       // または自分の投稿
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->get();
        }

        // 投稿一覧データ ('posts') をビューに渡す (if文の外で渡すことで、必ず渡される)
        return view('posts.index', [
            'posts' => $posts
        ]);
    }


    /**
     * 投稿の削除処理
     */
    public function delete($id)
    {
        // 1. 自分の投稿かチェックし、投稿を取得
        $post = Post::where('id', $id)
                    ->where('user_id', Auth::id()) // 自分の投稿のみを対象とする
                    ->firstOrFail(); // 該当しなければ404エラー

        // 2. 削除実行
        $post->delete();

        // 3. トップページに戻る
        return redirect()->route('top')->with('success', '投稿を削除しました。');
    }

}
