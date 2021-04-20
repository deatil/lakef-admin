<?php

declare (strict_types = 1);

namespace App\Admin\Traits;

/**
 * 视图
 *
 * create: 2021-4-18
 * author: deatil
 */
trait View
{
    /**
     * 数据
     *
     * @var array
     */
    protected $data = [];
    
    /**
     * 添加数据
     */
    protected function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->data = array_merge($this->data, $name);
        } else {
            $this->data[$name] = $value;
        }
        
        return $this;
    }
    
    /**
     * 视图
     */
    protected function view(string $path, array $data = [])
    {
        $data = array_merge($this->data, $data);
        
        return $this->view->render($path, $data);
    }
    
    /**
     * 成功
     */
    protected function success(string $message)
    {
        if ($this->request->isMethod('get')) {
            return $this->view('admin::view.success', [
                'message' => $message,
            ]);
        } else {
            return $this->successJson($message);
        }
    }
    
    /**
     * 失败
     */
    protected function error(string $message)
    {
        if ($this->request->isMethod('get')) {
            return $this->view('admin::view.error', [
                'message' => $message,
            ]);
        } else {
            return $this->errorJson($message);
        }
    }
    
}
