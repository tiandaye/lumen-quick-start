<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // 用户名
            $table->string('name')->nullable()->index();
            // 真实名字
            $table->string('real_name')->nullable()->index();
            // 手机号
            $table->string('phone', 11)->nullable()->index();
            // 邮箱
            $table->string('email')->unique()->nullable()->index();
            // 性别
            $table->tinyInteger('sex')->unsigned()->default(0);
            // 头像
            $table->string('avatar')->nullable();
            // 是否禁止
            $table->enum('is_banned', ['yes',  'no'])->default('no')->index();
            // 微信
            $table->string('wechat_openid')->nullable()->index();
            $table->string('wechat_unionid')->nullable()->index();
            // 密码
            $table->string('password');
            // 是否认证
            $table->boolean('verified')->default(false)->index();
            // 登录时间
            $table->timestamp('logined_at')->nullable();
            // 登出时间
            $table->timestamp('logouted_at')->nullable();

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
