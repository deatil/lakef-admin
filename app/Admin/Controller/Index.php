<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Psr\SimpleCache\CacheInterface;
use App\Admin\Support\Tree;
use App\Admin\Model\Permission as PermissionModel;

/**
 * 首页
 */
class Index extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        $authAdmin = $this->request->getAttribute('authAdmin');
        return $this->view('serverlog::index.index', [
            'admin' => $authAdmin,
        ]);
    }
    
    /**
     * 首页
     */
    public function getMain()
    {
        return $this->view('serverlog::index.main');
    }
    
    /**
     * 菜单
     */
    public function getMenu()
    {
        $info = $this->getAuthAdmin();
        
        if ($this->getIsSuperAdmin()) {
            // 所有权限
            $permissionMenus = PermissionModel
                ::orderBy('sort', 'DESC')
                ->orderBy('id', 'DESC')
                ->select([
                    'id', 'parent_id', 
                    'display_name', 'url', 'target', 'icon'
                ])
                ->get()
                ->toArray();
        } else {
            // 所有权限
            $permissionMenus = $info
                ->getAllPermissions()
                ->sortByDesc('sort')
                ->toArray();
        }
        
        $permissionMenus = collect($permissionMenus)
            ->map(function($data) {
                return [
                    'id' => $data['id'],
                    'parentid' => $data['parent_id'],
                    'title' => $data['display_name'],
                    'icon' => 'fa ' . $data['icon'],
                    'href' => $data['url'],
                    'target' => $data['target'],
                ];
            })
            ->toArray();
        
        $tree = make(Tree::class);
        $permissionMenus = $tree->withData($permissionMenus)
            ->withConfig('buildChildKey', 'child')
            ->buildArray(0);
        
        $menus = [
            'logoInfo' => [
                'title' => config('serverlog.admin.title'),
                'image' => config('serverlog.admin.logo'),
                'href' => 'javascript:;',
            ],
            'homeInfo' => [
                'title' => '控制台',
                'href' => '/admin/index/main',
            ],
            'menuInfo' => $permissionMenus,
        ];
        
        return $this->response->json($menus);
    }
    
    /**
     * 缓存清理
     */
    public function postClear(CacheInterface $cache)
    {
        $cache->clear();
        
        return $this->successJson('缓存清理成功');
    }
}
