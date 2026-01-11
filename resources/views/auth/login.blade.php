<x-logout-layout>
    {{--
        ロゴや外枠（container）は x-logout-layout 側ですでに用意されているため、
        ここではフォームの中身だけを記述します。
    --}}

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
