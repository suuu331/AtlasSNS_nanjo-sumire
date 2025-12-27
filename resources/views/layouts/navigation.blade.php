        <div id="head">
            <!-- 1. トップページを設定 -->
            <!-- <h1><a href="{{ route('top') }}"><img src="images/atlas.png"></a></h1> -->
            <h1><a href="{{ route('top') }}"><img src="{{ asset('images/atlas.png') }}"></a></h1>
            <div id="">
                <div id="">
                    <!-- 2,ユーザー名を表示
                     Authファサードで認証済みユーザーを取得する
                     ユーザーの名前はmy adminのuses　カラムのなかの名前-->
                    @auth
                    <p>{{ Auth::user()->username }}さん</p>
                    @endauth
                </div>
                <ul>
                    <!-- 3,各リンクに正しいルート名を設定
                     ホームへのルートの名前をtop に指定する -->
                    <li><a href="{{ route('top') }}">ホーム</a></li>
                    <li><a href="{{ route('profile') }}">プロフィール</a></li>

                     <!-- <a href="">ログアウト</a> ←元に入っていたもの
                       4,ログアウトをPOSTフォームで記述
                      Laravelの認証システムはCSRF保護とセキュリティのベストプラクティスに従って、ログアウトを POST リクエストとして処理するように設計されているため-->
                    <li><form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ログアウト</button></form>
                    </li>

                </ul>
            </div>
        </div>
