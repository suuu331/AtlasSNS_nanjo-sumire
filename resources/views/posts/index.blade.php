<x-login-layout>

<h2>変更</h2>

<div class="post-form-wrapper">
  @auth
    <div class="post-form-container"> {{-- ← クラス名を追加 --}}
      <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : asset('images/icon1.png') }}"
           alt="自分のアイコン" class="user-icon">

      <form action="{{ route('post.store') }}" method="POST" class="post-form">
        @csrf
        {{-- class="post-input" を追加。これで枠を消します --}}
        <textarea name="post" class="post-input" placeholder="投稿内容を入力してください。" required></textarea>

        {{-- ボタンを右下に配置するためのクラス submit-button --}}
        <button type="submit" class="submit-button">
          <img src="{{ asset('images/post.png') }}" alt="投稿" class="post-image">
        </button>
      </form>
    </div>
   @else
    <div class="post-form-container">
      <p>投稿するにはログインしてください。</p>
    </div>
  @endauth
</div>

  <div class="timeline-posts">
  @foreach ($posts as $post)
    <div class="post-item"> {{-- ← CSSの基準点 --}}

      <img src="{{ $post->user->images ? asset('storage/' . $post->user->images) : asset('images/icon1.png') }}"
           alt="アイコン" class="post-icon">

      <div class="post-main">
        <div class="post-header">
          <span class="post-user-name">{{ $post->user->username }}</span>
          <span class="post-date">{{ $post->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="post-text">
          {{ $post->post }}
        </div>
      </div>

      @if (Auth::id() === $post->user_id)
        <div class="post-buttons"> {{-- クラス名を post-buttons に統一 --}}

          {{-- 編集ボタン --}}
          <button class="edit-btn" data-post-id="{{ $post->id }}" data-post-text="{{ $post->post }}" style="background:none; border:none;">
              <img src="{{ asset('images/edit.png') }}" alt="編集">
          </button>

          {{-- 削除ボタン（ホバー機能付き） --}}
          <form action="{{ route('post.delete', $post->id) }}" method="POST" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="delete-button" onclick="return confirm('本当に削除しますか？')"
                      data-default-img="{{ asset('images/trash-h.png') }}"
                      data-hover-img="{{ asset('images/trash.png') }}"
                      style="background:none; border:none; padding:0;">
                 {{-- 最初は通常時の画像を表示 --}}
                  <img src="{{ asset('images/trash-h.png') }}" alt="削除" class="delete-img">
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

              // route('post.update', '') とすることでベースのURLを取得します
              let baseUrl = "{{ url('/post/update') }}";
              form.action = baseUrl + '/' + postId;
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

  <script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.querySelector('.menu-trigger');
    const menu = document.querySelector('.menu-content');

    if (trigger && menu) {
        trigger.addEventListener('click', function() {
            this.classList.toggle('active'); // 矢印を回転(∧)させる
            menu.classList.toggle('active');   // メニューを表示する
        });
    }
});
</script>

</x-login-layout>
