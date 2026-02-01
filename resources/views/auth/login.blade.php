<x-logout-layout>

    <!-- ログイン画面ページ（入力フォーム） -->

    {{--ロゴや外枠（container）は x-logout-layout 側ですでに用意されているためここではフォームの中身だけを記述--}}

    {!! Form::open(['url' => 'login']) !!}
        <p class="welcome-msg">AtlasSNSへようこそ</p>

        <div class="form-group">
            {{ Form::label('email', 'メールアドレス') }}
            {{ Form::text('email', null, ['class' => 'input']) }}
        </div>

        <div class="form-group">
            {{ Form::label('password', 'パスワード') }}
            {{ Form::password('password', ['class' => 'input']) }}
        </div>

        <div class="btn-area">
            {{ Form::submit('ログイン', ['class' => 'btn-submit']) }}
        </div>

        <div class="register-link">
            <a href="register">新規ユーザーの方はこちら</a>
        </div>
    {!! Form::close() !!}
</x-logout-layout>
