<?php
  /*  function GetMemberMessage($message_sys_name) {
        $message = db_get_single_value("select message from message_blocks where sys_code = '{$message_sys_name}'  and show_on_page = 1 ", 'message');
        return $message;
    }
    $MemberMessageBlock = GetMemberMessage('HOMEPAGE_MESSAGE');
    $smarty->assign('block', $MemberMessageBlock);
    $MemberMessageBlockTemplateFile = "$base_path/modules/message_blocks/templates/simple_message.tpl";
	$filters['filter_membermessageblock'] = array('search_string'  => '/<!-- CS message_block start *-->(.*)<!-- CS message_block end *-->/s',
       'replace_string' => '{include file="'.$MemberMessageBlockTemplateFile.'"}');
	   
	   */
	   
 
	 	
		$sql = 'SELECT * FROM message_blocks WHERE show_on_page = 1 ORDER BY 1 DESC';
		$result = mysql_query($sql);
		$blog_list = array();
		
		while ($row = mysql_fetch_array($result)) {
			$list[] = array(
				'id' => $row['id'],
				'message' =>  $row['message'],
			);	    
			$filters['filter_membermessageblock_' . $row['id']] = array('search_string'  => '/<!-- CS message_block_' . $row['id'] . ' start *-->(.*)<!-- CS message_block_' . $row['id'] . ' end *-->/s',
       'replace_string' => $row['message'] );
		}