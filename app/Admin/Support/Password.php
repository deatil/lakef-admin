<?php

declare (strict_types = 1);

namespace App\Admin\Support;

/**
 * 密码
 */
class Password
{
    protected $salt = '';
    
    /**
     * 设置盐
     *
     * @param $salt 加密盐
     *
     * @return $this
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    
    /**
     * 密码加密
     *
     * @param $password
     * @param $encrypt 传入加密串，在修改密码时做认证
     *
     * @return array/password
     */
    public function encrypt($password, $encrypt = '')
    {
        $pwd = [];
        $pwd['encrypt'] = $encrypt ? $encrypt : $this->randomString();
        $pwd['password'] = md5(md5($password . $pwd['encrypt']) . $this->salt);
        return $encrypt ? $pwd['password'] : $pwd;
    }
    
    /**
     * 产生一个指定长度的随机字符串,并返回
     *
     * @param type $len 产生字符串的长度
     *
     * @return string 随机字符串
     */
    protected function randomString($len = 6)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, intval(ceil($len / strlen($pool))))), 0, $len);
    }

}
