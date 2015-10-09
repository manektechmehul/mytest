<?php
    class pagination
    {
        var $pages;
        var $current_page;  
        var $link_tpl;
        var $elipse_tpl;
        var $prev_tpl;
        var $next_tpl;
        var $current_tpl;
        
        function  __construct($perPage = 3)
        {
            $this->link_tpl = '<li><a href="%s">%s</a></li>';
            $this->no_prev_tpl = '<li class="disabled"><a href="#">&laquo;</a></li>';
            $this->prev_tpl = '<li><a href="%s">&laquo;</a></li>';
            $this->no_next_tpl = '<li class="disabled"><a href="#">&raquo;</a></li>';
            $this->next_tpl = '<li><a href="%s">&raquo;</a></li>';
            $this->elipse_tpl = '<span class="pag_elipse">...</span>';
            $this->current_tpl = '<li class="active"><a href="#">%s</a></li>';
            $this->perPage = $perPage;            
        }  
        
        function set_pages()
        {
            $sql_count = 'select FOUND_ROWS() as rowcount';
            $result_count = mysql_query($sql_count);
            $row_count = mysql_fetch_array($result_count);		
		    $this->pages = ceil($row_count['rowcount'] / $this->perPage);			 
        }
        
        function page_start_row()
        {
            return ($this->current_page -1) * $this->perPage;
        }
        
        function set_page()
        {
			if (isset($_REQUEST['page']))
                $this->current_page = $_REQUEST['page'];
            else
                $this->current_page = 1;
        }

        function GetLimits()
        {
            $start = $this->page_start_row();
            $perPage = $this->perPage;
            return " limit $start, $perPage";
        }

        function html_string($page_link)
        {
			
			
			 
			
            $str = '';
            if ($this->current_page > 1) {
	            $str .= sprintf( $this->prev_tpl, $page_link . ( $this->current_page - 1 ) );
            }else {
	            $str .= sprintf( $this->no_prev_tpl );
            }
            $max = $this->pages;
            
			
			
            if ($max <= 1)
                return '';
            
            $current = $this->current_page;
            if ($max <= 12)
            {
                for($i = 1; $i <= $max; $i++)
                {
                    if ($i == $current)
                        $str .= sprintf($this->current_tpl, $i);
                    else
                        $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                }
            }
            else
            {
                if ($current <= 5)
                {
                    $upper = ($max - 1);
                    for($i = 1; $i < 7; $i++)
                    {
                        if ($i == $current)
                            $str .= sprintf($this->current_tpl, $i);
                        else
                            $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }                
                    $str .= sprintf($this->elipse_tpl);
                    for($i = $upper; $i <= $max; $i++)
                    {
                        $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }                
                }
                else if ($current > ($max - 5))
                {
                    $upper = ($max - 5);
                    
                    for($i = 1; $i < 3; $i++)
                    {
                        $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }             
                    $str .= sprintf($this->elipse_tpl);
                    for($i = $upper; $i <= $max; $i++)
                    {
                        if ($i == $current)
                            $str .= sprintf($this->current_tpl, $i);
                        else
                            $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }                
                }
                else
                {
                    $upper = ($max - 1);
                    $middle_start = $current - 2;
                    $middle_end = $current + 2;
                    
                    for($i = 1; $i < 3; $i++)
                    {
                        $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }             
                    $str .= sprintf($this->elipse_tpl);
                    for($i = $middle_start; $i <= $middle_end; $i++)
                    {
                        if ($i == $current)
                            $str .= sprintf($this->current_tpl, $i);
                        else
                            $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }                
                    $str .= sprintf($this->elipse_tpl);
                    for($i = $upper; $i <= $max; $i++)
                    {
                        $str .= sprintf($this->link_tpl, $page_link.$i,$i);
                    }                
                }
            }
            
            if ($this->current_page < $this->pages)
                 $str .= sprintf($this->next_tpl, $page_link.($this->current_page + 1));
            else
                $str .= sprintf($this->no_next_tpl);
        
			
            return $str;
        }
        
        function set_pages_val($items){
			 
			
        	$this->pages = ceil($items / $this->perPage) ;
		
        	
        }
        
    }
?>
