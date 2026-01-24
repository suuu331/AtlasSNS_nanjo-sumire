<x-login-layout>

   <!-- １．プロフィール編集画面  -->

  <div class="profile-edit-container">
    <!--  エラー表示 -->
    @if ($errors->any())
      <div style="color: red; margin-bottom: 20px; text-align: center;">
        <ul style="list-style: none;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
      </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="profile-flex-box">

        <!--  アイコン表示  -->
        <div class="profile-icon-area">
          <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : asset('images/icon1.png') }}" class="user-icon">
        </div>

        <div class="profile-form-area">
          {{-- 各入力フォーム --}}
          <p><label>ユーザー名</label> <input type="text" name="username" value="{{ $user->username }}"></p>
          <p><label>メールアドレス</label> <input type="email" name="email" value="{{ $user->email }}"></p>
          <p><label>パスワード</label> <input type="password" name="password"></p>
          <p><label>パスワード確認</label> <input type="password" name="password_confirmation"></p>
          <p><label>自己紹介</label> <textarea name="bio">{{ $user->bio }}</textarea></p>

          <!--  アイコン画像：見本の「グレーの枠」を再現 -->
          <div class="file-input-row">
            <label>アイコン画像</label>
            <div class="file-box">
               <label class="file-label">
                 <input type="file" name="images" class="file-hidden">
                 <span class="file-dummy">ファイルを選択</span>
               </label>
            </div>
           </div>


          <div class="submit-btn-area">
            <button type="submit" class="update-btn">更新</button>
          </div>
        </div>
      </div>
    </form>
  </div>

</x-login-layout>
