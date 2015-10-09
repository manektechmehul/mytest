<?php

class databaselookup_field_typeahead extends abstract_field {

    private $tabledata;

    function __construct($fieldname, $name, $required = true, $validate = false, $outputTemplate = '', $emailTemplate = '') {
        parent::__construct($fieldname, $name, $required = true, $validate = false, $outputTemplate = '', $emailTemplate = '');
        $this->initialValues = true;
		$this->required = true;
	    $this->name = "Seconder Member";
    }

    function setOutputTemplate($outputTemplate) {
        if (!empty($outputTemplate))
            $this->outputTemplate = $outputTemplate;
        else

	        $this->outputTemplate = "<tr valign=top><td><div align=right>Seconder</div></td><td>
	        <input id='seconder_select' name='seconder_select' type='text'   placeholder='Search members...' autocomplete='off' />
	        <input id='seconder' name='seconder' type='hidden' />
	        </td></tr>
	        <script src='/js/bootstrap-typeahead.min.js' type='text/javascript'></script>
	        <script>
				$('#seconder_select').typeahead({ source: %s, display: 'name', val: 'id',
					 onSelect: function(e){
						$('#seconder').val(e.value);
						}
                });
			</script>";
    }

    function __get($var) {
        if ($var == 'values')
            return '';
    }

    function __set($var, $value) {
        if ($var == 'values')
            $this->setValues($value);
    }

    function setValues($data) {
        $sql = "select * from $data";
       
        $result = mysql_query($sql);

        while ($row = mysql_fetch_array($result)) {
			
			
			if(isset($row['title'])){
				$title = $row['title'];
			}
			
			if(isset($row['name'])){
				$title = $row['name'];
			}

	        // this is the one for shop_member_users
	        if(isset($row['firstname'])) {
		        $title = $row['firstname'] . ' ' . $row['surname'];
	        }

			
			
            $this->tabledata[$row['id']] = array(
                'id' => $row['id'],
                // TODO: GL need to pass in a varible to select the chosen field  
                // Currently just mash up the two most likely columns 			
                'title' => $title,
            );
        }
    }

    function output() {
        // $selectedItem = $_GET[$this->default];
       // $selectedItem = $this->default;
       // $inner = "<select name=\"{$this->fieldname}\">";
        $json_array = "[";
        foreach ($this->tabledata as $value) {

	        $json_array .= "{ id: {$value['id']}, name: '{$value['title']}'},";
          //  $selected = ($value['id'] == $selectedItem) ? 'selected="selected" ' : '';
          //  $inner .= "<option value=\"{$value['id']}\" $selected/> {$value['title']}<br />";
        }
	    // trim off last comma
	    $json_array = substr($json_array,0,strlen($json_array)-1);
	    // close array
	    $json_array .= "]";


      //  $inner .= "</select>";
        $output = sprintf($this->outputTemplate,$json_array);
        return $output;
    }

    function getEmailMessage() {
        return sprintf($this->emailTemplate, $this->name, $this->tabledata[$this->data]['title']);
    }

    function isDataMissing() {
        return empty($this->data);
    }

    function isDataInvalid() {
	    $d = $this->data;
        return false;
    }

    function processTemplate($message) {
        $message = str_replace("[FC:{$this->fieldname}]", ucwords($this->tabledata[$this->data]['title']), $message);
        $message = str_replace("[{$this->fieldname}]", $this->tabledata[$this->data]['title'], $message);
        return $message;
    }

}

