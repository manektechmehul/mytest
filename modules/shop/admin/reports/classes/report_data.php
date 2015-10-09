<?php
class ReportData 
{
    var $sql;
    var $headers;
    var $csv_fields;
    function ReportData ()
    {
        $this->sql = "";
        $this->headers = "";
        $this->csv_fields = "";
    }
    function GetQuery()
    {
        return $this->sql;
    } 
    function OutputCSV($filename)
    {
        $result = mysql_query($this->sql);
        $output = $this->headers."\r\n";
        while ($row = mysql_fetch_array($result)) 
        {
            // if we have a list of fields - restrict output to that list 
            if ($this->csv_fields != "")
            {
                $filtered = array_intersect_key($row, array_fill_keys($this->csv_fields, ''));
                $output .=  implode("\t",$filtered)."\r\n";
            }
            else
                $output .=  implode("\t",$row)."\r\n";
        }
        header('Content-disposition: attachment; filename='.$filename);
        header("Content-Type: text/x-csv"); 
        echo $output;        
    }
}
?>
