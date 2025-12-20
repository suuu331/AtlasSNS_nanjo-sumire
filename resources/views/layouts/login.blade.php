<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!--IEブラウザ対策-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="ページの内容を表す文章" />
  <title></title>
  <link rel="stylesheet" href="{{ asset('css/reset.css') }} ">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }} ">
  <!--スマホ,タブレット対応-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Scripts -->
  <!--サイトのアイコン指定-->
  <link rel="icon" href="画像URL" sizes="16x16" type="image/png" />
  <link rel="icon" href="画像URL" sizes="32x32" type="image/png" />
  <link rel="icon" href="画像URL" sizes="48x48" type="image/png" />
  <link rel="icon" href="画像URL" sizes="62x62" type="image/png" />
  <!--iphoneのアプリアイコン指定-->
  <link rel="apple-touch-icon" href="画像のURL" /><!-- 変更:iconの後の-precomposedを削除 -->
  <!--OGPタグ/twitterカード-->
</head>

<body>
  <header>
    @include('layouts.navigation')
  </header>
  <!-- Page Content -->
  <div id="row">
    <div id="container">
      {{ $slot }}
    </div>
    <div id="side-bar">
      @auth
      <div id="confirm">
        <p>{{ Auth::user()->username }}さんの</p>
        <!-- {{ Auth::user()->username }}に変更 -->
        <div>
          <p>フォロー数</p>
          <p>{{ $followingCount }}名</p>
          <!-- {{ $followingCount }}名に変更 -->
        </div>
        <p class="btn"><a href="{{ route('follow.list') }}">フォローリスト</a></p>
        <div><!-- ルートヘルパー関数を使って修正 -->
          <p>フォロワー数</p>
          <p>{{ $followerCount }}名</p>
          <!-- {{ $followerCount }}に変更 -->
        </div>
        <p class="btn"><a href="{{ route('follower.list') }}">フォロワーリスト</a></p>
      </div><!-- ルートヘルパー関数を使って修正 -->

      <!-- 下記追加 -->
      @else
      <div id="confirm">
          <p>ログインすると、フォロー/フォロワーの状況が表示されます。</p>
        </div>
      @endauth


      <p class="btn"><a href="{{ route('user.search') }}">ユーザー検索</a></p>
    </div>
  </div>
  <footer>
  </footer>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="JavaScriptファイルのURL"></script>
  <script src="JavaScriptファイルのURL"></script>
</body>

</html>
