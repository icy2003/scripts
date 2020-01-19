<?php
/**
 * Class Scripts
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) 2019, icy2003
 */
namespace icy2003\scripts;

use icy2003\php\I;

/**
 * Scripts
 */
class Scripts
{
    /**
     * 加载一些配置
     *
     * @return void
     */
    public static function load()
    {
        I::setAlias('@icy2003/scripts', __DIR__);
        I::setAlias('@icy2003/scripts_runtime', __DIR__ . '/../runtime');
    }
}
