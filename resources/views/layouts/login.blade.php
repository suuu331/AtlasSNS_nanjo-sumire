<!DOCTYPE html>

<!-- 共通の土台（ヘッダー（メニュー）、サイドバー、背景など） -->
<html>
<head>
  <meta charset="utf-8">
   <!--IEブラウザ対策-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Atlas</title>
  <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
   <!--スマホ,タブレット対応-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <header>
    @include('layouts.navigation')
  </header>

  <div id="row">
    <div id="container">
      {{ $slot }}
    </div>

    <!-- サイドメニュー（フォロワー、検索ボタンのところ） -->
    <div id="side-bar">
      <div class="confirm">
        @if(Auth::check())
          <p>{{ Auth::user()->username }}さんの</p>
          <div class="side-row">
            <p class="side-label">フォロー数</p>
            <p class="side-number">{{ Auth::user()->followings()->count() }}人</p>
          </div>
          <div class="side-btn-container">
            <a href="/follow-list" class="btn-blue">フォローリスト</a>
          </div>
          <div class="side-row">
            <p class="side-label">フォロワー数</p>
            <p class="side-number">{{ Auth::user()->followers()->count() }}人</p>
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


  <!-- jQueryの読み込み（これが重要） -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>

    <!-- アコーディオンを動かすスクリプト -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.menu-trigger');
        const content = document.querySelector('.menu-content');
        if(trigger && content) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                content.classList.toggle('active');
            });
            document.addEventListener('click', function(e) {
                if (!trigger.contains(e.target) && !content.contains(e.target)) {
                    trigger.classList.remove('active');
                    content.classList.remove('active');
                }
            });
        }
    });
  </script>
</body>
</html>
