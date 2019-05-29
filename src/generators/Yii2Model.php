<?php

namespace icy2003\scripts\generators;

use icy2003\php\I;
use icy2003\php\ihelpers\File;
use icy2003\php\ihelpers\Strings;
use icy2003\scripts\Scripts;

class Yii2Model
{
    protected $_tables;
    protected $_namespace;
    protected $_type;
    protected $_activeRecordTemplate = null;
    protected $_files = [];

    public function __construct()
    {
        $this->_tables = [];
        $this->_type = 'Model';
        Scripts::load();
    }

    public function setTables($tables)
    {
        $this->_tables = $tables;
        return $this;
    }

    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    public function setActiveRecordTemplate($activeRecordTemplate = false)
    {
        $this->_activeRecordTemplate = $activeRecordTemplate;
        return $this;
    }

    public function run()
    {
        if (null === $this->_activeRecordTemplate) {
            array_push($this->_files, [
                'ActiveRecord.php',
                <<<EOT
<?php
/**
 * Class ActiveRecord
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) 2019, icy2003
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
            ]);
        }
        array_map(function ($table) {
            $modelName = ucfirst(Strings::underline2camel($table) . $this->_type);
            $namespaceString = 'namespace ' . $this->_namespace . ';' . PHP_EOL;
            null === $this->_activeRecordTemplate && $namespaceString .= 'use yii\db\ActiveRecord' . PHP_EOL;
            array_push($this->_files, [
                $modelName . '.php',
                <<<EOT
<?php
/**
 * Class {$modelName}
 *
 * @link https://www.icy2003.com/
 * @author icy2003 <2317216477@qq.com>
 * @copyright Copyright (c) 2019, icy2003
 */
{$namespaceString}

/**
 * {$modelName}
 */
class {$modelName} extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%{$table}}}';
    }
}

EOT
            ]);
        }, $this->_tables);
        array_map(function ($file) {
            list($path, $content) = $file;
            $path = I::getAlias('@icy2003/scripts_runtime/generators-Yii2Model/' . $path);
            if (!File::fileExists($path)) {
                file_put_contents($path, $content);
            }
        }, $this->_files);
    }
}
