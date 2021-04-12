<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

use App\Admin\Model\User as UserModel;
use App\Admin\Support\Password;

/**
 * 登陆
 */
class Passport extends Base
{
    /**
     * 验证码
     */
    public function getCaptcha()
    {
        $phraseBuilder = new PhraseBuilder(4);
        $captcha = new CaptchaBuilder(null, $phraseBuilder);
        $captcha->build();
        
        $this->session->set('captcha_id', $captcha->getPhrase());
        
        return $captcha->inline();
    }
    
    /**
     * 登陆页面
     */
    public function getLogin()
    {
        if ($this->session->get('adminid')) {
            return $this->response->redirect(admin_url('index'));
        }
        
        return $this->view('admin.passport.login');
    }
    
    /**
     * 提交登陆
     */
    public function postLogin()
    {
        if ($this->session->get('adminid')) {
            return $this->errorJson('你已登陆');
        }
        
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'email' => 'required|email|bail',
                'password' => 'required|min:6',
                'captcha' => 'required',
            ],
            [
                'email.required' => '邮箱必填',
                'email.email' => '邮箱格式错误',
                'password.required' => '密码必填',
                'password.min' => '密码最少6位',
                'captcha.required' => '验证码必填',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $captcha = $this->request->post('captcha');
        
        if (strtolower($captcha) != strtolower($this->session->get('captcha_id'))) {
            return $this->errorJson('验证码错误');
        }
        
        $info = UserModel::query()->where([
            'email' => $email,
        ])->first();
        
        if (empty($info)) {
            return $this->errorJson('账户不存在或者密码错误');
        }
        
        $encryptPassword = (new Password)
            ->setSalt($this->config->get('serverlog.passport_salt'))
            ->encrypt($password, $info['salt']);
        if ($info['password'] != $encryptPassword) {
            return $this->errorJson('账户不存在或者密码错误');
        }
        
        if ($info['status'] !== 1) {
            return $this->errorJson('用户状态异常');
        }
        
        $this->session->set('adminid', $info['id']);
        
        return $this->successJson('登陆成功');
    }
    
    /**
     * 退出
     */
    public function getLogout()
    {
        // 删除登陆状态
        $this->session->forget('adminid');
        
        return $this->response->redirect(admin_url('passport/login'));
    }
}
