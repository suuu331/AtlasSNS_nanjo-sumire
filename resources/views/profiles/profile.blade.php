<x-login-layout>

<h2>{{ $user->username }}さんのプロフィール</h2>
    <!-- <p>ここで相手の投稿一覧などを表示します（作成中）</p> -->


    <div class="user-profile-header" style="display: flex; padding: 20px; border-bottom: 2px solid #ccc;">
        <div>
            @if($user->images)
                <img src="{{ asset('storage/' . $user->images) }}" width="100">
            @else
                <img src="{{ asset('images/icon1.png') }}" width="100">
            @endif
        </div>
        <div style="margin-left: 30px;">
            <h3>ユーザー名: {{ $user->username }}</h3>
            <p>自己紹介: {{ $user->bio }}</p> </div>

        <div style="margin-left: auto;">
            @if(Auth::id() !== $user->id) @if($is_following)
                    <form action="{{ route('follow.unfollow', ['id' => $user->id]) }}" method="POST">
                       @csrf
                       <button type="submit">フォロー解除</button>
                    </form>
                @else
                    <form action="{{ route('follow.follow', ['id' => $user->id]) }}" method="POST">
                       @csrf
                       <button type="submit">フォローする</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="user-posts">
        @foreach($posts as $post)
            <div style="border-bottom: 1px solid #ddd; padding: 15px; display: flex;">
                <img src="{{ asset($post->user->images ? 'storage/'.$post->user->images : 'images/icon1.png') }}" width="50" height="50">
                <div style="margin-left: 15px;">
                    <p><strong>{{ $post->user->username }}</strong></p>
                    <p>{{ $post->post }}</p>
                    <p style="font-size: 0.8em; color: gray;">{{ $post->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        @endforeach
    </div>

</x-login-layout>
