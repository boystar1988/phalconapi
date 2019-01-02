<?php

class PhaUserProfile extends BaseModel
{

    /**
     * 
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * 
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $nickname='';

    /**
     * 
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $uid='0';

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pha_user_profile';
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
