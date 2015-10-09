<?
include "./Spreadsheet/Excel/Writer.php";

$book = new Spreadsheet_Excel_Writer();
$book->setTempDir('./tmp');
$sheet = $book->addWorksheet();

$title_fmt = $book->addFormat(array('Bold'=>1, 'Merge'=>1, 'Size' => 14));
$hdr_fmt = $book->addFormat(array('Bold'=>1));
$fmt = $book->addFormat(array('NumFormat'=>'£#.00'));

$sheet->write(0,0,"Sales Report", $title_fmt);
$sheet->write(0,1,"", $title_fmt);

$sheet->write(2,1,"Product", $hdr_fmt);
$sheet->write(2,2,"Price",$hdr_fmt);

$sheet->writeString(3,1,"hello world");
$sheet->write(3,2,9.99,$fmt);
$sheet->write(4,2,9.99,$fmt);
$sheet->write(5,2,10.00,$fmt);
$book->send('test2.xls');
$book->close();
?>