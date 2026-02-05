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
use Illuminate\Support\Facades\Hash; // プロフィールページ作成時これを追加

class ProfileController extends Controller
{
    // ★★★ これを追加することで、このコントローラーのすべてのアクションにログインが必須になる ★★★
    //public function __construct()
    //{ $this->middleware('auth'); }
    // ここまで***



 // プロフィール編集画面を表示する
   public function profile()
   {
    $user = Auth::user(); // ログイン中の自分を取得
    // 自分の編集画面なので $is_following などの判定は不要
    return view('profiles.usersProfile', ['user' => $user]);// ★自分専用のファイルに飛ばす
   }

 // プロフィールを更新する処理
   public function update(Request $request)
    {
    // 3. バリデーション（ご指定の条件をすべて反映！）
      $request->validate([
        'username' => 'required|string|min:2|max:12',
        'email' => 'required|string|email|min:5|max:40|unique:users,email,' . Auth::id(),
        'password' => 'required|alpha_num|min:8|max:20|confirmed', // alpha_numで英数字のみ
        'password_confirmation' => 'required|alpha_num|min:8|max:20',
        'bio' => 'nullable|string|max:150',
        'icon_image' => 'nullable|image|mimes:jpg,png,bmp,gif,svg',
      ]);

      $user = Auth::user();
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->bio = $request->input('bio');
      $user->password = Hash::make($request->input('password')); // パスワードをハッシュ化して保存

      // 画像の保存
      if ($request->hasFile('icon_image')) {
        // ファイル名を取得
        //getClientOriginalName() を使うことで元のファイル名で保存
        $file_name = $request->file('icon_image')->getClientOriginalName();
        // 「public」ディスクの直下に保存
        $request->file('icon_image')->storeAs('', $file_name, 'public'); //('public', $file_name);
        // DBにファイル名を保存
        $user->icon_image = $file_name;
    }

      $user->save();
      return redirect('/top');
      // 完了後にトップへ
}



    /* フォローリストページを表示 */
    public function followList()
    {
        // 1. 自分がフォローしているユーザーを取得
        $users = Auth::user()->followings()->get(); // $followings を $users に
        // 2. フォローしている人のIDだけを抜き出す
        $following_ids = $users->pluck('id');
        // 3. その人たちの投稿を取得（最新順）
        $posts = Post::with('user')
            ->whereIn('user_id', $following_ids)
            ->orderBy('created_at', 'desc')
            ->get();
            // 4. 'users' という名前で送る
        return view('follows.followList', [
            'users' => $users, // ★ 'users' に変更
            'posts' => $posts
        ]);
    }

    /* フォロワーリストページを表示 */
    public function followerList()
    {
        // 1. 自分をフォローしているユーザーを取得
        $users = Auth::user()->followers()->get(); // 変数名を $users に変更

        // 2. その人たちのIDを抜き出す
        $follower_ids = $users->pluck('id');

        // 3. その人たちの投稿を取得（最新順）
        $posts = Post::with('user')
           ->whereIn('user_id', $follower_ids)
           ->orderBy('created_at', 'desc')
           ->get();

        // 4. ビューに渡す（'users' という名前で渡す！）
        return view('follows.followerList', [
            'users' => $users, // ★ここを 'users' にすることでエラーが治ります
            'posts' => $posts
        ]);
    }

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



    //検索ページの検索処理　searchIndex メソッド
    public function searchIndex(Request $request)
    {
        // １　検索ワードを取得（最初はnullまたは空）
        $search_word = $request->input('keyword');
        // 後の課題で使うため、初期化　　$users = [];

        // 2. 検索クエリの開始（Userモデルを対象とする）
        $query = User::query();

        // 3. 自分のユーザーを除外する条件 (常に適用)
        //$query->where('id', '!=', Auth::id());
        if (Auth::check()) {
            $query->where('id', '!=', Auth::id());
    }

        // 4. 【検索ワードがある場合】部分一致検索を適用
        if (!empty($search_word)) {
            // WHERE username LIKE '%[検索ワード]%' を適用
            $query->where('username', 'LIKE', '%' . $search_word . '%');
        }

        // 5. クエリを実行し、結果を取得
        // ※ 検索ワードがない場合は「自分以外の全ユーザー」が取得される
        // ※ 検索ワードがある場合は「部分一致するユーザー」が取得される
        $users = $query->get();

        // フォローリストの取得（ログイン時のみ）
       $following_ids = [];
        if (Auth::check()) {
        $following_ids = Auth::user()->followings()->pluck('followed_id')->toArray();
        }

        // ６　呼び出すビューを 'users.search' に修正
        return view('users.search', [
            'search_word' => $search_word ,
            'users' => $users,
            'following_ids' => $following_ids // フォローしているIDリストをビューに渡す
        ]);
    }


    // ★★★ postStore メソッド ★★★

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
     * トップページ（タイムライン）を表示
     * 自分の投稿とフォローユーザーの投稿のみを取得する
     */
    public function index()
    {
       // 全ユーザーの投稿を取得
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();

        // ユーザーがログインしているかチェック
           if (Auth::check()) {
            // ログインユーザーがフォローしているユーザーIDのリストを取得
            // (Auth::user() は if ブロック内でのみ安全に呼び出す)
            //$following_ids = Auth::user()->followings()->pluck('users.id');

            // 取得したIDリストと自分のIDを含む投稿を取得
            //$posts = Post::whereIn('user_id', $following_ids) // フォローしているユーザーの投稿
                         //->orWhere('user_id', Auth::id())       // または自分の投稿
                         //->with('user')
                         //->orderBy('created_at', 'desc')
                         //->get();
            }

        // 2. ビューに渡す（'posts' という名前で $posts を箱に入れる）
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


    /**
     * ユーザーをフォローする
     */
    public function follow($id)
    {
        // ログインしているユーザーが、相手のID($id)をフォローする
        Auth::user()->followings()->attach($id);
        return back(); // 元の画面（検索画面）にリロードして戻る
    }

    /**
     * ユーザーのフォローを解除する
     */
    public function unfollow($id)
    {
        // ログインしているユーザーが、相手のID($id)のフォローを外す
        Auth::user()->followings()->detach($id);
        return back(); // 元の画面にリロードして戻る
    }


// 相手のプロフィール画面を表示する
public function userProfile($id)
{
    // dd($id); // ← これを1行目に入れて保存
    // クリックされたユーザーの情報を取得
    $user = User::findOrFail($id);

    // そのユーザーの投稿を取得
    $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();

    // 【追加】自分がこのユーザーをフォローしているかチェック（ボタンの切り替え用）
    $is_following //= Auth::user()->Following($id);下に修正
       = Auth::user()->followings()->where('followed_id', $id)->exists();

    return view('profiles.Profile', [
        'user' => $user,
        'posts' => $posts,
        'is_following' => $is_following // 【追加】これを渡す！
    ]);
}

}
