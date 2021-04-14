<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Support\Password;
use App\Admin\Model\User as UserModel;

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
        $authAdmin = $this->request->getAttribute('authAdmin');
        
        return $this->view('admin.profile.setting', [
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
                'name' => 'required|min:5',
                'email' => 'required|email|bail',
            ],
            [
                'name.required' => '用户名必填',
                'name.min' => '用户名最少6位',
                'email.required' => '邮箱必填',
                'email.email' => '邮箱格式错误',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $remark = $this->request->post('remark');
        
        // 登陆用户信息
        $authAdmin = $this->request->getAttribute('authAdmin');
        
        $info = UserModel::query()
            ->where([
                'name' => $name,
            ])
            ->whereNotIn('id', [$authAdmin['id']])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('用户名已经存在');
        }
        
        $info2 = UserModel::query()
            ->where([
                'email' => $email,
            ])
            ->whereNotIn('id', [$authAdmin['id']])
            ->first();
        if (! empty($info2)) {
            return $this->errorJson('用户邮箱已经存在');
        }
        
        $status = UserModel::query()
            ->where([
                'id' => $authAdmin['id'],
            ])
            ->update([
                'name' => $name,
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
        return $this->view('admin.profile.password');
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
        $authAdmin = $this->request->getAttribute('authAdmin');
        
        $oldPassword = $this->request->post('old_password');
        $newPassword = $this->request->post('new_password');
        $againPassword = $this->request->post('again_password');
        
        if ($newPassword != $againPassword) {
            return $this->errorJson('确认密码错误');
        }
        
        $encryptPassword = make(Password::class)
            ->setSalt($this->config->get('serverlog.passport_salt'))
            ->encrypt($oldPassword, $authAdmin['salt']);
        if ($authAdmin['password'] != $encryptPassword) {
            return $this->errorJson('旧的密码错误');
        }
        
        // 生成密码
        $encryptPassword2 = make(Password::class)
            ->setSalt($this->config->get('serverlog.passport_salt'))
            ->encrypt($newPassword);
        
        $status = UserModel::query()
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
