<?php

include_once "listmodel.php";

class CategoriesModel extends ListModel
{
	function  __construct() {
		parent::__construct();
                // todo: get this up to the top level
                /* #module specific */
		$this->table = 'category';
	}

	function GetSubCategoriesByPageName($pageName)
	{
		$parentId = db_get_single_value("select id from {$this->table} where page_name = '$pageName'");
		$sql = "select * from {$this->table} where parent = $parentId";		
		$items = $this->ReadItems($sql);
		return $items;
	}

	function GetSubCategoryByPageName($pageName)
	{
		$sql = "select * from {$this->table} where page_name = '$pageName'";		
		return $this->ReadItem(0, $sql);
	}

	function GetParentCategoryPageName($id)
	{
		$parentId = db_get_single_value("select * from {$this->table} where id = (select parent from category where id = '$id')");
		return db_get_single_value("select page_name from {$this->table} where id = '$parentId'");		
	}
	function GetSubmenuItems($id = 0)
	{
		$sql = "SELECT c.id, c.title, c.page_name FROM {$this->table} c where parent = $id";
		return $this->ReadItems($sql);
	}

	function BuildMenu($id, $level, $menulink, $nameParts, $validCategories)
	{
		$items = $this->GetSubmenuItems($id);
		$this->has_submenu = false;
		if (count($items) > 0)
		{
			$submenu = array();
			foreach ($items as $item) {
				if (!in_array($item['id'], $validCategories))
					continue;
				$submenuitem = array();
				$submenuitem['name'] = $item["title"];
				$submenuitem['on'] = ($item["page_name"] == $nameParts[1+$level]);

				$submenuitem['link'] = $menulink.'/'.$item["page_name"];
				if (($level == 0) && ((MENU_STYLE == 0) || ($submenuitem['on'])))
				{
					$submenuitem['pageMenu'] = $this->BuildMenu($item['id'], $level + 1, $menulink.'/'.$item["page_name"], $nameParts, $validCategories);
				}
				$submenu[] = $submenuitem;
			}
			$this->has_submenu = true;
			return $submenu;
		}
	}
}
