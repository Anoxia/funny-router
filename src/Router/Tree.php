<?php
namespace Funny\Router;

/**
 * 路由树操作类Tree
 * @package Funny\Router
 */
class Tree implements TreeInterface
{
    /**
     * URL分解符
     */
    private $separator = '/';

    /**
     * @var array
     */
    private $map = [];

    /**
     * 默认空节点
     * @var array
     */
    private $defaultNode = [];

    /**
     * 正则节点别名
     * @var string
     */
    private $regexNodeAlias  = '..';

    /**
     * 节点附加数据别名
     * @var string
     */
    private $attachNodeAlias = '::';

    /**
     * 修改URL分隔符
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * 获取URL分隔符
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * 将URL解析至路由数组
     * @param array $node
     * @param array $tokens
     * @param callable $callback
     * @param array $events
     * @return mixed
     * @throws TreeException
     */
    private function parse(&$node, $tokens, $callback, $events)
    {
        // 取一个节点名称
        $token = array_shift($tokens);
        if (empty($token)) {
            return false;
        }

        // 节点叶子
        $leaf  = $this->defaultNode;

        // 到达路径尾，添加叶子附属信息
        if (empty($tokens)) {
            $leaf['events']  = $events;
            $leaf['handle']  = $callback;
        }

        // 当前路由空节点霉是否为正则节点
        if ($token[0] == '{') {

            // 匹配'{'开头，'}'结尾表达式
            preg_match('/^(?:\{)([^{}].*)(?:\})/', $token, $match);
            // 正则节点格式匹配失败
            if (empty($match) || empty($match[1])) {
                throw new TreeException("正则节点 '{$token}' 无法解析。节点格式：'{正则表达式}'。", 500);
            }

            // 该节点已经存在正则节点
            if (isset($node[$this->regexNodeAlias])) {
                throw new TreeException("相同前缀的路径中只能定义一个正则匹配节点", 500);
            }

            // 创建正则节点
            $token = $this->regexNodeAlias;
            // 保存该节点正则表达式
            $leaf['regex'] = $match[1];
        }

        // 节点已经存在，
        if (isset($node[$token])) {
            // 节点数据有变化，更新节点叶子
            if (count($leaf)) {
                $node[$token][$this->attachNodeAlias] = $leaf;
            }
        }

        // 节点不存在(节点为正则节点或到达路径尾)，创建新节点节点叶子
        elseif (count($leaf)) {
            $node[$token] = [$this->attachNodeAlias => $leaf];
        }

        // 节点不存在，创建空节点
        else {
            $node[$token] = $leaf;
        }

        // 创建子节点
        if ($token) {
            return $this->parse($node[$token], $tokens, $callback, $events);
        }

        return true;
    }

    /**
     * 将路由解析至map数据节点汇总
     * @param string $method
     * @param string $path
     * @param callable|array $callback
     * @param array $events
     * @return mixed|void
     * @throws TreeException
     */
    public function add($method, $path, $callback, $events = [])
    {
        // HTTP方法作为根节点
        if (!isset($this->map[$method])) {
            $this->map[$method] = $this->defaultNode;
        }

        // 解析URL路径
        $path   = trim($path, $this->separator);
        if (empty($path)) {
            $tokens = [];
        } else {
            $tokens = explode($this->separator, $path);
        }

        // 处理根目录符号
        array_unshift($tokens, $this->separator);

        // 执行URL解析
        $this->parse($this->map[$method], $tokens, $callback, $events);
    }

    /**
     * 执行URL查找
     * @param array $node
     * @param array $tokens
     * @param array $matchVars
     * @return bool|array
     */
    private function resolve(&$node, $tokens, $matchVars = [])
    {
        // 取出第一个节点
        $token = array_shift($tokens);

        // 路径遍历结束
        if (empty($token)) {

            // 空节点
            if (!isset($node[$this->attachNodeAlias])) {
                return false;
            }

            // 取出节点叶子附属信息
            $attachNode = $node[$this->attachNodeAlias];

            // 检查是否为可执行叶子节点
            if (!isset($attachNode['handle'])) {
                return false;
            }

            // 携带匹配的变量
            $attachNode['matchVars'] = $matchVars;

            return $attachNode;
        } else {

            // 存在子节点，往下匹配
            if (isset($node[$token])) {
                return $this->resolve($node[$token], $tokens, $matchVars);
            }
            // 存在正则节点
            elseif (isset($node[$this->regexNodeAlias])) {

                // 节点叶子附属信息
                $attachNode = $node[$this->regexNodeAlias][$this->attachNodeAlias];
                // 拼接正则字符串
                $pattern    = "/^{$attachNode['regex']}/";
                // 执行路由匹配
                preg_match($pattern, $token, $match);
                // 正则不匹配
                if (empty($match)) {
                    return false;
                }

                // 保存配对的节点变量
                $matchVars[] = $match[0];

                // 继续查找子节点
                return $this->resolve($node[$this->regexNodeAlias], $tokens, $matchVars);
            }
        }

        return false;
    }

    /**
     * 发起URL匹配
     * @param string $method
     * @param string $path
     * @param bool $dynamic
     * @return array|false
     */
    public function match($method, $path, $dynamic = true)
    {
        if (!isset($this->map[$method])) {
            return false;
        }

        // 解析URL路径
        $tokens = explode($this->separator, trim($path, $this->separator));

        // 处理根目录符号
        array_unshift($tokens, $this->separator);

        // 执行路由查找
        $attachNode = $this->resolve($this->map[$method], $tokens);

        return $attachNode;
    }

    public function export()
    {
        // todo: 处理闭包函数
        return $this->map;
    }
}