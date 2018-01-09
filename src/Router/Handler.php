<?php
 namespace Funny\Router;

 /**
  * Class Handler
  * @package Funny\Router
  */
 class Handler implements HandlerInterface
 {
     /**
      * @var callable|array
      */
     private $handle = null;

     /**
      * 跨域头类型
      * @var int
      */
     private $options = 0;

     /**
      * 已匹配的变量
      * @var array
      */
     private $params = [];

     /**
      * 路由前置事件组
      * @var array
      */
     private $beforeEvents = [];

     /**
      * 路由后置事件组
      * @var array
      */
     private $afterEvents = [];

     /**
      * 回调(方法)执行返回值
      * @var mixed
      */
     private $returnValue = null;

     /**
      * Handler constructor.
      * @param callable|array $handle
      * @param array $events
      * @param int $options
      * @param array $matchVars
      */
     public function __construct($handle, $events, $options, $matchVars)
     {
         $this->handle  = $handle;
         $this->options = $options;
         $this->params  = $matchVars;

         if (isset($events['before'])) {
             $this->beforeEvents = $events['before'];
         }

         if (isset($events['after'])) {
             $this->afterEvents  = $events['after'];
         }
     }

     /**
      * 指定URL绑定的函数(方法)
      * @param mixed $params
      * @return mixed|void
      */
     public function dispatch($params = null)
     {
         // 拼接参数
         if (!empty($params)) {
             $this->params[] = $params;
         }

         // 前置事件
         $this->callEvent($this->beforeEvents);

         // 自身事件
         $this->callEvent([$this->handle], true);

         // 后置事件
         $this->callEvent($this->afterEvents);
     }

     /**
      * 调用路由事件
      * @param array $events 事件数组
      * @param bool $returnValue
      */
     private function callEvent($events, $returnValue = false)
     {
         $value = null;

         // 前置事件
         foreach ($events as $event) {
             $value = $this->callback($event, $this->params);

             // 前一次执行结果作为下一次调用参数
             if (!empty($value)) {
                 $this->params[] = $value;
             }
         }

         // 保存返回值
         if ($returnValue) {
             $this->returnValue = $value;
         }
     }

     /**
      * 执行函数(方法)调用
      * @param $callable
      * @param $params
      * @return mixed
      */
     private function callback($callable, $params)
     {
         // 匿名函数
         if ($callable instanceof \Closure) {
             return call_user_func_array($callable, $params);
         }

         // 方法
         if (is_array($callable)) {
             $class     = new $callable[0];
             $method    = $callable[1];

             return call_user_func_array([$class, $method], $params);
         }

         // 函数
         return call_user_func_array($callable, $params);
     }

     /**
      * 获取回调(方法)执行返回值
      * @return mixed
      */
     public function getReturnValue()
     {
         return $this->returnValue;
     }
 }