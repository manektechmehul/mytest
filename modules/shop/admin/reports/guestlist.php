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
			// look up event name
			$event_title = db_get_single_value("SELECT title FROM booking WHERE id =" . $_GET['parent_id']);
			$this->report_title = $event_title . ' : Guest List';
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




		}

		function new_customer_out(){
			$this->Ln(6);
			$this->SetFont('Arial', '', 11);
			$this->Cell(30, 5, 'Customer: ', '', 0);
			$x = $this->GetX() + 40;
			$y = $this->GetY() + 2;
			$this->Cell(0, 5, $this->customer_name, '', 1);
			$this->Cell(30, 5, 'Order no:'  , '', 0);
			$this->Cell(0, 5, $this->id, '', 1);
			$this->SetXY($x, $y);
			$col_2_y = $this->GetY();
			$y = ($col_1_y > $col_2_y) ? $col_1_y : $col_2_y;
			$this->SetY($y);
			$this->Ln(8);
			$this->Cell(30, $this->ln_height, 'Code ', 'B');
			$this->Cell(125, $this->ln_height, 'Ticket Description', 'B', 0);
			$this->Cell(10, $this->ln_height, 'Qty', 'B', 0);
			$this->Cell(30, $this->ln_height, 'Arrived', 'B', 1);
			$this->Ln(1);
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
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,'Page '.$this->cur_page ,0,0,'C');
		}

	}

	//Instanciation of inherited class
	$report = new Pick_List_Report_PDF();
	$report->SetFont('Arial', '', 9);
	$lh = $report->ln_height;
    $event_id = $_GET['parent_id'];

/* filter to just event tickets
	$sql = "SELECT DISTINCT so.id AS order_id, sc.`firstname`, sc.surname
FROM shop_order so
INNER JOIN shop_order_item soi ON soi.`order_id` = so.id
INNER JOIN shop_customer sc ON so.`customer_id` = sc.id
INNER JOIN booking_ticket bt ON bt.id = soi.`product_id`
WHERE so.`status`=1 AND product_type = 2 AND soi.product_sub_type = 1 AND bt.`booking_id` = " . $event_id . " order by  sc.surname, order_id ";
*/

	$sql = "SELECT DISTINCT so.id AS order_id, sc.`firstname`, sc.surname
FROM shop_order so
INNER JOIN shop_order_item soi ON soi.`order_id` = so.id
INNER JOIN shop_customer sc ON so.`customer_id` = sc.id
INNER JOIN booking_ticket bt ON bt.id = soi.`product_id`
WHERE so.`status`=1 AND bt.`booking_id` = " . $event_id . " order by  sc.surname, order_id ";

	$result = mysql_query($sql);
/*
	$report->AliasNbPages();
	$report->cur_page = 1;
*/
	$report->AddPage();
	$row_num = 0;

	while ($row = mysql_fetch_array($result)) {
		$report->id = $row['order_id'];
		$report->customer_name = $row['firstname'] . ' ' . $row['surname'];
		$report->SetFillColor(245, 245, 245);
	/*	$item_sql = "SELECT bt.id as product_id, bt.title AS description, soi.quantity FROM shop_order so
INNER JOIN shop_order_item soi ON soi.`order_id` = so.id
INNER JOIN booking_ticket bt ON bt.id = soi.`product_id`
WHERE  soi.`product_type` = 2    AND so.id = " . $row['order_id'] .  " and bt.`booking_id`=" . $event_id; */

		$item_sql = "SELECT bt.id AS product_id,
CASE WHEN soi.product_sub_type = 1 THEN CONCAT('Ticket::', bt.title, ' £',bt.price)
ELSE CONCAT('Product::', bp.title,' £', bp.price)  END AS description
, soi.quantity
FROM shop_order so
INNER JOIN shop_order_item soi ON soi.`order_id` = so.id
INNER JOIN booking_ticket bt ON bt.id = soi.`product_id`
INNER JOIN booking_product bp ON bp.id = soi.`product_id`
WHERE soi.`product_type` = 2
AND so.id = " . $row['order_id'] .  " and bt.`booking_id`=" . $event_id;



		$item_result = mysql_query($item_sql);
		$fill = 0;

		// need to make this smarter

	//	$report->new_customer_pages = ceil(mysql_num_rows($item_result) / 28);
	//	$report->new_customer = true;

		$lines_per_page = 48;

		if($row_num > ($lines_per_page - 7)){
			// start a new page
			$report->AddPage();
			$row_num = 0; // reset counter
		}

		$report->new_customer_out();
		$row_num = $row_num + 6;
	//	$report->prev_customer_pages = $report->customer_pages;
	//	$report->SetFont('Arial', '', 8);


		// output each ticket to the grid
		while ($item_row = mysql_fetch_array($item_result)) {

			if($row_num > $lines_per_page){
				// start a new page
				$report->AddPage();
				$row_num = 0; // reset counter
			}

			$row_num++;
			$report->SetFont('Arial', '', 10);
			$name =  str_replace("£",iconv("UTF-8", "ISO-8859-1", "£"), trim($item_row['description']) );
			$name =  str_replace("&pound; ",iconv("UTF-8", "ISO-8859-1", "£"), trim($name) );
			$report->Cell(8, $lh,  $item_row['product_id']  , 0, 0, 'R', $fill);
			$report->Cell(22, $lh,  ""  , 0, 0, 'R', $fill);
			$report->Cell(128, $lh, $name, 0, 0, '', $fill);
			$report->Cell(10, $lh, $item_row['quantity'], 0, 0, '', $fill);
			$report->Cell(5, 5, ' ', 1, 0, '', $fill);
			$fill = 1 - $fill;
			$report->Ln(6.5);
		}




	}

	$report->Output();




?>