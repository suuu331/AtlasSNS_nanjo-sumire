<x-login-layout>
  <!--  -->
<h2>ユーザー検索</h2>
<!--  -->
<div class="search-form-wrapper">
        <form action="{{ route('user.search') }}" method="GET" class="user-search-form">

            <label for="search-keyword" class="sr-only">検索ワード:</label>
            <input type="text"
                   id="search-keyword"
                   name="keyword"        placeholder="ユーザー名を入力">

            <button type="submit" class="search-button">
                <img src="{{ asset('images/search.png') }}" alt="検索" class="search-image">
            </button>

        </form>
    </div>
    <hr>

    <!--検索結果表示エリアを追加 -->
    @if (!empty($search_word))
        <h3>検索ワード: {{ $search_word }}</h3>
    @endif

    <div class="search-results">
        @forelse ($users as $user)
            <div class="user-item">

                <!-- <img src="{{ asset('storage/images/' . $user->images) }}" -->
                     <!-- alt="{{ $user->username }}のアイコン" -->
                     <!-- class="user-icon">  ここ３行を下に変更-->
                <img src="{{ $user->images ? asset('storage/' . $user->images) : asset('images/icon1.png') }}"
                     alt="{{ $user->username }}のアイコン"
                     class="user-icon">

                <span class="user-name">{{ $user->username }}</span>

                <!-- {{-- ★フォローボタンは「データがある時」のエリアに追加★ --}} -->
                <div class="follow-btn-container">
                    @if (in_array($user->id, $following_ids))
                        <form action="{{ route('follow.unfollow', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">フォロー解除</button>
                        </form>
                    @else
                        <form action="{{ route('follow.follow', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">フォローする</button>
                        </form>
                    @endif
                </div>
             </div>
                <!-- {{-- ▲ ここまでが繰り返される ▲ --}} -->

        @empty
         <!-- {{-- ▼ ここは「データが0件の時」だけ表示される ▼ --}} -->
            <p>
                @if (!empty($search_word))
                    「{{ $search_word }}」に一致するユーザーは見つかりませんでした。
                @else
                    データベースに登録されているユーザーはいません。
                @endif
            </p>
        @endforelse
    </div>

</x-login-layout>
