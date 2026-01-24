<x-login-layout>

  <div class="follower-list-header"> {{-- CSS共通なのでクラス名はそのままでOK --}}
    <h2>フォローリスト</h2>
    <div class="follow-icons">
        @foreach($users as $user)
            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
                <img src="{{ $user->images ? asset('storage/' . $user->images) : asset('images/icon1.png') }}"
                     alt="{{ $user->username }}" class="list-user-icon">
            </a>
        @endforeach
    </div>
  </div>

  <div class="separator-line"></div>

  <div class="post-list">
    @foreach($posts as $post)
    <div class="post-item">
        <div class="post-icon">
            <a href="{{ route('user.profile', ['id' => $post->user->id]) }}">
                <img src="{{ $post->user->images ? asset('storage/' . $post->user->images) : asset('images/icon1.png') }}" class="post-icon-img">
            </a>
        </div>

        <div class="post-main-content">
            <div class="post-header-flex">
                <p class="user-name">{{ $post->user->username }}</p>
                <p class="post-date">{{ $post->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <p class="post-text">{{ $post->post }}</p>
        </div>
    </div>
    @endforeach
  </div>

</x-login-layout>
