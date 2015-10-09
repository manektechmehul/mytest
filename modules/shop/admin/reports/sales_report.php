<?php
require('report.php');

class Sales_Report_PDF extends Report_PDF
{
	var $customer_name;
	var $address;
	var $delivery_instuctions;
    var $total;
    var $grand_total;
    var $maker_total;
    var $subtitle;
	
	function Sales_Report_PDF()
	{
		parent::Report_PDF();

	}

	//Page header
	function Header()
	{
		parent::Header();
        $this->SetFont('Arial','',12);
        $this->Cell(35,$this->ln_height,'Time','B',0);
        $this->Cell(20,$this->ln_height,'Tender','B',0);
        $this->Cell(75,$this->ln_height,'Product','B',0);
        $this->Cell(10,$this->ln_height,'Quantity','B',0);
        $this->Cell(0,$this->ln_height,'Price','B',1,'R');
		$this->Ln(6);
	}

	function show_maker_total()
	{
		$lh = $this->ln_height;
		$sum_total = 0;
		$this->Cell(130);
		$this->Cell(0,1,'','T',1,'R');
		foreach ($this->maker_total as $maker => $total)
		{
            $this->SetFont('Arial','i',12);
			$this->Cell(30,$lh,$maker,'',0,'');
            $this->SetFont('Arial','',12);
			$this->Cell(0,$lh,sprintf("£%.2f", $total),'',1,'R');
			$this->total[$t] = 0;
		}
        $this->maker_total = array();
    }
	
    function show_cat_total()
    {
        $lh = $this->ln_height;
        $pay_type = array('online', 'card', 'cash', 'cheque');
        $sum_total = 0;
        $this->Cell(130);
        $this->Cell(0,1,'','T',1,'R');
        for ($t = 0; $t < 4; $t++)
        {
            $this->Cell(130);
            $this->Cell(30,$lh,$pay_type[$t],'',0,'');
            $this->Cell(0,$lh,sprintf("£%.2f", $this->total[$t]),'',1,'R');
            $sum_total += $this->total[$t];
            $this->grand_total[$t] += $this->total[$t];
            $this->total[$t] = 0;
        }
        $this->Cell(130);
        $this->Cell(0,1,'','',1,'R');
        $this->Cell(130);
        $this->Cell(30,$lh,'Total','T',0,'');
        $this->Cell(0,$lh,sprintf("£%.2f", $sum_total),'T',1,'R');
    }    
    
    function show_grand_total()
    {
        $lh = $this->ln_height;
        $pay_type = array('online', 'card', 'cash', 'cheque');
        $sum_total = 0;
        $this->Cell(130);
        $this->Cell(0,1,'','T',1,'R');
        for ($t = 0; $t < 4; $t++)
        {
            $this->Cell(130);
            $this->Cell(30,$lh,$pay_type[$t],'',0,'');
            $this->Cell(0,$lh,sprintf("£%.2f", $this->grand_total[$t]),'',1,'R');
            $sum_total += $this->grand_total[$t];
            $this->total[$t] = 0;
        }
        $this->Cell(130);
        $this->Cell(0,1,'','',1,'R');
        $this->Cell(130);
        $this->Cell(30,$lh,'Total','T',0,'');
        $this->Cell(0,$lh,sprintf("£%.2f", $sum_total),'T',1,'R');
    }    
    
    function show_heading($heading)
    {
        $this->SetFont('Arial','B',12);
        $ypos = $this->GetY();
        if ($ypos > 260)
            $this->AddPage();            
        $this->Cell(35, $report->ln_height, $heading, '', 1);    
        $this->Ln(6.5);

    }
    
	//Page footer
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    //$this->SetY(-15);
	    //Arial italic 8
	    //$this->SetFont('Arial','I',8);
	    //Page number
		
	    //$this->Cell(0,10,'Page '.$this->cur_page.'/'.$this->customer_pages,0,0,'C');
		
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
$report = new Sales_Report_PDF();
$report->report_title = 'Sales Report ';
$report->report_title .= ($detailed > 0) ? '(detailed)' : '(summary)'; 
$report->SetFont('Times','',12);
$lh = $report->ln_height;

if ($maker_id) 
{
    $maker_name = db_get_single_value("select name from shop_maker where id = $maker_id", 'name');
    $report->subtitle = "Sales for $maker_name in period ";
}
else
   $report->subtitle = "Sales in period ";


$report->subtitle .= ' '. date('jS M Y', $start_date) . ' to ' . date('jS M Y', $end_date);

$report->AliasNbPages();    
    
    



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

$report->SetFillColor(245,245,245);

$tender_types = array('online', 'card', 'cash', 'cheque');
$report->AddPage();
while ($category_row = mysql_fetch_array($category_result)) 
{
    $report->show_heading($category_row['name']);

    $category_id = $category_row['id'];
    
    $item_sql = "select p.name, ccd.name as 'colourname', oi.quantity, unix_timestamp(o.time_made) as `date`, ".
        'tender_type as tender, quantity * price as row_price, m.name as maker_name '.
        'from shop_product p '.
        'join shop_maker m on m.id = maker_id '.
        'join shop_order_item oi on oi.product_id = p.id '.
		'join colour_colour_details ccd on oi.colour_id = ccd.id '.
        'join shop_order o on o.id = oi.order_id '.
        "where primary_category_id = $category_id and o.time_made between '".
        date('Y-m-d', $start_date)."' and date_add('".date('Y-m-d', $end_date)."', INTERVAL 1 DAY) ";
    if ($maker_id > 0)
        $item_sql .= ' and m.id = '.$maker_id;

    $item_result = mysql_query($item_sql);

    $fill = 0;
    
    $report->SetFont('Arial','',12);
    
    if (mysql_num_rows($item_result) == 0)
    {
        $report->Cell(35, $report->ln_height, 'no sales', '', 1);    
        $report->Ln(6.5);
    
    }
    else
    {
        while ($item_row = mysql_fetch_array($item_result))
        {
            $row_num++;
            
            $report->maker_total[$item_row['maker_name']] += $item_row['row_price'];   
            $report->total[$item_row['tender']] += $item_row['row_price'];  
            if ($detailed)
            {
                $tender_name =  $tender_types[$item_row['tender']];
				$orderProductName = "{$item_row['name']} ({$item_row['colourname']})";
                $report->Cell(35,$lh,date('d/m/y h:i', $item_row['date']),0,0,'',$fill);
                //$report->Cell(20,$lh,$tender_name,0,0,'',$fill);
                $report->Cell(75,$lh,$orderProductName,0,0,'',$fill);
                $report->Cell(20,$lh,$item_row['quantity'],0,0,'',$fill);
                $report->Cell(0,$lh,sprintf("£%.2f", $item_row['row_price']),0,0,'R',$fill);
                           
                $fill = 1 - $fill;
                $report->Ln(6.5);
            }
        }
        //$report->show_maker_total();    
        $report->show_cat_total();
        $report->Ln(6.5);
    }
}	
$report->Cell(0,$lh,'','B');
$report->Ln(1);
$report->Cell(0,$lh,'','B');
$report->Ln(10.5);
$report->show_heading('Grand Total');    
$report->SetFont('Arial','',12);
$report->show_grand_total();

$report->Output();
?>