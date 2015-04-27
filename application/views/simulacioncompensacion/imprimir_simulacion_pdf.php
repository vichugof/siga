<?php

$this->pdf->selectFont(APPPATH.'third_party/pdf-php/fonts/Helvetica.afm');  //choose font, watch out for the dont location!


$column_header = $parametros['column_header'];

$options = array(
            'shadeCol'=>array(0.8,0.8,0.8),                                       
            'width'=>550,   //Ancho de la Tabla.
            'cols'=> array(
                        'valor'=>array('justification'=>'right' )
                    )
        );
$codigo .= "_".date("Y-m-d");
$encabezado = $this->pdf->openObject();
$this->pdf->addJpegFromFile(FCPATH.$parametros['logo'],250,690,100,100);
$this->pdf->closeObject();
$this->pdf->addObject($encabezado,'all');

$this->pdf->ezStartPageNumbers(585,40,8,"right", 'Página'." {PAGENUM} de {TOTALPAGENUM}");
$this->pdf->ezSetMargins(100,70,30,30);
$this->pdf->ezText($parametros['titulo'],20,array('justification'=>'center'));  //insert text with size
$this->pdf->ezText('');  //espcio entre texto
$this->pdf->ezText($parametros['header'],14,array('justification'=>'left'));  //insert text with size
$this->pdf->ezText('');  //espcio entre texto
$this->pdf->ezTable($data, $column_header,'',$options); //generate table
$this->pdf->ezText('');  //espcio entre texto
$options['showHeadings'] = 0; $options['showLines'] = 0;
$this->pdf->ezTable($data_total, $column_header,'',$options); //generate table
$this->pdf->ezText("\n");
$this->pdf->ezText($parametros['pie_pagina'],14,array('justification'=>'center'));  //insert text with size

$optionsFile = array('Content-Disposition' => "simulacion{$codigo}.pdf", 'download' => 1, 'compress' => 1);

$this->pdf->ezStream($optionsFile);
