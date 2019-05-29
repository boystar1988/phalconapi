<?php
use Phalcon\Mvc\Model\Message as Message;

class BaseModel extends \Phalcon\Mvc\Model
{
    const IS_DEL_FALSE = 0;
    const IS_DEL_TRUE = 1;

    public function columnMap()
    {
        return [];
    }

    /**
     * 数据验证
     * PresenceOf	检测字段的值是否为非空
     * Identical	检测字段的值是否和指定的相同
     * Email	    检测值是否为合法的email地址
     * ExclusionIn	检测值是否不在列举的范围内
     * InclusionIn	检测值是否在列举的范围内
     * Regex	    检测值是否匹配正则表达式
     * StringLength	检测值的字符串长度
     * Between	    检测值是否位于两个值之间
     * Confirmation	检测两个值是否相等
     * @return bool
     */
    public function validation()
    {
        $validation = new \Phalcon\Validation();
        if(!$this->validate($validation)){
            return false;
        }
        return true;
    }

    /**
     * 验证
     * @param \Phalcon\ValidationInterface $validator
     * @return bool
     */
    public function validate($validator)
    {
        $ruleMap = include dirname(__DIR__) . "/config/dbmap.php";
        $rule = $ruleMap[$this->getSource()]??[];
        foreach ($rule as $k=>$v){
            if(isset($v['min']) || isset($v['max'])){
                if(isset($v['max'])){
                    $filter['maximum'] = $v['max'];
                }
                if(isset($v['min'])){
                    $filter['minimum'] = $v['min'];
                }
                $filter['message'] = $this->columnMap()[$k].'的值必须在'.$v['min'].'~'.$v['max'].'之间';
                $validator->add($k,new \Phalcon\Validation\Validator\Between($filter));
            }
            if(isset($v['length'])){
                $validator->add($k,new \Phalcon\Validation\Validator\StringLength([
                    "max" => $v['length'],
                    "min" => 0,
                    "messageMaximum" => $this->columnMap()[$k]."长度不能超过".$v['length'],
                    "messageMinimum" => $this->columnMap()[$k]."长度不能少于".$v['length'],
                ]));
            }
            if(isset($v['in'])){
                $validator->add($k,new \Phalcon\Validation\Validator\InclusionIn([
                    'domain'=>$v['in'],
                    'message'=>$this->columnMap()[$k].'格式不正确，取值范围：('.implode(',',$v['in']).')',
                ]));
            }
        }
        return parent::validate($validator);
    }

    /**
     * 载入Model数据
     * @param array $attribute
     * @return $this
     */
    public function load(array $attribute)
    {
        $rule = $this->columnMap();
        foreach ($attribute as $k=>$v){
            if(array_key_exists($k,$rule)){
                $this->$k = $v;
            }
        }
        return $this;
    }

    /**
     * 获取第一条错误
     * @return string
     */
    public function getFirstMessage()
    {
        $firstMessage = '';
        if($this->getMessages()){
            $firstMessage = $this->getMessages()[0]->getMessage();
        }
        return $firstMessage;
    }

    /**
     * 保存
     * @param $data
     * @return bool
     */
    public function loadAndSave($data)
    {
        $this->load($data);
        if($this->validation()){
            return $this->save();
        }
        return false;
    }

}
