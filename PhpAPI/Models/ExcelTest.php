<?php
namespace Models_ExcelTest;

class ExcelTest{
    public $Nombre;
    public $Fecha;
    public $LinkUrl;
    public $Nulo;

    public function __construct($nombre,$fecha,$linkurl,$nulo){
        $this->Nombre = $nombre;
        $this->Fecha = $fecha;
        $this->LinkUrl = $linkurl;
        $this->Nulo = $nulo;
    }
}