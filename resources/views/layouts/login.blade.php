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
      <div class="confirm">
        {{-- Auth::user() が null の場合にエラーにならないよう optional() を使うか、if文で囲みます --}}
    @if(Auth::check())
        <p>{{ Auth::user()->username }}さんの</p>

        <div class="side-row">
            <p class="side-label">フォロー数</p>
            <p class="side-number">{{ Auth::user()->followings()->count() }}名</p>
        </div>

        <div class="side-btn-container">
            <a href="/follow-list" class="btn-blue">フォローリスト</a>
        </div>

        <div class="side-row">
            <p class="side-label">フォロワー数</p>
            <p class="side-number">{{ Auth::user()->followers()->count() }}名</p>
        </div>

        <div class="side-btn-container">
            <a href="/follower-list" class="btn-blue">フォロワーリスト</a>
        </div>
     @endif
    </div>

    <div class="search-area">
        <a href="/search" class="btn-blue-large">ユーザー検索</a>
    </div>
</div>




  </div>
  <footer>
  </footer>
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="JavaScriptファイルのURL"></script>
  <script src="JavaScriptファイルのURL"></script>

  <!-- ★ここに追記  -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.menu-trigger');
        const content = document.querySelector('.menu-content');

        // 要素が存在するか確認してからイベントを設定する（エラー防止）
        if(trigger && content) {
            trigger.addEventListener('click', function() {
                // 'active'クラスを付け外しする
                this.classList.toggle('active');
                content.classList.toggle('active');
            });
        }
    });
  </script>
</body>

</html>
