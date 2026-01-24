<x-logout-layout>

<div class="added-container">
        <div class="welcome-text">
            <p>{{ session('username') }}さん</p>
            <p>ようこそ！AtlasSNSへ！</p>
        </div>

        <div class="message-area">
            <p>ユーザー登録が完了いたしました。</p>
            <p>早速ログインをしてみましょう！</p>
        </div>

        <div class="btn-area-center">
            <a href="login" class="btn-submit" style="text-decoration:none;">ログイン画面へ</a>
        </div>
    </div>

</x-logout-layout>
