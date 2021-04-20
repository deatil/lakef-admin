<?php

if (! function_exists('serverlog_md5')) {
    /**
     * 返回16位md5值
     *
     * @param string $str 字符串
     * @return string $str 返回16位的字符串
     */
    function serverlog_md5($str) {
        return substr(md5($str), 8, 16);
    }
}

if (! function_exists('serverlog_rand_id')) {
    /**
     * 生成16位ID
     *
     * @return string 返回16位的字符串
     */
    function serverlog_rand_id() {
        return serverlog_md5(microtime().mt_rand(10000, 99999));
    }
}

