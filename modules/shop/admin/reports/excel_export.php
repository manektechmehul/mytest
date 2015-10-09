<?php
require "./Spreadsheet/Excel/Writer.php";
$base_path = $_SERVER['DOCUMENT_ROOT'];
include $base_path.'/php/databaseconnection.php';

class Excel_Export
{
    var $book;
    var $sheet;
    var $title_fmt;
    var $hdr_fmt;
    var $money_fmt;
    var $date_fmt;
    var $current_line;
    
    function Excel_Export($title)
    {
        $this->book = new spreadsheet_Excel_Writer();
        $this->book->setTempDir('./tmp');
        $this->sheet = $this->book->addWorksheet($title);

        $this->title_fmt = $this->book->addFormat(array('Bold'=>1, 'Merge'=>1, 'Size' => 14));
        $this->hdr_fmt = $this->book->addFormat(array('Bold'=>1));
        $this->money_fmt = $this->book->addFormat(array('NumFormat'=>'£#.00'));
        $this->total_fmt = $this->book->addFormat(array('Bold'=>0, 'Top'=>1));
        $this->money_total_fmt = $this->book->addFormat(array('NumFormat'=>'£#.00', 'Bold'=>0, 'Top'=>1));
        $this->date_fmt = $this->book->addFormat(array('NumFormat'=>'DD/MM/YY HH:MM'));
        $this->current_line = 0;
    }
    
    function Header($report_title)
    {
        $this->sheet->write(0,0,'Report date: '.date('jS F Y'));
        $this->sheet->write(1,0,$report_title, $this->title_fmt);
        $this->sheet->write(1,1,"", $this->title_fmt);
        $this->sheet->write(1,2,"", $this->title_fmt);
        $this->sheet->write(1,3,"", $this->title_fmt);
        $this->sheet->write(1,4,"", $this->title_fmt);
        $this->current_line = 3;
    }
    
    function Output ($filename)
    {
        $this->book->send($filename);    
        $this->book->close();
    }
}

?>