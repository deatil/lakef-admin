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

if (! function_exists('serverlog_secret_id')) {
    /**
     * 生成32位ID
     *
     * @return string 返回16位的字符串
     */
    function serverlog_secret_id() {
        return md5(microtime().mt_rand(10000, 99999));
    }
}

if (! function_exists('serverlog_json_encode')) {
    /**
     * 返回可阅读json
     */
    function serverlog_json_encode(array $data) {
        return json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}
