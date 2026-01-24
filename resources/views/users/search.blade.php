<x-login-layout>


  <div class="search-container">
    {{-- 検索フォームエリア --}}
    <form action="{{ route('user.search') }}" method="GET" class="search-form">
      <input type="text" name="keyword" placeholder="ユーザー名" value="{{ $search_word }}" class="search-input">
      <button type="submit" class="search-btn">
        <img src="{{ asset('images/search.png') }}" alt="検索">
      </button>
    </form>

    {{-- 検索ワードがある場合のみ表示（任意） --}}
    @if(!empty($search_word))
      <p class="search-word-display">検索ワード：{{ $search_word }}</p>
    @endif
  </div>

  <div class="separator-line"></div>

  {{-- ユーザー一覧エリア --}}
  <div class="user-list">
    @foreach($users as $user)
      <div class="user-item">
        <div class="user-info">
          <img src="{{ $user->images ? asset('storage/' . $user->images) : asset('images/icon1.png') }}" class="list-user-icon">
          <span class="user-name">{{ $user->username }}</span>
        </div>

        <div class="user-action">
          {{-- フォローしているかどうかの判定 --}}
          @if(in_array($user->id, $following_ids))
            {{-- フォロー解除ボタン（赤色） --}}
            <form action="{{ route('follow.unfollow', ['id' => $user->id]) }}" method="POST">
              @csrf
              <button type="submit" class="btn-unfollow">フォロー解除</button>
            </form>
          @else
            {{-- フォローするボタン（青色） --}}
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
