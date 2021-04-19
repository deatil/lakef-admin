<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Support\Tree;
use App\Admin\Support\Password;
use App\Admin\Model\Role as RoleModel;
use App\Admin\Model\Admin as AdminModel;

/**
 * 管理员
 */
class Admin extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        return $this->view('admin::admin.index');
    }
    
    /**
     * 首页
     */
    public function getIndexData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $name = $this->request->input('name');
        if (! empty($name)) {
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        
        $email = $this->request->input('email');
        if (! empty($email)) {
            $where[] = ['email', 'like', '%'.$email.'%'];
        }
        
        $page = max($page, 1);
        
        if (! $this->getIsSuperAdmin()) {
            try {
                $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            } catch(\Exception $e) {
                return $this->errorJson($e->getMessage());
            }
            
            $list = AdminModel::with('roles')
                ->whereHas('roles', function($query) use($childRoleIds) {
                    $query->whereIn('id', $childRoleIds);
                })
                ->where($where)
                ->orderBy('id', 'ASC')
                ->offset($page - 1)
                ->limit($limit)
                ->get();
                
            $count = AdminModel::with('roles')
                ->whereHas('roles', function($query) use($childRoleIds) {
                    $query->whereIn('id', $childRoleIds);
                })
                ->where($where)
                ->count();
        } else {
            $list = AdminModel::with('roles')
                ->where($where)
                ->offset($page - 1)
                ->limit($limit)
                ->get();
                
            $count = AdminModel::with('roles')
                ->where($where)
                ->count();
        }
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        return $this->view('admin::admin.create');
    }
    
    /**
     * 创建
     */
    public function postCreate()
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
        $status = $this->request->post('status');
        
        if (! empty($status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        $info = AdminModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('账号已经存在');
        }
        
        $info = AdminModel::query()
            ->where([
                'email' => $email,
            ])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('邮箱已经存在');
        }
        
        $admin = AdminModel::create([
            'name' => $name,
            'nickname' => $nickname,
            'email' => $email,
            'remark' => $remark,
            'status' => $status,
        ]);
        if ($admin === false) {
            return $this->errorJson('账号创建失败');
        }
        
        // 添加授权
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            
            if (empty($childRoleIds)) {
                return $this->errorJson('当前账号不能创建新账号');
            }
            
            try {
                $admin->syncRoles([$childRoleIds[0]]);
            } catch(\Exception $e) {
                return $this->errorJson('账号创建授权失败');
            }
        }
        
        return $this->successJson('账号创建成功');
    }
    
    /**
     * 更新
     */
    public function getUpdate()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->error('ID不能为空');
        }
        
        $info = AdminModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('账号信息不存在');
        }
        
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $intersectRoles = AdminModel::getIntersectRoles($childRoleIds, $info->getRoleIds()->toArray());
            
            if (empty($intersectRoles)) {
                return $this->error('你不能修改该账号信息');
            }
        }
        
        return $this->view('admin::admin.update', [
            'info' => $info,
        ]);
    }
    
    /**
     * 更新
     */
    public function postUpdate()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if (! $this->getIsSuperAdmin()) {
            if ($id == $this->getAuthAdminId()) {
                return $this->errorJson('你不能更新自己的账号');
            }
        }
        
        $info = AdminModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('账号信息不存在');
        }
        
        // 账号角色检测
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $intersectRoles = AdminModel::getIntersectRoles($childRoleIds, $info->getRoleIds()->toArray());
            
            if (empty($intersectRoles)) {
                return $this->error('你不能修改该账号信息');
            }
        }
        
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
        $avatar = $this->request->post('avatar');
        $remark = $this->request->post('remark');
        $status = $this->request->post('status');
        if (! empty($status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        $info = AdminModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info) && $info['id'] != $id) {
            return $this->errorJson('账号已经存在');
        }
        
        $info = AdminModel::query()
            ->where([
                'email' => $email,
            ])
            ->first();
        if (! empty($info) && $info['id'] != $id) {
            return $this->errorJson('邮箱已经存在');
        }
        
        $update = AdminModel::where([
            ['id', '=', $id],
        ])->update([
            'name' => $name,
            'nickname' => $nickname,
            'email' => $email,
            'avatar' => $avatar,
            'remark' => $remark,
            'status' => $status,
        ]);
        if ($update === false) {
            return $this->errorJson('账号更新失败');
        }
        
        return $this->successJson('账号更新成功');
    }
    
    /**
     * 删除
     */
    public function postDelete()
    {
        $id = (int) $this->request->post('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if ($id == $this->getAuthAdminId()) {
            return $this->errorJson('你不能删除自己的账号');
        }
        
        $info = AdminModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('账号信息不存在');
        }
        
        // 账号角色检测
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $intersectRoles = AdminModel::getIntersectRoles($childRoleIds, $info->getRoleIds()->toArray());
            
            if (empty($intersectRoles)) {
                return $this->errorJson('你不能删除该账号信息');
            }
        }
        
        $delete = AdminModel::where('id', '=', $id)
            ->delete();
        if ($delete === false) {
            return $this->errorJson('删除失败');
        }
        
        return $this->successJson('删除成功');
    }
    
    /**
     * 管理员更新密码
     */
    public function getPassword()
    {
        $id = (int) $this->request->input('id');
        
        $info = AdminModel::where([
                "id" => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('账号不存在');
        }
        
        // 账号角色检测
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $intersectRoles = AdminModel::getIntersectRoles($childRoleIds, $info->getRoleIds()->toArray());
            
            if (empty($intersectRoles)) {
                return $this->error('你不能更新该账号密码');
            }
        }
        
        return $this->view('admin::admin.password', [
            'info' => $info,
        ]);
    }
    
    /**
     * 管理员更新密码
     */
    public function postPassword()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if ($id == $this->getAuthAdminId()) {
            return $this->errorJson('你不能更新自己的密码');
        }
        
        $info = AdminModel::where([
                "id" => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('账号不存在');
        }
        
        // 账号角色检测
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $intersectRoles = AdminModel::getIntersectRoles($childRoleIds, $info->getRoleIds()->toArray());
            
            if (empty($intersectRoles)) {
                return $this->errorJson('你不能更新该账号密码');
            }
        }
        
        $password = $this->request->post('password');
        if (empty($password)) {
            return $this->errorJson('新密码不能为空');
        }
        
        $password_confirm = $this->request->post('password_confirm');
        if (empty($password_confirm)) {
            return $this->errorJson('确认密码不能为空');
        }
        
        if ($password != $password_confirm) {
            return $this->errorJson('两次密码不一致');
        }
        
        if ($id == $this->getAuthAdminId()) {
            return $this->errorJson('你不能修改自己账号的密码');
        }
        
        // 对密码进行处理
        $encryptPassword = make(Password::class)
            ->setSalt($this->config->get('admin.passport.salt'))
            ->encrypt($password);
        
        $data = [];
        $data['password'] = $encryptPassword['password'];
        $data['salt'] = $encryptPassword['encrypt'];
        
        $status = AdminModel::where([
                'id' => $id,
            ])
            ->update($data);
        if ($status === false) {
            return $this->errorJson('修改密码失败');
        }
        
        return $this->successJson("修改密码成功");
    }
    
    /**
     * 授权
     */
    public function getAccess()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->error('ID不能为空');
        }
        
        $info = AdminModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('账号信息不存在');
        }
        
        $adminRoles = $info->getRoleIds()->toArray();
        
        $list = RoleModel
            ::orderBy('sort', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        
        $tree = make(Tree::class);
        $treeData = $tree->withData($list)
            ->withConfig('parentidKey', 'parent_id')
            ->buildArray(0);
        $roles = $tree->buildFormatList($treeData);
        
        return $this->view('admin::admin.access', [
            'info' => $info,
            'roles' => $roles,
            'admin_roles' => $adminRoles,
        ]);
    }
    
    /**
     * 授权
     */
    public function postAccess()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if (! $this->getIsSuperAdmin()) {
            if ($id == $this->getAuthAdminId()) {
                return $this->errorJson('你不能更新自己的账号授权');
            }
        }
        
        $roleid = $this->request->input('roleid');
        
        $info = AdminModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('账号信息不存在');
        }
        
        $roleids = collect(explode(',', $roleid))
            ->map(function($item) {
                return (int) $item;
            })
            ->values()
            ->toArray();
        
        // 过滤非当前账号子角色组
        if (! $this->getIsSuperAdmin()) {
            $childRoleIds = $this->getAuthAdmin()->getChildRoleIds();
            $roleids = AdminModel::getIntersectRoles($childRoleIds, $roleids);
            
            if (empty($roleids)) {
                return $this->errorJson('账号至少需要一个角色组');
            }
        }
        
        try {
            $info->syncRoles($roleids);
        } catch(\Exception $e) {
            return $this->errorJson('账号授权失败');
        }
        
        return $this->successJson("账号授权成功");
    }

}
