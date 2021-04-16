<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Support\Tree;
use App\Admin\Model\Role as RoleModel;
use App\Admin\Model\Permission as PermissionModel;

/**
 * 角色
 */
class Role extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        return $this->view('serverlog::role.index');
    }
    
    /**
     * 首页
     */
    public function getData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $name = $this->request->input('name');
        if (! empty($name)) {
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        
        $guardName = $this->request->input('guard_name');
        if (! empty($guardName)) {
            $where[] = ['guard_name', 'like', '%'.$guardName.'%'];
        }
        
        $page = max($page, 1);
        $list = RoleModel::where($where)
            ->offset($page - 1)
            ->limit($limit)
            ->get();
            
        $count = RoleModel::where($where)
            ->count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 结构
     */
    public function getTree()
    {
        return $this->view('serverlog::role.tree');
    }
    
    /**
     * 结构数据
     */
    public function getTreeData()
    {
        $list = RoleModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $count = RoleModel::count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        $parentid = (int) $this->request->input('parentid');
        
        $list = RoleModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $tree = make(Tree::class);
        $treeData = $tree->withData($list)
            ->withConfig('parentidKey', 'parent_id')
            ->buildArray(0);
        $parents = $tree->buildFormatList($treeData);
        
        return $this->view('serverlog::role.create', [
            'parentid' => $parentid,
            'parents' => $parents,
        ]);
    }
    
    /**
     * 创建
     */
    public function postCreate()
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|min:1',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '角色名必填',
                'name.min' => '角色名最少1位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $parentId = (int) $this->request->post('parent_id');
        $name = $this->request->post('name');
        $guardName = $this->request->post('guard_name');
        $description = $this->request->post('description');
        
        $info = RoleModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('角色名已经存在');
        }
        
        $role = RoleModel::create([
            'parent_id' => $parentId,
            'name' => $name,
            'guard_name' => $guardName,
            'description' => $description,
        ]);
        if ($role === false) {
            return $this->errorJson('角色创建失败');
        }
        
        return $this->successJson('角色创建成功');
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
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('角色信息不存在');
        }
        
        // 父级
        $list = RoleModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $tree = make(Tree::class);
        $childsId = $tree->getListChildsId($list, $info['id']);
        $childsId[] = $info['id'];
        
        $parentList = [];
        foreach ($list as $r) {
            if (in_array($r['id'], $childsId)) {
                continue;
            }
            
            $parentList[] = $r;
        }
        
        $parentTree = $tree->withData($parentList)
            ->withConfig('parentidKey', 'parent_id')
            ->buildArray(0);
        $parents = $tree->buildFormatList($parentTree);
        
        return $this->view('serverlog::role.update', [
            'parentid' => $info['parent_id'],
            'parents' => $parents,
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
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('角色信息不存在');
        }
        
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|min:1',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '角色名必填',
                'name.min' => '角色名最少1位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $parentId = (int) $this->request->post('parent_id');
        $name = $this->request->post('name');
        $guardName = $this->request->post('guard_name');
        $description = $this->request->post('description');
        
        $info = RoleModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info) && $info['id'] != $id) {
            return $this->errorJson('角色名已经存在');
        }
        
        $update = RoleModel::where([
            ['id', '=', $id],
        ])->update([
            'parent_id' => $parentId,
            'name' => $name,
            'guard_name' => $guardName,
            'description' => $description,
        ]);
        if ($update === false) {
            return $this->errorJson('更新失败');
        }
        
        return $this->successJson('更新成功');
    }
    
    /**
     * 删除
     */
    public function postDelete()
    {
        $id = $this->request->post('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if (! is_array($id)) {
            $id = [$id];
        }
        
        $delete = RoleModel::whereIn('id', $id)
            ->delete();
        if ($delete === false) {
            return $this->errorJson('删除失败');
        }
        
        return $this->successJson('删除成功');
    }

    /**
     * 菜单排序
     */
    public function postSort()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            $this->errorJson('参数不能为空');
        }
        
        $sort = $this->request->post('value', 1000);
        
        $update = RoleModel::where([
            'id' => $id,
        ])->update([
            'sort' => $sort,
        ]);
        if ($update === false) {
            return $this->errorJson("排序失败");
        }
        
        return $this->successJson('排序成功');
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
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('角色信息不存在');
        }
        
        // 权限
        $permissionList = PermissionModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $permissions = [];
        if (!empty($permissionList)) {
            foreach ($permissionList as $rs) {
                $data = [
                    'id' => $rs['id'],
                    'parentid' => $rs['parent_id'],
                    'title' => (empty($rs['method']) ? $rs['display_name'] : ($rs['display_name'] . '[' . strtoupper($rs['method']) . ']')),
                    // 'checked' => in_array($rs['id'], $rules) ? true : false,
                    'field' => 'roleid',
                    'spread' => false,
                ];
                $permissions[] = $data;
            }
        }
        
        $permissions = make(Tree::class)
            ->withConfig('buildChildKey', 'children')
            ->withData($permissions)
            ->buildArray(0);
        
        try {
            $accessPermissions = $info->permissions
                ->pluck('id')
                ->toArray();
        } catch(\Exception $e) {
            return $this->error('获取角色授权信息失败');
        }

        return $this->view('serverlog::role.access', [
            'info' => $info,
            'permissions' => $permissions,
            'access_permissions' => $accessPermissions,
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
        
        $permissions = $this->request->input('permissions');
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('角色信息不存在');
        }
        
        $permissions = collect(explode(',', $permissions))
            ->map(function($item) {
                return (int) $item;
            })
            ->values()
            ->toArray();
        
        try {
            $info->syncPermissions($permissions);
        } catch(\Exception $e) {
            return $this->errorJson('角色授权失败');
        }
        
        return $this->successJson("角色授权成功");
    }
    
}
