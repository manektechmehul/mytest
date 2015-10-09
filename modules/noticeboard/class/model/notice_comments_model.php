<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of notice_comments
 *
 * @author Ian
 */
include_once $base_path.'/php/classes/model/listmodel.php';

class NoticeCommentsModel extends ListModel {

    function __construct($paginate = false) {
        $this->table = 'notice_comments';
        parent::__construct($paginate);
    }
    
    function GetCommentsForNotice($notice) {
        
        $sql = "select
			 nc.id, 
			 parent,
			 `text`,
			 concat(`firstname`,' ',`surname`) as 'name', screenname,
			 (select count(ncsub.id) from notice_comments ncsub where nc.parent = ncsub.id and ncsub.status = 1) as children,
			 unix_timestamp(`timestamp`) datestamp
			from notice_comments nc
			join member_user mu on mu.id = nc.member
			where notice = $notice and nc.status = 1
			order by case when parent = 0 then nc.id else parent end, case when parent = 0 then 0 else nc.id end";
        
        return $this->ReadItems($sql);
    }
    
    function AddComment($name, $comment,$notice, $parent, $member, $comment_status) {
        $new_comment_sql = "insert into {$this->table} (name, `text`, notice, parent, member,  status) values ".
                    "('$name', '$comment', '$notice', '$parent', $member,  '$comment_status')";

        return  mysql_query($new_comment_sql);        
    }
    
}

