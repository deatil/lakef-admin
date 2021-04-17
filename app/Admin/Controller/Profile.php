<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Support\Password;
use App\Admin\Model\Admin as AdminModel;

/**
 * 个人资料
 *
 * author: deatil
 * create: 2021-4-13
 */
class Profile extends Base
{
    /**
     * 设置
     */
    public function getSetting()
    {
        $authAdmin = $this->getAuthAdminInfo();
        
        return $this->view('serverlog::profile.setting', [
            'admin' => $authAdmin,
        ]);
    }
    
    /**
     * 设置提交
     */
    public function postSetting()
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|alpha_dash|min:1',
                'nickname' => 'required',
                'email' => 'required|email|bail',
            ],
            [
                'name.required' => '账号名必填',
                'name.alpha_dash' => '账号名只能包含字母、数字、中划线或下划线',
                'name.min' => '账号名最少1位',
                'nickname.required' => '昵称必填',
                'email.required' => '邮箱必填',
                'email.email' => '邮箱格式错误',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $nickname = $this->request->post('nickname');
        $email = $this->request->post('email');
        $remark = $this->request->post('remark');
        
        // 登陆用户信息
        $authAdmin = $this->getAuthAdminInfo();
        
        $info = AdminModel::query()
            ->where([
                'name' => $name,
            ])
            ->whereNotIn('id', [$authAdmin['id']])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('用户名已经存在');
        }
        
        $info2 = AdminModel::query()
            ->where([
                'email' => $email,
            ])
            ->whereNotIn('id', [$authAdmin['id']])
            ->first();
        if (! empty($info2)) {
            return $this->errorJson('用户邮箱已经存在');
        }
        
        $status = AdminModel::query()
            ->where([
                'id' => $authAdmin['id'],
            ])
            ->update([
                'name' => $name,
                'nickname' => $nickname,
                'email' => $email,
                'remark' => $remark,
            ]);
        if ($status === false) {
            return $this->errorJson('信息更新失败');
        }
        
        return $this->successJson('信息更新成功');
    }
    
    /**
     * 密码
     */
    public function getPassword()
    {
        return $this->view('serverlog::profile.password');
    }
    
    /**
     * 密码提交
     */
    public function postPassword()
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'old_password' => 'required|size:32',
                'new_password' => 'required|size:32',
                'again_password' => 'required|size:32',
            ],
            [
                'old_password.required' => '旧的密码必填',
                'old_password.size' => '旧的密码必须32位',
                'new_password.required' => '新的密码必填',
                'new_password.size' => '新的密码必须32位',
                'again_password.required' => '确认密码必填',
                'again_password.size' => '确认密码必须32位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        // 登陆用户信息
        $authAdmin = $this->getAuthAdminInfo();
        
        $oldPassword = $this->request->post('old_password');
        $newPassword = $this->request->post('new_password');
        $againPassword = $this->request->post('again_password');
        
        if ($newPassword != $againPassword) {
            return $this->errorJson('确认密码错误');
        }
        
        $encryptPassword = make(Password::class)
            ->setSalt($this->config->get('serverlog.passport.salt'))
            ->encrypt($oldPassword, $authAdmin['salt']);
        if ($authAdmin['password'] != $encryptPassword) {
            return $this->errorJson('旧的密码错误');
        }
        
        // 生成密码
        $encryptPassword2 = make(Password::class)
            ->setSalt($this->config->get('serverlog.passport.salt'))
            ->encrypt($newPassword);
        
        $status = AdminModel::query()
            ->where([
                'id' => $authAdmin['id'],
            ])
            ->update([
                'password' => $encryptPassword2['password'],
                'salt' => $encryptPassword2['encrypt'],
            ]);
        if ($status === false) {
            return $this->errorJson('密码更新失败');
        }
        
        return $this->successJson('密码更新成功');
    }
}
