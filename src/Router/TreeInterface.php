<?php
namespace Funny\Router;

/**
 * Interface TreeInterface
 * @package Funny\Router
 */
interface TreeInterface
{
    /**
     * 修改URL分隔符
     * @param string $separator
     */
    public function setSeparator($separator);

    /**
     * 获取URL分隔符
     * @return string
     */
    public function getSeparator();

    /**
     * 将路由解析至map数据节点汇总
     * @param string $method
     * @param string $path
     * @param callable|array $callback
     * @param array $events
     * @return mixed|void
     */
    public function add($method, $path, $callback, $events);

    /**
     * 执行路由匹配
     * @param string $method
     * @param string $path
     * @param bool $dynamic
     * @return Handler|false
     */
    public function match($method, $path, $dynamic = true);

    /**
     * 返回路由树
     * @return array
     */
    public function export();
}