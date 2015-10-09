<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of notice_categories
 *
 * @author Ian
 */

include_once $base_path.'/php/classes/model/listmodel.php';

class NoticeCategoriesModel extends ListModel {
    
    function __construct($paginate = false) {
        $this->table = 'notice_category';
        parent::__construct($paginate);
        $this->publishedField = 'published';
    }
    
    function getAddressees($category) {
		$sql = "SELECT mu.* FROM  member_user mu
				JOIN member_user_notification_categories mn ON mu.id = mn.user_id
				WHERE category_id = $category";

		return db_get_rows($sql);
    }
}

?>
