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

}