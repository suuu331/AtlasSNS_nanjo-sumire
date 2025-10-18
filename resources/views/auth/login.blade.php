<x-logout-layout>

  <!-- 適切なURLを入力してください -->
  {!! Form::open(['url' => 'login']) !!}
<!-- @csrf（セキュリティトークン）を追加 -->
@csrf


  <p>AtlasSNSへようこそ</p>

  {{ Form::label('email', 'メールアドレス') }}
  {{ Form::text('email',null,['class' => 'input','placeholder'=> 'メールアドレス' ]) }}

  {{ Form::label('password','パスワード') }}
  {{ Form::password('password',['class' => 'input','placeholder' => 'パスワード']) }}

  {{ Form::submit('ログイン') }}

  <p><a href="register">新規ユーザーの方はこちら</a></p>

  {!! Form::close() !!}

</x-logout-layout>
