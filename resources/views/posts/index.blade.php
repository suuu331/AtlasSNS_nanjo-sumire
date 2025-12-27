<x-login-layout>

<h2>タイムライン</h2>

  <div class="post-form-wrapper">
    @auth
      <div class="user-icon-area">
        <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : asset('images/icon1.png') }}"
             alt="自分のアイコン" class="user-icon">
      </div>

      <form action="{{ route('post.store') }}" method="POST" class="post-form">
        @csrf
        <textarea name="post" placeholder="投稿内容を入力" required></textarea>

        <button type="submit" class="submit-button">
          <img src="{{ asset('images/edit_h.png') }}" alt="投稿" class="post-image">
        </button>

        @error('post')
          <div class="error-message">{{ $message }}</div>
        @enderror
      </form>
    @else
      <p>投稿するにはログインしてください。</p>
    @endauth
  </div>

  <hr>

  <div class="timeline-posts">
    @foreach ($posts as $post)
      <div class="post-item">
        <div class="user-info">
          <img src="{{ $post->user->images ? asset('storage/' . $post->user->images) : asset('images/icon1.png') }}"
               alt="{{ $post->user->username }}のアイコン" class="post-icon">
          <div class="name-and-time">
            <span class="username">{{ $post->user->username }}</span>
            <span class="post-time">{{ $post->created_at->format('Y-m-d H:i') }}</span>
          </div>
        </div>

        <p class="post-content">{{ $post->post }}</p>

        @if (Auth::id() === $post->user_id)
          <div class="post-actions">
            <button class="edit-btn" data-post-id="{{ $post->id }}" data-post-text="{{ $post->post }}">
                <img src="{{ asset('images/edit.png') }}" alt="編集">
            </button>

            <form action="{{ route('post.delete', $post->id) }}" method="POST" class="delete-form" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" onclick="return confirm('本当に削除しますか？')"
                        data-default-img="{{ asset('images/delete.png') }}"
                        data-hover-img="{{ asset('images/delete_hover.png') }}">
                    <img src="{{ asset('images/delete.png') }}" alt="削除" class="delete-img">
                </button>
            </form>
          </div>
        @endif
      </div>
    @endforeach
  </div>

  <div id="edit-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100;">
      <div class="modal-content" style="background: white; margin: 10% auto; padding: 20px; width: 50%; border-radius: 8px;">
          <h3>投稿内容の編集</h3>
          <form id="edit-form" method="POST">
              @csrf
              @method('PATCH')
              <input type="hidden" name="id" id="modal-post-id">
              <textarea name="post" id="modal-post-text" rows="5" style="width: 100%; margin-bottom: 10px;"></textarea>
              <div style="text-align: right;">
                  <button type="submit" class="btn btn-primary">更新</button>
                  <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-modal').style.display='none'">キャンセル</button>
              </div>
          </form>
      </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('edit-modal');
      const form = document.getElementById('edit-form');
      const postIdInput = document.getElementById('modal-post-id');
      const postTextInput = document.getElementById('modal-post-text');

      // 編集ボタンのクリックイベント
      document.querySelectorAll('.edit-btn').forEach(button => {
          button.addEventListener('click', function() {
              const postId = this.getAttribute('data-post-id');
              const postText = this.getAttribute('data-post-text');
              postTextInput.value = postText;
              postIdInput.value = postId;

              const updateUrlTemplate = "{{ route('post.update', ['id' => 'POST_ID_PLACEHOLDER']) }}";
              form.action = updateUrlTemplate.replace('POST_ID_PLACEHOLDER', postId);
              modal.style.display = 'block';
          });
      });

      // 削除ボタンのホバーイベント
      document.querySelectorAll('.delete-button').forEach(button => {
          const img = button.querySelector('.delete-img');
          const defaultSrc = button.getAttribute('data-default-img');
          const hoverSrc = button.getAttribute('data-hover-img');

          button.addEventListener('mouseenter', () => { img.src = hoverSrc; });
          button.addEventListener('mouseleave', () => { img.src = defaultSrc; });
      });
  });
  </script>

</x-login-layout>
