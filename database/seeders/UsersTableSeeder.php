<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   // <-- ★追加：データベース操作に必要
use Illuminate\Support\Facades\Hash; // <-- ★追加：パスワード暗号化に必要


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 必須項目（ユーザー名、メールアドレス、パスワード）を登録
        DB::table('users')->insert([
            'username' => '初期ユーザー', // ユーザー名
            'email' => 'test@atlas.com',   // メールアドレス
            'password' => Hash::make('password123'), // 暗号化処理
            'images' => 'icon1.png', // ★ここを追加！アイコン画像の表示★
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 必要に応じて他のテストユーザーも追加できます
        // DB::table('users')->insert([ ...
    }
}
