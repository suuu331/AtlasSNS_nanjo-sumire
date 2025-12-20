<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // ★★★ この行を追加 ★★★
    protected $fillable = [
        'user_id', // ログインユーザーIDの保存を許可
        'post',    // 投稿内容の保存を許可
    ];


    // ★★★ ここにリレーションメソッドを追記 ★★★
    /**
     * この投稿を作成したユーザーを取得します。
     */
    public function user()
    {
        // 投稿は一人のユーザーに属する (belongsTo)
        // user_id を外部キーとして User モデルと関連付けます
        return $this->belongsTo(User::class);
    }
}
