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
        return $this->view('serverlog::permission.index');
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
     * 结构
     */
    public function getMenu()
    {
        return $this->view('serverlog::permission.menu');
    }
    
    /**
     * 结构数据
     */
    public function getMenuData()
    {
        $list = PermissionModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $count = PermissionModel::count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        $parentid = (int) $this->request->input('parentid');
        
        $permissionList = PermissionModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $tree = make(Tree::class);
        $permissionTree = $tree->withData($permissionList)
            ->withConfig('parentidKey', 'parent_id')
            ->buildArray(0);
        $permissions = $tree->buildFormatList($permissionTree);
        
        return $this->view('serverlog::permission.create', [
            'parentid' => $parentid,
            'permissions' => $permissions,
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
                'url' => 'required',
                'method' => 'required',
                'display_name' => 'required|min:2',
                'guard_name' => 'required|min:2',
            ],
            [
                'url.required' => '请求链接必填',
                'method.required' => '请求方式必填',
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
        $displayName = $this->request->post('display_name');
        $guardName = $this->request->post('guard_name');
        $url = $this->request->post('url');
        $method = $this->request->post('method');
        $icon = $this->request->post('icon');
        $sort = $this->request->post('sort', 1000);
        
        $is_menu = $this->request->post('is_menu');
        if (! empty($is_menu)) {
            $is_menu = 1;
        } else {
            $is_menu = 0;
        }
        
        $permission = PermissionModel::create([
            'parent_id' => $parentId,
            'name' => $method.':'.$url,
            'display_name' => $displayName,
            'guard_name' => $guardName,
            'url' => $url,
            'icon' => $icon,
            'is_menu' => $is_menu,
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
        
        $permissionList = PermissionModel
            ::orderBy('sort', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
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
        
        $permissionTree = $tree->withData($permissionParentList)
            ->withConfig('parentidKey', 'parent_id')
            ->buildArray(0);
        $permissions = $tree->buildFormatList($permissionTree);
        
        // 格式化出来请求链接
        [$method, $url] = explode(':', $info['name'], 2);
        $info['method'] = $method;
        $info['url'] = $url;
        
        return $this->view('serverlog::permission.update', [
            'parentid' => $info['parent_id'],
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
                'url' => 'required',
                'method' => 'required',
                'display_name' => 'required|min:2',
                'guard_name' => 'required|min:2',
            ],
            [
                'url.required' => '请求链接必填',
                'method.required' => '请求方式必填',
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
        $displayName = $this->request->post('display_name');
        $guardName = $this->request->post('guard_name');
        $url = $this->request->post('url');
        $method = $this->request->post('method');
        $icon = $this->request->post('icon');
        $sort = $this->request->post('sort', 1000);
        
        $is_menu = $this->request->post('is_menu');
        if (! empty($is_menu)) {
            $is_menu = 1;
        } else {
            $is_menu = 0;
        }
        
        $update = PermissionModel::where([
            ['id', '=', $id],
        ])->update([
            'parent_id' => $parentId,
            'name' => $method.':'.$url,
            'display_name' => $displayName,
            'guard_name' => $guardName,
            'url' => $url,
            'icon' => $icon,
            'is_menu' => $is_menu,
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
     * 菜单设置是否显示
     */
    public function postSetMenu()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            $this->errorJson('参数不能为空');
        }
        
        $status = (int) $this->request->post('status', 0);
        
        $update = PermissionModel::where([
            'id' => $id,
        ])->update([
            'is_menu' => $status,
        ]);
        if ($update === false) {
            return $this->errorJson("设置失败");
        }
        
        return $this->successJson('设置成功');
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
        
        $update = PermissionModel::where([
            'id' => $id,
        ])->update([
            'sort' => $sort,
        ]);
        if ($update === false) {
            return $this->errorJson("排序失败");
        }
        
        return $this->successJson('排序成功');
    }
}
