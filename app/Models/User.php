<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        return $this->statuses()->orderBy('created_at', 'desc');
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
}
