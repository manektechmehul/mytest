<?php

class dataField
{
    public $fieldName;
    public $sqlField;
    public $calculated;

    function  __construct( $fieldName, $calculated = false, $sqlField = '') {
        $this->fieldName = $fieldName;
        $this->calculated = $calculated;
        if (!$calculated)
            $this->sqlField = (!empty($sqlField))? $sqlField : $fieldName;
    }

    function getData($row)
    {
        return $row[$this->fieldName];
    }
}

class linkField
{
    public $fieldName;
    public $sqlField;
    public $calculated;
    protected $url;

    function  __construct( $fieldName, $calculated = false, $sqlField, $url) {
        $this->fieldName = $fieldName;
        $this->calculated = $calculated;
        if (!$calculated)
            $this->sqlField = (!empty($sqlField))? $sqlField : $fieldName;
        $this->url = $url;
    }

    function getData($row)
    {
        return $this->url.'/'.$row[$this->sqlField];
    }
}

class newDateFlagField
{
    public $fieldName;
    public $sqlField;
    public $calculated;
    static private $currentDate;


    function  __construct($dateFieldName)
    {
        $this->calculated = true;
        $this->fieldName = $dateFieldName;
        $this->currentDate = '';
    }

    function getData($row)
    {
        $rowDate = $row[$this->fieldName];
        $isNewDate = ($this->currentDate != $rowDate);
        if ($isNewDate)
           $this->currentDate = $rowDate;
        return $isNewDate;
    }
}
