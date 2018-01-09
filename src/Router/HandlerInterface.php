<?php
namespace Funny\Router;

/**
 * Interface HandlerInterface
 * @package Funny\Router
 */
interface HandlerInterface
{
    /**
     * HandlerInterface constructor.
     * @param callable|array $handle
     * @param array $events
     * @param int $options
     * @param array $matchVars
     */
    public function __construct($handle, $events, $options, $matchVars);

    /**
     * 执行命中目标回调函数(方法)
     * @param array $params
     * @return mixed
     */
    public function dispatch($params = []);

    /**
     * 获取回调(方法)执行返回值
     * @return mixed
     */
    public function getReturnValue();
}