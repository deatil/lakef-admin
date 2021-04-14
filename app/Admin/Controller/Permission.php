<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Donjan\Permission\Models\Permission as PermissionModel;
use App\Admin\Support\Tree;

/**
 * 权限
 */
class Permission extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        return $this->view('admin.permission.index');
    }
    
    /**
     * 首页数据
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
        
        $displayName = $this->request->input('display_name');
        if (! empty($guardName)) {
            $where[] = ['display_name', 'like', '%'.$displayName.'%'];
        }
        
        $guardName = $this->request->input('guard_name');
        if (! empty($guardName)) {
            $where[] = ['guard_name', 'like', '%'.$guardName.'%'];
        }
        
        $page = max($page, 1);
        $list = PermissionModel::where($where)
            ->offset($page - 1)
            ->limit($limit)
            ->get();
            
        $count = PermissionModel::where($where)
            ->count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        return $this->view('admin.permission.create');
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
                'display_name' => 'required|min:2',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '权限必填',
                'name.min' => '权限最少1位',
                'display_name.required' => '权限名必填',
                'display_name.min' => '权限名最少2位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $parentId = (int) $this->request->post('parent_id');
        $name = $this->request->post('name');
        $displayName = $this->request->post('display_name');
        $guardName = $this->request->post('guard_name');
        $url = $this->request->post('url');
        $icon = $this->request->post('icon');
        $sort = $this->request->post('sort', 1000);
        
        $permission = PermissionModel::create([
            'parent_id' => $parentId,
            'name' => $name,
            'display_name' => $displayName,
            'guard_name' => $guardName,
            'url' => $url,
            'icon' => $icon,
            'sort' => $sort,
        ]);
        if ($permission === false) {
            return $this->errorJson('权限创建失败');
        }
        
        return $this->successJson('权限创建成功');
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
        
        $info = PermissionModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('权限信息不存在');
        }
        
        $permissionList = PermissionModel::order([
            'sort' => 'DESC', 
            'id' => 'DESC',
        ])->get()->toArray();
        
        $tree = make(Tree::class);
        $childsId = $tree->getListChildsId($permissionList, $info['id']);
        $childsId[] = $info['id'];
        
        $permissionParentList = [];
        foreach ($permissionList as $r) {
            if (in_array($r['id'], $childsId)) {
                continue;
            }
            
            $permissionParentList[] = $r;
        }
        
        $permissionTree = $Tree->withData($permissionParentList)->buildArray(0);
        $permissions = $Tree->buildFormatList($permissionTree, 'title');
        
        return $this->view('admin.permission.update', [
            'parentid' => $info['parentid'],
            'permissions' => $permissions,
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
        
        $info = PermissionModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('权限信息不存在');
        }
        
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|min:1',
                'display_name' => 'required|min:2',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '权限必填',
                'name.min' => '权限最少1位',
                'display_name.required' => '权限名必填',
                'display_name.min' => '权限名最少2位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $parentId = (int) $this->request->post('parent_id');
        $name = $this->request->post('name');
        $displayName = $this->request->post('display_name');
        $guardName = $this->request->post('guard_name');
        $url = $this->request->post('url');
        $icon = $this->request->post('icon');
        $sort = $this->request->post('sort', 1000);
        
        $update = PermissionModel::where([
            ['id', '=', $id],
        ])->update([
            'parent_id' => $parentId,
            'name' => $name,
            'display_name' => $displayName,
            'guard_name' => $guardName,
            'url' => $url,
            'icon' => $icon,
            'sort' => $sort,
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
        
        $info = PermissionModel::where(["id" => $id])
            ->first();
        if (empty($info)) {
            return $this->errorJson('权限信息不存在');
        }
        
        $parentCount = PermissionModel::where([ 
                ["parent_id", '=', $id],
            ])
            ->count();
        if ($parentCount > 0) {
            return $this->errorJson("含有子权限，无法删除");
        }
        
        $delete = PermissionModel::where('id', $id)
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
        
        $sort = $this->request->post('value', 100);
        
        $rs = PermissionModel::where([
            'id' => $id,
        ])->update([
            'sort' => $sort,
        ]);
        if ($rs === false) {
            return $this->errorJson("排序失败");
        }
        
        return $this->successJson('排序成功');
    }
}
