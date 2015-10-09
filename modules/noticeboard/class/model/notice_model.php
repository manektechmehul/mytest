<?php
include_once $base_path.'/php/classes/model/add_pages_list_module.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of notice_model
 *
 * @author Ian
 */
class NoticeModel extends AddPagesListModel {
    
	function  __construct($paginate = false)
    {
		$this->table = 'notice_post';
		$this->orderField = 'date';
		$this->publishedField = 'published';
        $this->categoryTable = 'notice_category';
        $this->categoryField = 'category';
        parent::__construct();
	}    

    function ReadItemsByCategory($category) {
        $sql = "SELECT np.id, np.page_name, np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName', mu.screenname as 'memberScreenName',
                    nc.title AS category, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`,
                    (select count(*) from notice_comments ncom where ncom.notice = np.id) as comment_count
                    FROM notice_post np
                    JOIN member_user mu ON np.member = mu.id
                    JOIN notice_category nc ON np.category = nc.id and nc.id = $category order by np.date desc";            

        return $this->ReadItems($sql);
    }
    
    function ReadItem($id, $sql = '') {
        if ($sql == '')
            $sql = "SELECT np.id, np.title, np.description,CONCAT(mu.firstname,' ',mu.surname) AS 'memberName', mu.screenname as 'memberScreenName',
                nc.title AS category, nc.id as category_id, np.link, np.linktitle, unix_timestamp(np.`date`) as `date`
                FROM notice_post np
                JOIN member_user mu ON np.member = mu.id
                JOIN notice_category nc ON np.category = nc.id and np.id = $id order by np.date desc";
        
        return parent::ReadItem($id, $sql);
    }
  
    
    function ReadMenuItems ($menulink)
    {
//		$sql = "select c.id, c.title, c.page_name, count(*) from {$this->categoryTable} c join {$this->table} t on c.id = t.{$this->categoryField} ".
//                'where c.id > 1 and c.published = 1 '.
//				'group by title, c.page_name order by order_num';
		$sql = "select c.id, c.title, c.page_name, count(*) from {$this->categoryTable} c".
                ' where c.id > 1 and c.published = 1 '.
    			' group by title, c.page_name order by order_num';
		$result = mysql_query($sql);     
        
		global $name_parts;

		$this->has_submenu = false;
		if (mysql_num_rows($result) > 0) 
		{
			$submenu = array();
			while ($row = mysql_fetch_array($result)) {    
				$submenuitem = array();
				$submenuitem['name'] = $row["title"];
				$submenuitem['on'] = ($row["page_name"] == $name_parts[1]);
				$submenuitem['link'] = $menulink.'/'.$row["page_name"];
				$submenu[] = $submenuitem;
			}
			$this->has_submenu = true;
			return $submenu;
		}        
    }
    
    function ProcessData($data, $member)
    {
        $pageName = $this->MakeValidPagename($data['id'], 0, $data['title']);
        
		$sqldata = array(
			'member' => $member,
			'date' => date('Y-m-d H:i:s'),
			'title' => $data['title'],
            'page_name' => $pageName,
			'description' => $data['description'],
			'linktitle' => $data['linktitle'],
			'link' => $data['link'],
			'category' => $data['category'],
		);

		foreach ($sqldata as $title => $value)
		{
			$sqlfields[] = $title;
			$sqlvalues[] = $value;
		}
		$sql = 'insert into notice_post (`'.implode('`,`', $sqlfields).'`)'.
				"values ('".implode("','", $sqlvalues)."')";
		mysql_query($sql);

		return mysql_insert_id();
    }
    
}

