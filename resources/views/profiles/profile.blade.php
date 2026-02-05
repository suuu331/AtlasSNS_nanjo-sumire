<x-login-layout>

<!-- アイコンクリック後の 相手のプロフィール -->

  <div class="other-profile-container">
    {{-- ヘッダー部分 --}}
    <div class="user-profile-header">
      <div class="profile-header-left">
        <img src="{{ asset($user->icon_image ? 'storage/'.$user->icon_image : 'images/icon1.png') }}" class="other-user-icon">
      </div>

      <div class="profile-header-center">
        <div class="profile-row">
          <label>ユーザー名</label>
          <span>{{ $user->username }}</span>
        </div>
        <div class="profile-row">
          <label>自己紹介</label>
          <span>{{ $user->bio }}</span>
        </div>
      </div>

      <div class="profile-header-right">
        @if(Auth::id() !== $user->id)
          @if($is_following)
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
        @endif
      </div>
    </div>

    {{-- 投稿一覧エリア --}}
    <div class="user-posts">
      @foreach($posts as $post)
        <div class="post-item">
          <div class="post-item-left">
            <img src="{{ asset($post->user->icon_image ? 'storage/'.$post->user->icon_image : 'images/icon1.png') }}" class="post-icon">
          </div>
          <div class="post-item-main">
            <div class="post-info">
              <span class="post-user">{{ $post->user->username }}</span>
              <span class="post-date">{{ $post->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <p class="post-text">{{ $post->post }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</x-login-layout>
