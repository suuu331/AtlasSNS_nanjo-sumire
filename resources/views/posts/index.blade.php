<x-login-layout>

<!-- トップページ（ログイン前ログイン後） -->


 <!-- 投稿入力の部分 -->

<div class="post-form-wrapper">
  @auth
    <div class="post-form-container">
      <!-- アイコン -->
      <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : asset('images/icon1.png') }}" alt="自分のアイコン" class="user-icon">
      <!-- 投稿フォーム -->
      <form action="{{ route('post.store') }}" method="POST" class="post-form">
        @csrf
        <!-- 入力枠 -->
        <textarea name="post" class="post-input" placeholder="投稿内容を入力してください。" required></textarea>
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


 <!-- 投稿一覧エリア  -->
<div class="timeline-posts">
  @foreach ($posts as $post)
    <div class="post-item">
      <img src="{{ $post->user->images ? asset('storage/' . $post->user->images) : asset('images/icon1.png') }}" alt="アイコン" class="post-icon">
      <div class="post-main">
        <div class="post-header">
          <span class="post-user-name">{{ $post->user->username }}</span>
          <span class="post-date">{{ $post->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="post-text">{{ $post->post }}</div>
      </div>

      @if (Auth::id() === $post->user_id)
      <div class="post-buttons">
        {{-- 編集ボタン --}}
        <a class="js-modal-open" post="{{ $post->post }}" post_id="{{ $post->id }}">
            <img src="{{ asset('images/edit.png') }}" alt="編集" class="btn-img">
        </a>

        {{-- 削除ボタン：aタグに変更して、JSでモーダルを呼ぶ --}}
        <a class="trash-btn js-delete-modal-open" post_id="{{ $post->id }}">
          <img src="{{ asset('images/trash.png') }}"
               alt="削除"class="btn-img"
               onmouseover="this.src='{{ asset('images/trash-h.png') }}'"
               onmouseout="this.src='{{ asset('images/trash.png') }}'">
         </a>
       </div>
       @endif
    </div>
  @endforeach
</div>

<!-- 編集モーダル -->
<div id="edit-modal" class="modal-wrapper">
    <div class="modal-inner">
        <form action="" method="POST" id="edit-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" id="modal-post-id">
            {{-- ★150文字バリデーション設定 --}}
            <textarea name="post" id="modal-post-text" class="modal-textarea" maxlength="150" required></textarea>
            <div class="modal-btn-area">
              <button type="submit" class="modal-update-btn">
                <img src="{{ asset('images/edit.png') }}" alt="更新" class="btn-img">
              </button>
            </div>
        </form>
    </div>
</div>

<!-- 削除モーダル -->
<div id="delete-modal" class="modal-wrapper">
    {{-- delete-confirm クラスで上寄りの位置に調整します --}}
    <div class="modal-inner delete-confirm">
        <div class="modal-text">
            <p>この投稿を削除します。よろしいでしょうか？</p>
        </div>
        <div class="modal-btn-area">
            {{-- 左側：OKボタン（削除実行） --}}
            <form action="" method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">OK</button>
            </form>

            {{-- 右側：キャンセルボタン --}}
            <button type="button" class="btn btn-cancel js-modal-close-delete">キャンセル</button>
        </div>
    </div>
</div>




<!-- JavaScript部分（一番下にまとめて書く！）-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---  編集モーダルの処理 ---
    document.querySelectorAll('.js-modal-open').forEach(button => {
        button.addEventListener('click', function() {
            const post = this.getAttribute('post');
            const id = this.getAttribute('post_id');
            document.getElementById('modal-post-text').value = post;
            document.getElementById('modal-post-id').value = id;
            document.getElementById('edit-form').action = "/post/update/" + id;
            document.getElementById('edit-modal').style.display = 'flex';
        });
    });

    // ---  削除モーダルの処理 ---
    document.querySelectorAll('.js-delete-modal-open').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('post_id');
            // 削除フォームの送り先を書き換える
            document.getElementById('delete-form').action = "/post/delete/" + id;
            // 削除モーダルを表示
            document.getElementById('delete-modal').style.display = 'flex';
        });
    });

    // ---  モーダルを閉じる処理（共通） ---
    // キャンセルボタン（削除用）を押したとき
    const cancelDelete = document.querySelector('.js-modal-close-delete');
    if (cancelDelete) {
        cancelDelete.addEventListener('click', function() {
            document.getElementById('delete-modal').style.display = 'none';
        });
    }

    // モーダルの背景をクリックしたとき
    window.addEventListener('click', function(event) {
        const editModal = document.getElementById('edit-modal');
        const deleteModal = document.getElementById('delete-modal');

        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
        }
    });
});
</script>

</x-login-layout>
