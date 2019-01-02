<?php

class PhaUser extends BaseModel
{

    /**
     * 
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $uid;

    /**
     * 
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $username='';

    /**
     * 
     * @var string
     * @Column(type="string", length=32, nullable=false)
     */
    public $password='';

    /**
     * 
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_del='0';

    /**
     * 
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $create_time='0';

    /**
     * 
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $update_time='0';

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pha_user';
    }

    /**
     * 获取字段名称
     * @param $attribute
     * @return mixed
     */
    public function labelName($attribute)
    {
        $labelsMap = [
            //标题名称赋值
        ];
        return $labelsMap[$attribute]??$attribute;
    }

}
