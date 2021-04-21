<?php

declare(strict_types=1);

namespace Lakef\Admin\Controller;

use Psr\SimpleCache\CacheInterface;
use Hyperf\DbConnection\Db;

use Lakef\Admin\Support\Tree;
use Lakef\Admin\Model\Role as RoleModel;
use Lakef\Admin\Model\Admin as AdminModel;
use Lakef\Admin\Model\Permission as PermissionModel;
use Lakef\Admin\Model\Attachment as AttachmentModel;

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
        $authAdmin = $this->getAuthAdminInfo();
        
        return $this->view('admin::index.index', [
            'admin' => $authAdmin,
        ]);
    }
    
    /**
     * 首页
     */
    public function getDashboard()
    {
        $adminCount = AdminModel::query()->count();
        $roleCount = RoleModel::query()->count();
        $permissionCount = PermissionModel::query()->count();
        $attachmentCount = AttachmentModel::query()->count();
        
        $sysInfo['ip'] = $this->request->server('remote_addr'); // 服务器IP
        $sysInfo['host'] = parse_url(request()->url())['host'];
        $sysInfo['php_uname'] = php_uname();
        $sysInfo['phpv'] = phpversion(); // php版本
        $sysInfo['time'] = date("Y年n月j日 H:i:s", $this->request->server('master_time')); //服务器时间
        $mysqlinfo = Db::select("SELECT VERSION() as version");
        $sysInfo['mysql_version'] = $mysqlinfo[0]->version;
        $sysInfo['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown'; //文件上传限制
        if (function_exists("gd_info")) {
            // GD库版本
            $gd = gd_info();
            $sysInfo['gdinfo'] = $gd['GD Version'];
        } else {
            $sysInfo['gdinfo'] = "未知";
        }

        return $this->view('admin::index.dashboard', [
            'counts' => [
                'admin' => $adminCount,
                'role' => $roleCount,
                'permission' => $permissionCount,
                'attachment' => $attachmentCount,
            ],
            'sys_info' => $sysInfo,
            'system' => [
                'title' => config('admin.system.title'),
                'version' => config('admin.system.version'),
            ],
        ]);
    }
    
    /**
     * 菜单
     */
    public function getMenu()
    {
        $info = $this->getAuthAdminInfo();
        
        if ($this->getIsSuperAdmin()) {
            // 所有权限
            $permissionMenus = PermissionModel
                ::orderBy('sort', 'ASC')
                ->orderBy('id', 'ASC')
                ->select([
                    'id', 'parent_id', 
                    'display_name', 'url', 'target', 'icon', 
                    'is_menu', 'is_click'
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
                if ($data['is_menu'] != 1) {
                    return [];
                }
                
                if ($data['is_click'] != 1) {
                    $data['url'] = '';
                }
                
                return [
                    'id' => $data['id'],
                    'parentid' => $data['parent_id'],
                    'title' => $data['display_name'],
                    'icon' => 'fa ' . $data['icon'],
                    'href' => $data['url'],
                    'target' => $data['target'],
                ];
            })
            ->filter(function($item) {
                return !empty($item);
            })
            ->toArray();
        
        $tree = make(Tree::class);
        $permissionMenus = $tree->withData($permissionMenus)
            ->withConfig('buildChildKey', 'child')
            ->buildArray(0);
        
        $menus = [
            'logoInfo' => [
                'title' => config('admin.system.title'),
                'image' => config('admin.system.logo'),
                'href' => 'javascript:;',
            ],
            'homeInfo' => [
                'title' => '控制台',
                'href' => '/admin/index/dashboard',
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
