<?php
require('classes/report_data.php');  
class DayEndData extends ReportData
{
    function DayEndData()
    {
        if (isset($_REQUEST['report-date']))
        {
            $date = $_REQUEST['report-date'];
            $date = substr($date, 6, 4) .'-'.substr($date, 3, 2) .'-'.substr($date, 0, 2);
        }
        else
            $date = date('Y-m-d');
        $this->sql =  'select p.name, p.stock_code, oi.*, o.tender_type  '.
                'from shop_order_item oi '.
                'join shop_order o on o.id = oi.order_id  '.
                'join shop_product p on oi.product_id = p.id '.
                'where tender_type = 1 and status = 3 and date(time_made) = '."'$date'";
        $headers = array('Name', 'Stock Code', 'Quantity', 'Price', 'Vat', 'Commission');
        $this->headers = implode("\t", $headers);
        $this->csv_fields = array('name', 'stock_code', 'quantity', 'net_price', 'vat', 'commission');
    }
}
?>
