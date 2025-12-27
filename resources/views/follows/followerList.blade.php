<x-login-layout>


  <h2>フォロワーリスト</h2>

  <div class="follow-list-container">
      <h2>Follower List</h2>
    <div class="follow-icons">
        @foreach($followers as $user)
            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
                @if($user->images)
                    <img src="{{ asset('storage/' . $user->images) }}" alt="{{ $user->username }}" width="50">
                @else
                    <img src="{{ asset('images/icon1.png') }}" alt="default-icon" width="50">
                @endif
            </a>
        @endforeach
    </div>
  </div>

  <hr> <div class="post-list">
    @foreach($posts as $post)
    <div class="post-item" style="border-bottom: 1px solid #ccc; padding: 10px; display: flex;">
        <div class="post-icon">
            <a href="{{ route('user.profile', ['id' => $post->user->id]) }}">
                @if($post->user->images)
                    <img src="{{ asset('storage/' . $post->user->images) }}" width="50">
                @else
                    <img src="{{ asset('images/icon1.png') }}" width="50">
                @endif
            </a>
        </div>

        <div class="post-content" style="margin-left: 15px;">
            <p class="user-name"><strong>{{ $post->user->username }}</strong></p>
            <p class="post-text">{{ $post->post }}</p>
            <p class="post-date" style="font-size: 0.8em; color: gray;">
                {{ $post->created_at->format('Y-m-d H:i') }}
            </p>
        </div>
    </div>
    @endforeach
  </div>

</x-login-layout>
