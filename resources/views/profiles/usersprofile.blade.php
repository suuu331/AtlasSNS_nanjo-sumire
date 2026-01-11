<x-login-layout>

    <!-- {{-- ==========================================
             １．プロフィール編集画面（自分の場合）
        ========================================== --}}-->
   <div class="profile-edit-container">
       <h2>プロフィール編集</h2>

            {{-- エラー表示 --}}
            @if ($errors->any())
                <div style="color: red; margin-bottom: 20px;">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

         <form action="{{ route('profile.update') }}" method="POST"enctype="multipart/form-data">
                @csrf
             <div class="profile-flex-box"> {{-- ← CSSと合わせるため styleを消してクラス名に --}}

                    {{-- アイコン表示 --}}
                    <div class="profile-icon-area">
                        @if(Auth::user()->images)
                            <img src="{{ asset('storage/' . Auth::user()->images) }}" width="60">
                        @else
                            <img src="{{ asset('images/icon1.png') }}" width="60">
                        @endif
                     </div>

                     <div class="profile-form-area"> {{-- ← ここもクラス名に --}}
                       {{-- 各入力フォーム --}}
                        <p><label>ユーザー名</label> <input type="text" name="username" value="{{ $user->username }}"></p>
                        <p><label>メールアドレス</label> <input type="email" name="email" value="{{ $user->email }}"></p>
                        <p><label>パスワード</label> <input type="password" name="password"></p>
                        <p><label>パスワード確認</label> <input type="password" name="password_confirmation"></p>
                        <p><label>自己紹介</label> <textarea name="bio">{{ $user->bio }}</textarea></p>
                        <p><label>アイコン画像</label> <input type="file" name="images" class="input-file"></.>

                        <div class="submit-btn-area">
                            <button type="submit">更新</button>
                        </div>
                     </div>
             </div>
         </form>
   </div>

</x-login-layout>
