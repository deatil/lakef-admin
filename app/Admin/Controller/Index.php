<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Psr\SimpleCache\CacheInterface;

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
        return $this->view('admin.index.index', [
            'admin' => $authAdmin,
        ]);
    }
    
    /**
     * 首页
     */
    public function getMain()
    {
        return $this->view('admin.index.main');
    }
    
    /**
     * 菜单
     */
    public function getMenu()
    {
        $menus = [
            'homeInfo' => [
                'title' => '首页',
                'href' => '/admin/index/main',
            ],
            'logoInfo' => [
                'title' => 'ServerLog',
                'image' => 'images/logo.png',
                'href' => '/admin/index/main',
            ],
            'menuInfo' => [
                [
                    'title' => '常规管理',
                    'icon' => 'fa fa-address-book',
                    'href' => '',
                    'target' => '_self',
                    'child' => [
                        [
                            'title' => '权限管理',
                            'icon' => 'fa fa-cogs',
                            'href' => '',
                            'target' => '_self',
                            'child' => [
                                [
                                    'title' => '角色',
                                    'icon' => 'fa fa-user',
                                    'href' => '/admin/role/index',
                                    'target' => '_self',
                                ],
                                [
                                    'title' => '权限',
                                    'icon' => 'fa fa-list',
                                    'href' => '/admin/permission/index',
                                    'target' => '_self',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
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
