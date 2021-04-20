<?php

declare (strict_types = 1);

namespace App\Serverlog\Traits;

use App\Serverlog\Support\Sign;
use App\Serverlog\Model\App as AppModel;

/**
 * Api检测
 *
 * create 2021-4-20
 * author deatil
 */
trait ApiCheck
{
    /**
     * 检测签名
     */
    protected function checkSign()
    {
        $appId = $this->request->input('app_id');
        if (empty($appId)) {
            return $this->errorJson("app_id错误", 99);
        }
        
        $app = AppModel
            ::where([
                'app_id' => $appId,
            ])
            ->first();
        if (empty($app) || $app['status'] != 1) {
            return $this->errorJson("授权错误", 97);
        }
        
        $nonceStr = $this->request->input('nonce_str');
        if (empty($nonceStr)) {
            return $this->errorJson("nonce_str错误", 99);
        }
        if (strlen($nonceStr) != 16) {
            return $this->errorJson("nonce_str格式错误", 99);
        }

        $timestamp = $this->request->input('timestamp');
        if (empty($timestamp)) {
            return $this->errorJson("时间戳错误", 99);
        }
        if (strlen($timestamp) != 10) {
            return $this->errorJson("时间戳格式错误", 99);
        }
        if (time() - $timestamp > (60 * 30)) {
            return $this->errorJson("时间错误，请确认你的时间为正确的北京时间", 99);
        }
        
        $sign = $this->request->input('sign');
        if (empty($sign)) {
            return $this->errorJson("签名错误", 99);
        }
        
        $checkSign = make(Sign::class);
        
        $checkSignData = $this->request->all();
        $checkSignKey = $app['app_secret'];
        $checkSignString = $checkSign->makeSign($checkSignData, $checkSignKey);

        if ($checkSignString != $sign) {
            return $this->errorJson("授权验证失败", 99);
        }
    }
}
