<?php
namespace Funny\Router;

/**
 * Interface CollectionInterface
 * @package Funny\Router
 */
interface CollectionInterface
{
    /**
     * CollectionInterface constructor.
     * @param null $handler
     * @param string $prefix
     */
    public function __construct($handler = null, $prefix = '');

    /**
     * 设置Handler class
     * @param string $handler
     * @return $this
     */
    public function setHandler($handler);

    /**
     * 获取handler class
     * @return string
     */
    public function getHandler();

    /**
     * 设置URL前缀
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix);

    /**
     * 获取URL前缀
     * @return string
     */
    public function getPrefix();

    /**
     * Cet Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     */
    public function get($routePattern, $handle, $optionsMode = 0);

    /**
     * Post Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     */
    public function post($routePattern, $handle, $optionsMode = 0);

    /**
     * Put Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     */
    public function put($routePattern, $handle, $optionsMode = 0);

    /**
     * Delete Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     */
    public function delete($routePattern, $handle, $optionsMode = 0);

    /**
     * Patch Method
     * @param string $routePattern
     * @param callable $handle
     * @param int $optionsMode
     * @return $this
     */
    public function patch($routePattern, $handle, $optionsMode = 0);

    /**
     * Options Method
     * @param string $routePattern
     * @param callable $handle
     * @return $this
     */
    public function options($routePattern, $handle);

    /**
     * 路由前置事件集
     * @param array|string $mixed
     * @param array $events
     * @return $this
     */
    public function before($mixed, $events = []);

    /**
     * 后置事件
     * @param array|string $mixed
     * @param array $events
     * @return $this
     * @throws CollectionException
     */
    public function after($mixed, $events = []);

    /**
     * 定义路由组
     * @param string $namespace
     * @param string $prefix
     * @param callable $callback
     */
    public function group($namespace, $prefix, $callback);

    /**
     * 获取当前对象上面的所有路由规则
     * @return array
     */
    public function getUrls();
}
