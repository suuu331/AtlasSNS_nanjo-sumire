<x-login-layout>

<div class="search-top-area">
    <div class="search-form-wrapper">
        <form action="{{ route('user.search') }}" method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="ユーザー名" value="{{ $search_word }}" class="search-input">
            <button type="submit" class="search-btn">
                <img src="{{ asset('images/search.png') }}" alt="検索" class="search-icon-img">
            </button>
        </form>

        {{-- 検索ワード表示（枠なし・右側に配置） --}}
        @if(!empty($search_word))
            <div class="search-word-text">
                検索ワード：{{ $search_word }}
            </div>
        @endif
    </div>
</div>

{{-- 見本通りのグレーの太いライン --}}
<hr class="separator-line">

<div class="user-list-container">
    @foreach($users as $user)
        <div class="user-row">
            {{-- 左側：アイコンと名前 --}}
            <div class="user-profile">
                <img src="{{ $user->icon_image ? asset('storage/' . $user->icon_image) : asset('images/icon1.png') }}" class="user-icon">
                <span class="user-name">{{ $user->username }}</span>
            </div>

            {{-- 右側：ボタン --}}
            <div class="user-action-btn">
                @if(in_array($user->id, $following_ids))
                    <form action="{{ route('follow.unfollow', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-unfollow">フォロー解除</button>
                    </form>
                @else
                    <form action="{{ route('follow.follow', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-follow">フォローする</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>

</x-login-layout>
