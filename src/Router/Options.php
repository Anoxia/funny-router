<?php
namespace Funny\Router;

/**
 * Class Options
 * @package Funny\Router
 */
class Options extends OptionsAbstract
{
    /**
     * 只读options
     */
    public static function read()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * 可创建options
     */
    public static function create()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: PUT, POST, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * 可编辑options
     */
    public static function edit()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, PUT, PATCH, DELETE, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * 开放所有方法
     */
    public static function unlimited()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD');
        header('Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization');
        header('Access-Control-Max-Age: 86400');
    }
}