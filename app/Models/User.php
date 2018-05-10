<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
// use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    use HasApiTokens;

    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 用户名
        'name',
        // 真实名字
        'real_name',
        // 手机号
        'phone',
        // 邮箱
        'email',
        // 性别
        'sex',
        // 头像
        'avatar',
        // 是否禁止
        'is_banned',
        // 微信
        'wechat_openid',
        'wechat_unionid',
        // 是否认证
        'verified',
        // 登录时间
        'logined_at',
        // 登出时间
        'logouted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * [findForPassport passport通过手机号/账号验证]
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function findForPassport($username)
    {
        return $this->where('phone', $username)->first();
    }

    /**
     * [validateForPassportPasswordGrant 通过密码或hash加密的密码]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function validateForPassportPasswordGrant($password)
    {
        return $password == $this->password || Hash::check($password, $this->password);
    }

    /**
     * Determine whether the user is active.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->is_banned === 'yes';
    }
}
