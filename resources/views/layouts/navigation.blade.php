
<!-- アコーディオンメニューのレイアウト -->
<div id="head">
    <div class="header-left">
        <a href="{{ route('top') }}">
            <img src="{{ asset('images/atlas.png') }}" class="atlas-logo">
        </a>
    </div>

    <div class="header-right">
        <div class="menu-container">
            {{-- クリックに反応するエリア --}}
            <div class="menu-trigger">
                @auth
                    <p class="user-name">{{ Auth::user()->username }} さん</p>
                @endauth

                {{-- 矢印 --}}
                <span class="arrow"></span>

                {{-- プロフィール画像 --}}
                @auth
                    @if(!empty(Auth::user()->images))
                        <img src="{{ asset('storage/' . Auth::user()->images) }}" class="header-icon">
                    @else
                        <img src="{{ asset('images/icon1.png') }}" class="header-icon">
                    @endif
                @endauth
            </div>

            {{-- ドロップダウンメニュー --}}
            <ul class="menu-content">
                <li><a href="{{ route('top') }}">HOME</a></li>
                <li><a href="{{ route('profile') }}" class="edit-link">プロフィール編集</a></li>
                <li>
                    {{-- ログアウトは form なので注意 --}}
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                        @csrf
                    </form>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       ログアウト
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
