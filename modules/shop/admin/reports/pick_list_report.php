<?php

require('report.php');

class Pick_List_Report_PDF extends Report_PDF {

    var $customer_name;
    var $address;
    var $delivery_instuctions;
    var $total_price;
    var $tax;

    function Pick_List_Report_PDF() {
        parent::Report_PDF();
        $this->report_title = 'Pick List Report';
    }

    //Page header
    function Header() {
        if ($this->new_customer) {
            $this->customer_pages = $this->new_customer_pages;
            $this->cur_page = 1;
            $this->new_customer = false;
        }
        else
            $this->cur_page++;
        parent::Header();

     //   $delivery_instructions = '';
        //$delivery_instructions = 'blah blah blah blah blah';
     //   $delivery_instructions = $this->delivery_instructions;

        $this->SetFont('Arial', '', 12);
        $this->Cell(40, 10, 'Customer: ', '', 0);
        $x = $this->GetX() + 40;
        $y = $this->GetY() + 2;
        $this->Cell(0, 10, $this->customer_name, '', 1);
		  
        $this->Cell(40, 6, 'Delivery Address: ', '', 0);
        $this->Cell(60, 6, '' . $this->address[0], '', 1);
        $this->Cell(40, 6, '', '', 0);
        $this->Cell(60, 6, '' . $this->address[1], '', 1);
        $this->Cell(40, 6, '', '', 0);
        $this->Cell(0, 6, '' . $this->address[2], '', 1);
        $this->Cell(40, 6, '', '', 0);
        $this->Cell(0, 6, '' . $this->address[3], '', 1);

    //    $this->Cell(40, 6, 'Goods (Inc Delivery)', '', 0);
     //   $this->Cell(0, 6, iconv("UTF-8", "ISO-8859-1", "£") . number_format($this->total_price, 2), '', 1);

        $this->Cell(40, 6, 'Order no.'  , '', 0);
        $this->Cell(0, 6, $this->id, '', 1);

        $this->Cell(40, 6, 'Total Price (inc Del)', '', 0);
        $this->Cell(0, 6, iconv("UTF-8", "ISO-8859-1", "£") . number_format($this->total_price, 2), '', 1);

        $this->Cell(40, 10, 'Page: ', '', 0);
        $pageStr = "{$this->cur_page} of {$this->customer_pages}";
        $this->Cell(0, 10, $pageStr, '', 1);
        $col_1_y = $this->GetY();
        $this->SetXY($x, $y);
        $this->Cell(0, 6, 'Notes: ', '', 1);
        $this->Cell(80, 6, '', '', 0);
       // $this->MultiCell(80, 6, $delivery_instructions, 1);
        $col_2_y = $this->GetY();
        $y = ($col_1_y > $col_2_y) ? $col_1_y : $col_2_y;

        $this->SetY($y);
        $this->Ln(6);

        $this->Cell(8, $this->ln_height, 'No.', 'B');
        $this->Cell(35, $this->ln_height, 'Stock Code', 'B', 0);
        $this->Cell(100, $this->ln_height, 'Product', 'B', 0);
        $this->Cell(10, $this->ln_height, 'Qty', 'B', 0);
        $this->Cell(10, $this->ln_height, 'Picked', 'B', 1);
        $this->Ln(6);
    }

    function show_total() {
        $lh = $this->ln_height;
        $pay_type = array('card', 'cash', 'cheque');
        $gtotal = 0;
        $this->Cell(130);
        $this->Cell(0, 1, '', 'T', 1, 'R');
        for ($t = 0; $t < 3; $t++) {
            $this->Cell(130);
            $this->Cell(30, $lh, $pay_type[$t], '', 0, '');
            $this->Cell(0, $lh, sprintf("�%.2f", $this->total[$t]), '', 1, 'R');
            $gtotal += $this->total[$t];
            $this->total[$t] = 0;
        }
        $this->Cell(130);
        $this->Cell(0, 1, '', '', 1, 'R');
        $this->Cell(130);
        $this->Cell(30, $lh, 'Total', 'T', 0, '');
        $this->Cell(0, $lh, sprintf("�%.2f", $gtotal), 'T', 1, 'R');
    }

    //Page footer
    function Footer() {
        //Position at 1.5 cm from bottom
        //$this->SetY(-15);
        //Arial italic 8
        //$this->SetFont('Arial','I',8);
        //Page number
        //$this->Cell(0,10,'Page '.$this->cur_page.'/'.$this->customer_pages,0,0,'C');
    }

}

//Instanciation of inherited class
$report = new Pick_List_Report_PDF();
$report->SetFont('Arial', '', 9);
$lh = $report->ln_height;

$sql = 'select c.*, o.* from shop_order o join shop_customer c on customer_id = c.id where o.status = 1';
$result = mysql_query($sql);
$report->AliasNbPages();

$report->cur_page = 1;
while ($row = mysql_fetch_array($result)) {
    $report->id = $row['id'];
	
    $report->customer_name = $row['firstname'] . ' ' . $row['surname'];
    $report->address = array($row['delivery_address1'], $row['delivery_address2'], $row['delivery_address3'], $row['delivery_postalcode']);
    $report->total_price = $row['total_price'];
    $report->tax = $row['tax'];
    $report->delivery_instructions = $row{'delivery_instructions'};


    $report->SetFillColor(245, 245, 245);

	

  /*  $item_sql = "SELECT CONCAT(p.name , ' - ', ccd.name) AS 'name' , p.stock_code, oi.* 
FROM shop_order_item oi JOIN shop_product p ON oi.product_id = p.id 
INNER JOIN colour_colour_details ccd ON ccd.id = oi.`colour_id`
WHERE order_id =  " . $row['id']; */

$item_sql = "SELECT CONCAT(p.name) AS 'name' , p.stock_code, oi.* 
FROM shop_order_item oi JOIN shop_product p ON oi.product_id = p.id 
WHERE order_id =  " . $row['id'];
    $item_result = mysql_query($item_sql);
    $fill = 0;
    $row_num = 0;
    $report->new_customer_pages = ceil(mysql_num_rows($item_result) / 28);
    $report->new_customer = true;
    $report->AddPage();

    $report->prev_customer_pages = $report->customer_pages;
    $report->SetFont('Arial', '', 8);
    while ($item_row = mysql_fetch_array($item_result)) {
        $row_num++;
	//	if($item_row['dc_id'] != 'N/A'){
			 $name = $item_row['description']; // . ' [' . $item_row['dc_id'] . ']';
	//	}else{
	//		 $name = '' ;
	//	}

       
        $report->Cell(8, $lh, $row_num . ' ', 0, 0, 'R', $fill);
        $report->SetFont('Arial', '', 8);
        $report->Cell(35, $lh, $item_row['stock_code'], 0, 0, '', $fill);
        $report->Cell(105, $lh, $name, 0, 0, '', $fill);
        $report->Cell(10, $lh, $item_row['quantity'], 0, 0, '', $fill);
        $report->Cell(10, 5, ' ', 1, 0, '', $fill);
        $fill = 1 - $fill;
        $report->Ln(6.5);
    }
}

$report->Output();

if($_GET['update_orders']){
	$sql = "update shop_order set `status` = 2 where `status` = 1";
	$result = mysql_query($sql);
}


?>