<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * Class User
 * @package App\Models
 * @property string $name
 * @property string $password
 * @property string $email
 * @property bool $activated
 * @property string $activation_token
 * @property bool $is_admin
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public static function boot()
    {
        parent::boot();
        static:: creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }


    /**
     * 向用户送发账号激活邮件
     * @param User $user
     */
    public static function sendEmailConfirmationTo(User $user)
    {
        $view = 'emails.confirm';
        $data['user'] = $user;
        $from = 'fthvgb1@163.com';
        $name = 'fthvgb1';
        $to = $user->email;
        $subject = '感谢注册 learn-laravel 应用！请确认你的邮箱。';
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user()->id);
        return Status::whereIn('user_id', $user_ids)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    public function gravatar($size='100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * 用户有多条微博
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'user_id');
    }

    public function followings()
    {
        return $this->belongsToMany(self::class, 'followers', 'user_id', 'follower_id');
    }


    /**
     * 是否已关注
     * @param $user_id
     * @return mixed
     */
    public function isFollowing($user_id)
    {
        return $this->followings()->allRelatedIds()->contains($user_id);
    }

    /**
     * 关注用户
     * @param $user_ids array|mixed 被关注的用户ids
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    /**
     * 取消关注
     * @param $user_ids
     */
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
}
