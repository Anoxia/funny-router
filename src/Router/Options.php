<?php
namespace Funny\Router;

/**
 * Class Options
 * @package Funny\Router
 */
class Options
{
    /**
     * OPTIONS禁用
     */
    const NONE      = 0;

    /**
     * Options可读
     */
    const READ      = 1;

    /**
     * Options可写
     */
    const CREATE    = 2;

    /**
     * Options可编辑
     */
    const EDIT      = 3;

    /**
     * 开放Options所有权限
     */
    const UNLIMITED = 4;

    /**
     * 只读options
     */
    public static function read()
    {
//        $response = $this->response;
//        $response->setHeader('Access-Control-Allow-Origin', $this->request->getHeader('Origin'));
//        $response->setHeader('Access-Control-Allow-Credentials', 'true');
//        $response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS, HEAD');
//        $response->setHeader('Access-Control-Allow-Headers', "origin, x-requested-with, content-type, authorization");
//        $response->setHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * 可创建options
     */
    public static function create()
    {
//        $response = $this->response;
//        $response->setHeader('Access-Control-Allow-Origin', $this->request->getHeader('Origin'));
//        $response->setHeader('Access-Control-Allow-Credentials', 'true');
//        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, HEAD');
//        $response->setHeader('Access-Control-Allow-Headers', "origin, x-requested-with, content-type, authorization");
//        $response->setHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * 可编辑options
     */
    public static function edit()
    {
//        $response = $this->response;
//        $response->setHeader('Access-Control-Allow-Origin', $this->request->getHeader('Origin'));
//        $response->setHeader('Access-Control-Allow-Credentials', 'true');
//        $response->setHeader('Access-Control-Allow-Methods', 'GET, PUT, PATCH, DELETE, OPTIONS, HEAD');
//        $response->setHeader('Access-Control-Allow-Headers', "origin, x-requested-with, content-type, authorization");
//        $response->setHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * 开放所有权限
     */
    public static function unlimited()
    {

    }

    public static function none()
    {

    }
}