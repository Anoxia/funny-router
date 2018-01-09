<?php
namespace Funny\Router;

/**
 * Class OptionsAbstract
 * @package Funny\Router
 */
abstract class OptionsAbstract
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
    abstract public static function read();

    /**
     * 可创建options
     */
    abstract public static function create();

    /**
     * 可编辑options
     */
    abstract public static function edit();

    /**
     * 开放所有方法
     */
    abstract public static function unlimited();

    /**
     * 生成options回调
     * @param int $type
     * @return array
     * @throws OptionsException
     */
    public function getMethodHandle($type)
    {
        switch ($type) {
            case self::NONE:
                return [];
                break;

            case self::READ:
                return [$this, 'read'];
                break;

            case self::CREATE:
                return [$this, 'create'];
                break;

            case self::EDIT:
                return [$this, 'edit'];
                break;

            case self::UNLIMITED:
                return [$this, 'unlimited'];
                break;

            default:
                throw new OptionsException("options: {$type}，超出可用方法范围", 500);
        }
    }
}