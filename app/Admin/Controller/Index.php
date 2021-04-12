<?php

declare(strict_types=1);

namespace App\Admin\Controller;

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
                            'title' => '主页模板',
                            'icon' => 'fa fa-home',
                            'href' => '',
                            'target' => '_self',
                            'child' => [
                                [
                                    'title' => '主页一',
                                    'icon' => 'fa fa-tachometer',
                                    'href' => '/admin/index/main',
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
    public function postClear()
    {
        return $this->successJson('缓存清理成功');
    }
}
