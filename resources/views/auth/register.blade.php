<x-logout-layout>
    <!-- 適切なURLを入力してください -->
{!! Form::open(['url' => '/register']) !!}
<!-- @csrfを追加 -->
@csrf

<!-- css編集時に　Pタグに変更 -->
<p class="welcome-msg">新規ユーザー登録</p>

@if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
    @endif

    <!-- css編集時変更 -->
<div class="form-group">
  {{ Form::label('ユーザー名') }}
  {{ Form::text('username',null,['class' => 'input']) }}
</div>

<div class="form-group">
  {{ Form::label('メールアドレス') }}
  {{ Form::email('email',null,['class' => 'input']) }}
</div>

<div class="form-group">
    {{ Form::label('password', 'パスワード') }}
    {{ Form::password('password', ['class' => 'input']) }}
</div>

<div class="form-group">
    {{ Form::label('password_confirmation', 'パスワード確認') }}
    {{ Form::password('password_confirmation', ['class' => 'input']) }}
</div>
<!-- ↑textをpasswordに変更 -->

<div class="btn-area">
        {{ Form::submit('新規登録', ['class' => 'btn-submit']) }}
    </div>

    <div class="register-link">
        <a href="login">ログイン画面へ戻る</a>
    </div>


{!! Form::close() !!}
</x-logout-layout>
