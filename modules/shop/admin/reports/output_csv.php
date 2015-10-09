<?php
    include "../../../../php/databaseconnection.php";
    $report = $_GET['report'];
    //echo "$report";
    switch ($report)
    {
        case 'sales':
            require('classes/sales_report_data.php');
            $data = new SalesReportData();
            break;
        case 'day_end':
            require('classes/day_end_data.php');
            $data = new DayEndData();
            break;
        case 'pick_list':
            require('classes/pick_list_data.php');
            $data = new PickListData();
            break;
        case 'over_age':
            require('classes/over_age_data.php');
            $data = new OverAgeData();
            break;
    }
    if ($data)
        $data->OutputCSV($report.".csv");
?>
