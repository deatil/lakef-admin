<?php

declare (strict_types = 1);

namespace Lakef\Serverlog\Traits;

/**
 * 视图
 *
 * create: 2021-4-18
 * author: deatil
 */
trait Json
{
    /**
     * 表格数据 json
     */
    protected function tableJson($list, $count, $msg = '获取成功')
    {
        return $this->response->json([
            'code' => 0,
            'msg' => $msg,
            'data' => $list,
            'count' => $count,
        ]);
    }
    
    /**
     * json
     */
    protected function json(
        $success = true, 
        $code = 0, 
        $message = "", 
        $data = []
    ) {
        $result = [];
        $result['success'] = $success;
        $result['code'] = $code;
        $message ? $result['message'] = $message : null;
        $data ? $result['data'] = $data : null;
        
        return $this->response->json($result);
    }
    
    /**
     * 返回错误json
     */
    protected function errorJson(
        $message = null, 
        $code = 1, 
        $data = []
    ) {
        return $this->json(false, $code, $message, $data);
    }
    
    /**
     * 返回成功json
     */
    protected function successJson(
        $message = null, 
        $data = [], 
        $code = 0
    ) {
        return $this->json(true, $code, $message, $data);
    }
}
