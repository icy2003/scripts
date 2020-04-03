<?php
/**
 * Class Sorts
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) 2020, icy2003
 */
namespace icy2003\scripts\algorithms;

/**
 * PHP 的排序：
 * ```
 * 1. 冒泡排序
 * 2. 快速排序
 * 3. 选择排序
 * ```
 */
class Sorts
{
    /**
     * 冒泡排序
     *
     * 介绍：它重复地走访过要排序的数列，一次比较两个元素，如果他们的顺序错误就把他们交换过来。因为越小的元素会经由交换慢慢“浮”到数列的顶端，所以被称为“冒泡排序”。
     *
     * @param array $array
     *
     * @return array
     */
    public static function bubble($array)
    {
        $count = count($array);
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if ($array[$j] < $array[$i]) {
                    list($array[$i], $array[$j]) = [$array[$j], $array[$i]];
                }
            }
        }
        return $array;
    }

    /**
     * 快速排序
     *
     * 介绍：通过一趟排序将要排序的数据分割成独立的两部分，其中一部分的所有数据都比另外一部分的所有数据都要小，然后再按此方法对这两部分数据分别进行快速排序，整个排序过程可以递归进行，以此达到整个数据变成有序序列。
     *
     * @param array $array
     *
     * @return array
     */
    public static function quick($array)
    {
        $count = count($array);
        if ($count < 2) {
            return $array;
        }
        $leftArray = $rightArray = [];
        $middle = $array[0];
        for ($i = 1; $i < $count; $i++) {
            if ($array[$i] < $middle) {
                $leftArray[] = $array[$i];
            } else {
                $rightArray[] = $array[$i];
            }
        }
        return array_merge(self::quick($leftArray), [$middle], self::quick($rightArray));
    }

    /**
     * 选择排序
     *
     * 介绍：首先在未排序序列中找到最小（大）元素，存放到排序序列的起始位置，然后，再从剩余未排序元素中继续寻找最小（大）元素，然后放到已排序序列的末尾。以此类推，直到所有元素均排序完毕。
     *
     * @param array $array
     *
     * @return array
     */
    public static function select($array)
    {
        $length = count($array);
        for ($i = 0; $i < $length; $i++) {
            $minIndex = $i;
            for ($j = $i + 1; $j < $length; $j++) {
                if ($array[$j] < $array[$minIndex]) {
                    $minIndex = $j;
                }
            }
            list($array[$i], $array[$minIndex]) = [$array[$minIndex], $array[$i]];
        }
        return $array;
    }

    public static function tests($max = 100, $showResult = false)
    {
        $array = range(1, $max);
        // bubble
        shuffle($array);
        $t0 = microtime(true);
        $bubble = self::bubble($array);
        $t1 = microtime(true);
        echo 'bubble:', $t1 - $t0, PHP_EOL;
        // quick
        shuffle($array);
        $t0 = microtime(true);
        $quick = self::quick($array);
        $t2 = microtime(true);
        echo 'quick:', $t2 - $t0, PHP_EOL;
        // select
        shuffle($array);
        $t0 = microtime(true);
        $select = self::select($array);
        $t3 = microtime(true);
        echo 'select:', $t3 - $t0, PHP_EOL;

        if(false === $showResult){
            exit;
        }

        // result
        echo 'bubble:', implode(',', $bubble), PHP_EOL;
        echo 'quick:', implode(',', $quick), PHP_EOL;
        echo 'select:', implode(',', $select), PHP_EOL;
    }
}
