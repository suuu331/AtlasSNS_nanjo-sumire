<x-login-layout>


  <!-- <h2>機能を実装していきましょう。</h2>
   ここから下：投稿フォームの設置-->

  <h2>タイムライン</h2>

  <div class="post-form-wrapper">

    @auth
      <div class="user-icon-area">
        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->username }}のアイコン" class="user-icon">
      </div>

      <form action="{{ route('post.create') }}" method="POST" class="post-form">
        @csrf

       <!-- <label for="post-content">投稿内容:</label> -->
       <label for="post-content" class="sr-only">投稿内容:</label>
       <textarea id="post-content" name="post" placeholder="記入" required></textarea>

       <button type="submit" class="submit-button">
          <img src="{{ asset('images/post_button.png') }}" alt="投稿" class="post-image">
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

<!-- * Bladeファイルへの一覧表示
     * ボタン設置 -->
<div class="timeline-posts">
      @foreach ($posts as $post)
          <div class="post-item">
              <div class="user-info">
                  <img src="{{ asset('storage/' . $post->user->profile_image) }}" alt="{{ $post->user->username }}のアイコン" class="post-icon">
                  <div class="name-and-time">
                      <span class="username">{{ $post->user->username }}</span>
                      <span class="post-time">{{ $post->created_at->format('Y-m-d H:i') }}</span>
                  </div>
              </div>

              <p class="post-content">{{ $post->post }}</p>

              <div class="post-actions">
                  @if (Auth::id() === $post->user_id)
                      <button class="edit-btn" data-post-id="{{ $post->id }}" data-post-text="{{ $post->post }}">
                          編集
                      </button>

                      <form action="{{ route('post.delete', $post->id) }}" method="POST" class="delete-form">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="delete-button"
                                  onclick="return confirm('本当に削除しますか？')"
                                  data-default-img="{{ asset('images/delete.png') }}"
                                  data-hover-img="{{ asset('images/delete_hover.png') }}">
                              <img src="{{ asset('images/delete.png') }}" alt="削除" class="delete-img">
                          </button>
                      </form>
                  @endif
              </div>
          </div>
      @endforeach
  </div>


<!-- 投稿編集モーダル用のHTMLを設置 -->
<div id="edit-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100;">
    <div class="modal-content" style="background: white; margin: 10% auto; padding: 20px; width: 50%;">
        <h3>投稿内容の編集</h3>

        <form id="edit-form" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="id" id="modal-post-id">

            <textarea name="post" id="modal-post-text" rows="5" style="width: 100%;"></textarea>

            <button type="submit">更新</button>
            <button type="button" onclick="document.getElementById('edit-modal').style.display='none'">キャンセル</button>
        </form>
    </div>
</div>




<!-- JavaScriptによるモーダル処理 -->

<script> // ★★★ <script>タグの開始 ★★★
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const postIdInput = document.getElementById('modal-post-id');
    const postTextInput = document.getElementById('modal-post-text');

    // 編集ボタンがクリックされたときの処理
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const postText = this.getAttribute('data-post-text');

            // フォームの入力値に既存の投稿内容をセット（初期値として表示）
            postTextInput.value = postText;
            postIdInput.value = postId; // フォームにIDを設定（hidden fieldは不要だが、慣習として）

            // フォームの送信先URLを動的に変更
            // {{ route('post.update', 0) }} の 0 の部分をJSで置換
            const updateUrlTemplate = "{{ route('post.update', ['id' => 'POST_ID_PLACEHOLDER']) }}";
            form.action = updateUrlTemplate.replace('POST_ID_PLACEHOLDER', postId);

            // モーダルを表示
            modal.style.display = 'block';
        });
    });
});
document.querySelectorAll('.delete-button').forEach(button => {
    const img = button.querySelector('.delete-img');
    const defaultSrc = button.getAttribute('data-default-img');
    const hoverSrc = button.getAttribute('data-hover-img');

    // マウスオーバー時
    button.addEventListener('mouseenter', () => {
        img.src = hoverSrc;
    });

    // マウスアウト時
    button.addEventListener('mouseleave', () => {
        img.src = defaultSrc;
    });
});
</script> <!-- ★★★ <script>タグの終了 ★★★ -->

</x-login-layout>
