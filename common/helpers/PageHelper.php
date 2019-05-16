<?php
use Phalcon\Mvc\Model\Criteria;

class PageHelper
{

    /**
     * 分页
     * @param Criteria $qb
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function pageOutput(Criteria $qb,$page=PAGE_NO_DEFAULT,$pageSize=PAGE_SIZE_DEFAULT)
    {
        $count = $qb->execute()->count();
        if($pageSize){
            $qb->limit($pageSize,($page-1)*$pageSize);
        }
        $data = $qb->execute();
        return [
            'total_page'=>ceil($count/$pageSize),
            'total_record'=>intval($count),
            'page_no'=>intval($page),
            'page_size'=>intval($pageSize),
            'list'=>$data,
        ];
    }

}