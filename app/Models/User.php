<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// 追加: BelongsToManyリレーションを使うために必要
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // 追加
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    //* 1,自分がフォローしているユーザーを取得（フォロー数カウントに利用）
    public function followings(): BelongsToMany
    {
        // フォロー関係を保持する中間テーブル: 'follows'
        // 自分のIDカラム: 'following_id'
        // 相手のIDカラム: 'followed_id'
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'followed_id')->withTimestamps();
    }

    //* 2,自分をフォローしているユーザーを取得（フォロワー数カウントに利用）
    public function followers(): BelongsToMany
    {
        // // 中間テーブル: 'follows'
        // 自分のIDが入っているカラム: 'followed_id'
        // 相手のIDが入っているカラム: 'following_id'
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'following_id')->withTimestamps();
    }


    //追記
    public function posts() // ★ここが関係名★
    {
    return $this->hasMany(Post::class);
    }

}
