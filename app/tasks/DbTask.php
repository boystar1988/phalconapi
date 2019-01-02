<?php

class DbTask extends \Phalcon\CLI\Task
{

    /**
     * 更新Model数据结构
     */
    public function renewAction()
    {
        $string = "<?php".PHP_EOL;
        $string .= "/**".PHP_EOL;
        $string .= " * 数据库字段校验规则".PHP_EOL;
        $string .= " * (系统自动生成)".PHP_EOL;
        $string .= " * @author zhaozhuobin".PHP_EOL;
        $string .= " * @date ".date("Y-m-d H:i").PHP_EOL;
        $string .= " */".PHP_EOL;
        $string .= "return [".PHP_EOL;
        $tableRes = $this->di->get('db')->query('show tables;')->fetchAll();
        $tables = array_column($tableRes,'Tables_in_'.$this->config->database->dbname);
        $list = [];
        foreach ($tables as $v){
            $string .= "    \"$v\"  =>  [".PHP_EOL;
            $columnRes = $this->di->get('db')->query('desc '.$v.';')->fetchAll();
            foreach ($columnRes as $kk=>$vv){
                $string .= "        \"{$vv['Field']}\"  =>  [".PHP_EOL;
                //Todo: 自增字段跳过
                if($vv['Extra'] == 'auto_increment') {
                    $string .= "        ],".PHP_EOL;
                    continue;
                }
                preg_match("/^(\w+)(\((.*)\))?/",$vv['Type'],$match);
                $list[$v][$vv['Field']]['type'] = $match[1]??'';
                switch ($match[1]??'')
                {
                    case 'int':
                    case 'tinyint':
                    case 'smallint':
                    case 'mediumint':
                    case 'bigint':
                        $list[$v][$vv['Field']]['min'] = 0;
                        $list[$v][$vv['Field']]['max'] = intval(str_repeat("9",$match[3]??''));
                        $string .= "            \"min\"  =>  {$list[$v][$vv['Field']]['min']},".PHP_EOL;
                        $string .= "            \"max\"  =>  {$list[$v][$vv['Field']]['max']},".PHP_EOL;
                        break;
                    case 'double':
                    case 'float':
                    case 'decimal':
                        $range = explode(',',$match[3]??'');
                        $list[$v][$vv['Field']]['min'] = 0;
                        $list[$v][$vv['Field']]['max'] = floatval(str_repeat("9",$range[0]??'') . "." . str_repeat("9",$range[1]??''));
                        $string .= "            \"min\"  =>  {$list[$v][$vv['Field']]['min']},".PHP_EOL;
                        $string .= "            \"max\"  =>  {$list[$v][$vv['Field']]['max']},".PHP_EOL;
                        break;
                    case 'enum':
                        $range = str_replace("'",'',$match[3]??'');
                        $range = str_replace("\"",'',$range);
                        $range = explode(',',$range);
                        $list[$v][$vv['Field']]['in'] = $range;
                        $string .= "            \"in\"  =>  [".($match[3]??'')."],".PHP_EOL;
                        break;
                    case 'varchar':
                    case 'char':
                        $list[$v][$vv['Field']]['length'] = intval($match[3]??'');
                        $string .= "            \"length\"  =>  {$list[$v][$vv['Field']]['length']},".PHP_EOL;
                        break;
                    case 'text':
                        $list[$v][$vv['Field']]['length'] = 65535;
                        $string .= "            \"length\"  =>  {$list[$v][$vv['Field']]['length']},".PHP_EOL;
                        break;
                    case 'mediumtext':
                        $list[$v][$vv['Field']]['length'] = 16777215;
                        $string .= "            \"length\"  =>  {$list[$v][$vv['Field']]['length']},".PHP_EOL;
                        break;
                    case 'tinytext':
                        $list[$v][$vv['Field']]['length'] = 256;
                        $string .= "            \"length\"  =>  {$list[$v][$vv['Field']]['length']},".PHP_EOL;
                        break;
                    case 'longtext':
                        $list[$v][$vv['Field']]['length'] = 4294967295;
                        $string .= "            \"length\"  =>  {$list[$v][$vv['Field']]['length']},".PHP_EOL;
                        break;
                }
                $string .= "        ],".PHP_EOL;
            }
            $string .= "    ],".PHP_EOL;
        }
        $string .= "];".PHP_EOL;
        file_put_contents(APPLICATION_PATH.'/config/dbmap.php',$string);
        echo "生成成功！".PHP_EOL;
    }

    /**
     * 生成model文件
     */
    public function modelAction()
    {
        $tableRes = $this->di->get('db')->query('show tables;')->fetchAll();
        $tables = array_column($tableRes,'Tables_in_'.$this->config->database->dbname);
        $fields = [];
        foreach ($tables as $v){
            $columnRes = $this->di->get('db')->query('desc '.$v.';')->fetchAll();
            $uv = '_'. str_replace('_', " ", strtolower($v));
            $uv = ltrim(str_replace(" ", "", ucwords($uv)), '_' );
            $uv = ucfirst($uv);
            //Todo: Class
            $string = "<?php".PHP_EOL;
            $string .= PHP_EOL;
            $string .= "class {$uv} extends BaseModel".PHP_EOL;
            $string .= "{".PHP_EOL;
            $string .= PHP_EOL;
            //Todo: public $attribute;
            foreach ($columnRes as $vv){
                preg_match("/^(\w+)(\((.*)\))?/",$vv['Type'],$match);
                $fields[] = $vv['Field'];
                $string .= "    /**".PHP_EOL;
                $string .= "     * ".PHP_EOL;
                $string .= "     * @var ".$this->getType($match[1]).PHP_EOL;
                if($vv['Key'] == 'PRI') {
                    $string .= "     * @Primary".PHP_EOL;
                    $string .= "     * @Identity".PHP_EOL;
                }
                $string .= "     * @Column(type=\"".$this->getType($match[1])."\", length={$match[3]}, nullable=false)".PHP_EOL;
                $string .= "     */".PHP_EOL;
                if($vv['Extra'] == 'auto_increment'){
                    $string .= "    public \${$vv['Field']};".PHP_EOL;
                }else{
                    $string .= "    public \${$vv['Field']}='{$vv['Default']}';".PHP_EOL;
                }
                $string .= PHP_EOL;
            }
            //Todo: getSource()
            $string .= "    /**".PHP_EOL;
            $string .= "     * Returns table name mapped in the model.".PHP_EOL;
            $string .= "     *".PHP_EOL;
            $string .= "     * @return string".PHP_EOL;
            $string .= "     */".PHP_EOL;
            $string .= "    public function getSource()".PHP_EOL;
            $string .= "    {".PHP_EOL;
            $string .= "        return '{$v}';".PHP_EOL;
            $string .= "    }".PHP_EOL;
            $string .= PHP_EOL;
            //Todo: labelName()
            $string .= "    /**".PHP_EOL;
            $string .= "     * 获取字段名称".PHP_EOL;
            $string .= "     * @param \$attribute".PHP_EOL;
            $string .= "     * @return mixed".PHP_EOL;
            $string .= "     */".PHP_EOL;
            $string .= "    public function labelName(\$attribute)".PHP_EOL;
            $string .= "    {".PHP_EOL;
            $string .= "        \$labelsMap = [".PHP_EOL;
            $string .= "            //标题名称赋值".PHP_EOL;
            $string .= "        ];".PHP_EOL;
            $string .= "        return \$labelsMap[\$attribute]??\$attribute;".PHP_EOL;
            $string .= "    }".PHP_EOL;
            $string .= PHP_EOL;
            $string .= '}'.PHP_EOL;
            //Todo: backup
            if(is_file(APPLICATION_PATH.'/models/'.$uv.'.php')){
                shell_exec("mv ".APPLICATION_PATH.'/models/'.$uv.'.php '.APPLICATION_PATH.'/models/'.$uv.'.php~');
            }
            file_put_contents(APPLICATION_PATH.'/models/'.$uv.'.php',$string);
            unset($string);
        }
        echo "生成成功！".PHP_EOL;
    }

    private function getType($type)
    {
        if(in_array($type,['tinyint','smallint','mediumint','int','bigint'])){
            return 'integer';
        }
        if(in_array($type,['varchar','char','mediumtext','text','tinytext','longtext','enum'])){
            return 'string';
        }
        if(in_array($type,['decimal','float','double'])){
            return 'double';
        }
        return '';
    }

}