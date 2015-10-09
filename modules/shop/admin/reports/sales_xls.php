<?php
require 'excel_export.php';    
class Sales_Report_XLS extends Excel_Export
{
	var $customer_name;
	var $address;
	var $delivery_instuctions;
    var $total;
    var $grand_total;
    var $maker_total;
    var $subtitle;
    var $heading_fmt;
	function Sales_Report_XLS($title)
	{
		$this->Excel_Export($title);
	}
	//Page header
	function Header($title, $subtitle)
	{
        parent::Header($title);
        $this->heading_fmt = $this->book->addFormat(array('Bold'=>1, 'Size' => 10));
        $sub_title_fmt = $this->book->addFormat(array('Bold'=>1, 'Size' => 12));      
        $this->sheet->write(3,0,$subtitle, $sub_title_fmt);
        $this->sheet->setColumn(0,0,20);
        $this->sheet->setColumn(1,1,20);
        $this->sheet->setColumn(2,2,13);
        $this->sheet->setColumn(3,3,16);
        $this->sheet->setColumn(5,5,20);
        $this->current_line ++;
        $ln = $this->current_line++;
        $hdr_fmt = $this->hdr_fmt;
        $this->sheet->write($ln,1,'Maker', $hdr_fmt);
        $this->sheet->write($ln,2,'Stock Code', $hdr_fmt);
        $this->sheet->write($ln,3,'Time', $hdr_fmt);
        $this->sheet->write($ln,4,'Tender', $hdr_fmt);
        $this->sheet->write($ln,5,'Product', $hdr_fmt);
        $this->sheet->write($ln,6,'Quantity', $hdr_fmt);
        $this->sheet->write($ln,7,'Price', $hdr_fmt);        
	}
    function show_heading($title)
    {
        $ln = $this->current_line++;
        $this->sheet->write($ln,0,$title,$this->heading_fmt);
    }
}
$report_id = $_REQUEST['report_id'];
$report->cur_page = 1;
$report_sql = 'select unix_timestamp(`Start Date`) as `Start Date`, unix_timestamp(`End Date`) as `End Date`, Detailed, '.
      ' `Maker Id`, `All Categories` from shop_sales_report where id = '.$report_id;
$report_result = mysql_query($report_sql);
$report_row = mysql_fetch_array($report_result);
$start_date = $report_row ['Start Date'];
$end_date = $report_row ['End Date'];
$detailed = $report_row ['Detailed'];
$maker_id = $report_row ['Maker Id'];
//Instanciation of inherited class
$report_title = 'Sales Report ';
$report_title .= ($detailed > 0) ? '(detailed)' : '(summary)'; 
if ($maker_id) 
{
    $maker_name = db_get_single_value("select name from shop_maker where id = $maker_id", 'name');
    $subtitle = "Sales for $maker_name in period ";
}
else
   $subtitle = "Sales in period ";
$subtitle  .= ' '. date('jS M Y', $start_date) . ' to ' . date('jS M Y', $end_date);
// start report
$report = new Sales_Report_XLS($report_title);
$report->Header($report_title, $subtitle);
$all_categories = $report_row['All Categories'];
if ($all_categories > 0)
{
    $category_sql = 'select c.id, c.name from shop_category c where c.special = 0';
}
else
{    
    $category_sql = 'select c.id, c.name from shop_sales_report_categories src '.
        'join shop_sales_report sr on sr.id = src.report_id '.
        'join shop_category c on c.id = src.category_id where sr.id = '.$report_id;
}
$category_result = mysql_query($category_sql);
$tender_types = array('online', 'card', 'cash', 'cheque', 'online');
while ($category_row = mysql_fetch_array($category_result)) 
{
    $report->show_heading($category_row['name']);
    $category_id = $category_row['id'];
    $item_sql = 'select p.name, ccd.name as \'colourname\',  p.stock_code, oi.quantity, unix_timestamp(o.time_made) as `date`, '.
        'tender_type as tender, quantity * price as row_price, m.name as maker_name '.
        'from shop_product p '.
        'join shop_maker m on m.id = maker_id '.
        'join shop_order_item oi on oi.product_id = p.id '.
		'join colour_colour_details ccd on oi.colour_id = ccd.id '.
        'join shop_order o on o.id = oi.order_id '.
        "where primary_category_id = $category_id and o.time_made between '".
        date('Y-m-d', $start_date)."' and '".date('Y-m-d', $end_date)."' ";
    if ($maker_id > 0)
        $item_sql .= ' and m.id = '.$maker_id;
    $item_result = mysql_query($item_sql);
    if (mysql_num_rows($item_result) == 0)
    {
        $ln = $report->current_line++;
        $report->sheet->write($ln,0,'no sales');
    }
    else
    {
        $start_line = $report->current_line;
        while ($item_row = mysql_fetch_array($item_result))
        {
            if ($detailed)
            {
                $ln = $report->current_line++;
                $transaction_date = date('d/m/y G:i', $item_row['date']);
                $tender_name =  $tender_types[$item_row['tender']];
				$orderProductName = "{$item_row['name']} ({$item_row['colourname']})";
                $report->total[$item_row['tender']] += $item_row['row_price'];  
                //$report->sheet->write($ln,1,$item_row['maker_name']);
                $report->sheet->write($ln,2,$item_row['stock_code']);
                $report->sheet->write($ln,3,$transaction_date);
                $report->sheet->write($ln,4,$tender_name);
                $report->sheet->write($ln,5,$orderProductName);
                $report->sheet->write($ln,6,$item_row['quantity']);
                $report->sheet->write($ln,7,$item_row['row_price'], $report->money_fmt);
            }
            else 
                $report->total[$item_row['tender']] += $item_row['row_price'];  
        }
        $start_line = $report->current_line;
        $first = true;
        foreach ($report->total as $tender_type => $tender_total)
        {
            if ($tender_total == 0)
                continue;
            $ln = $report->current_line++;
            if ($first)
            {
                $report->sheet->write($ln,6,$tender_types[$tender_type], $report->total_fmt);
                $report->sheet->write($ln,7,$tender_total, $report->money_total_fmt);
                $first = false;
            }
            else
            {
                $report->sheet->write($ln,6,$tender_types[$tender_type]);
                $report->sheet->write($ln,7,$tender_total, $report->money_fmt);
            }
            $report->total[$tender_type] = 0;
        }
        $end_line = $report->current_line;
        $ln = $report->current_line++;
        $report->sheet->write($ln,6,'total', $report->total_fmt);
        $report->sheet->writeFormula($ln,7,"=sum(H$start_line:H$end_line)", $report->money_total_fmt);
    }
}	
$report->Output('sales.xls');
?>