<?php
/**
 * Class Yii2Model
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) 2019, icy2003
 */
namespace icy2003\scripts\generators;

use icy2003\php\I;
use icy2003\php\ihelpers\File;
use icy2003\php\ihelpers\Strings;
use icy2003\scripts\Scripts;

/**
 * Yii2Model
 *
 * 生成 Yii2 的模型类文件
 *
 * ```
 * 我知道 Yii2 有几个表叫 `user`、`image`，现在我需要创建这几个表的模型：右键，新建 txt，改名字……
 * 好累！
 * 我要生成的文件长得都一样，只是换了个表名~~而已，那么，用这个类吧：
 * $yii2Model = new Yii2Model();
 * $yii2Model->setTables(['user', 'image', 'file'])->run();
 * 仅此而已……
 * 文件会被生成在 runtime 目录下
 * ps：你也可以使用 Yii2 的脚手架。当然我不太想为了几个文件去装个模块然后还在网页上跑，更重要的是，生成之后的类还得自己删不需要的函数，注释也不规范
 * ```
 */
class Yii2Model
{

    /**
     * 待生成的文件列表
     *
     * @var array
     */
    protected $_files = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        Scripts::load();
    }

    /**
     * 是否强制重新生成
     *
     * @var bool
     */
    protected $_forceUpdate = false;

    /**
     * 是否强制重新生成
     *
     * @param boolean $forceUpdate 默认 true，是
     *
     * @return static
     */
    public function forceUpdate($forceUpdate = true)
    {
        $this->_forceUpdate = $forceUpdate;
        return $this;
    }

    /**
     * 表名列表
     *
     * @var array
     */
    protected $_tables = [];

    /**
     * 设置表名列表
     *
     * @param array $tables
     *
     * @return static
     */
    public function setTables($tables)
    {
        $this->_tables = $tables;
        return $this;
    }

    /**
     * 模型的分类，即模型类的后缀，默认 'Model'
     *
     * 例如：Model、Form、View 分别表示表模型、表单模型、视图模型，只是语义上的约定
     *
     * @var string
     */
    protected $_type = 'Model';

    /**
     * 设置模型类别
     *
     * @param string $type
     *
     * @return static
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * 命名空间
     *
     * @var string
     */
    protected $_namespace = 'app\models';

    /**
     * 设置命名空间
     *
     * @param string $namespace
     *
     * @return static
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * 以 ActiveRecord 为父类的自定义 ActiveRecord
     *
     * 默认 null，即使用默认模板
     *
     * 格式：[文件名（带后缀）, 模板内容]
     *
     * @var array
     */
    protected $_activeRecordTemplate = null;

    /**
     * 设置以 ActiveRecord 为父类的自定义 ActiveRecord
     *
     * @param bool $activeRecordTemplate 不为 null 的话都
     *
     * @return static
     */
    public function setActiveRecordTemplate($activeRecordTemplate = false)
    {
        $this->_activeRecordTemplate = $activeRecordTemplate;
        return $this;
    }

    /**
     * 执行
     *
     * @return void
     */
    public function run()
    {
        $namespaceArray[] = $this->_namespace ? 'namespace ' . $this->_namespace . ';' : '';
        $nowYear = date('Y');
        if (null == $this->_activeRecordTemplate) {
            $this->_activeRecordTemplate = [
                'ActiveRecord.php',
                <<<EOT
<?php
/**
 * Class ActiveRecord
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) {$nowYear}, icy2003
 */
namespace app\models;

use yii\db\ActiveRecord as AR;

/**
 * ActiveRecord
 */
class ActiveRecord extends AR
{

}
EOT
            ];
        } else {
            $namespaceArray[] = 'use yii\db\ActiveRecord;';
        }
        $this->_files[] = $this->_activeRecordTemplate;
        foreach ($this->_tables as $table) {
            $modelName = ucfirst(Strings::underline2camel($table) . $this->_type);
            $namespaceString = implode(PHP_EOL, $namespaceArray);
            $this->_files[] = [
                $modelName . '.php',
                <<<EOT
<?php
/**
 * Class {$modelName}
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) {$nowYear}, icy2003
 */
{$namespaceString}

/**
 * {$modelName}
 */
class {$modelName} extends ActiveRecord
{
    /**
     * 模型表：{$table}
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%{$table}}}';
    }
}

EOT
            ];
        }
        $dir = I::getAlias('@icy2003/scripts_runtime/generators-Yii2Model');
        $this->_forceUpdate && File::deleteDir($dir);
        File::createDir($dir);
        array_map(function ($file) use ($dir) {
            list($path, $content) = $file;
            if (!File::fileExists($dir . '/' . $path)) {
                file_put_contents($dir . '/' . $path, $content);
            }
        }, $this->_files);
    }
}
