<?php

include_once $base_path.'/php/classes/model/listmodel.php';

/**
 * Description of add_pages_list_module
 *
 * @author Ian
 */
abstract class AddPagesListModel extends ListModel {

    protected $hasParent;
    protected $parent_field;
    protected $invalid_page_names;


    function MakeValidPagename($id, $parent_id, $page_name)
	{
		$page_name = preg_replace('/[\',"]+/', '', strtolower($page_name));
		$page_name = preg_replace('/[^a-z1-9.]+/', '_', $page_name);
		$page_name = preg_replace('/_$/', '', $page_name);

		$sql = "select page_name from {$this->table} where page_name like '$page_name%'";
		if ($id)
			$sql .= "and id <> '$id'";
		if ($this->hasParent)
			$sql .= "and {$this->parent_field} = '$parent_id'";

		$result = mysql_query($sql);

		if ((mysql_num_rows($result) > 0) || (file_exists($base_path . '/' . $page_name)) || (in_array($page_name, $this->invalid_page_names)))
		{
			$name_part = $page_name;
			$rows = array();
			while ($row = mysql_fetch_array($result)) {
				$rows[] = $row['page_name'];
			}
			$i = 1;

			while ((in_array($page_name, $rows)) || (file_exists($base_path . '/' . $page_name)) || (in_array($page_name, $this->invalid_page_names)))
			{
				$i++;
				$page_name = $name_part.$i;
			}
		}
		return $page_name;
	}    
}

?>
