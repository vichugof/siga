<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simulacioncompensacion extends CI_Controller{

    public function __construct()
    {
            parent::__construct();

            $this->load->database();
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('html');
            
            $this->load->model('d_arbol_model', 'darbol');
            $this->load->model('Simulacioncompensacion_model', 'simulacion');
    }
    

    public function output($view, $output = null)
    {
            $this->load->view($view,$output);
    }
        
    public function index(){
        $data = array(
            'valorIndiviualArbol' =>   $this->simulacion->get_valor_indiviual_arbol(),
            'smdlv'               =>   $this->simulacion->get_smdlv(),
        );
        $this->output('simulacioncompensacion/simulacioncompensacion_view', $data);
    }
    
    public function consultar(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $idArbol = (isset($request->idsArbol)) ? $request->idsArbol : NULL;

        if($idArbol !== '' && $idArbol !== NULL){

            $idsArboles = explode(',', $idArbol);
            if(count($idsArboles) > 0){
                $idsArboles=array_map('trim',$idsArboles);
                $idArbol = $idsArboles;
            }
            
            /*
             * Si la cadena de texto termina en coma y se crear un valor null de último
             */
            if($idArbol[count($idArbol)-1] === NULL || $idArbol[count($idArbol)-1] === ''){
                unset($idArbol[count($idArbol)-1]);
            }
            
            /*
             * Validar que los ids sean de tipo integer
             */
            if(in_array(false, array_map(function($id){return is_numeric($id);}, $idArbol))){
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(array(
                        'text' => 'Error, los criterios de búsqueda deben ser de tipo númerico.',
                        'type' => 'danger'
                    )));
            }
            
            
            $result = $this->darbol->get_by_id($idArbol);
            if(count($result) > 0){

                $resultCompensacion = array();
                foreach ($result as $arbol){
                    $resultCompensacion[] = $this->simulacion->calcularsimarbol($arbol);
                }
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => TRUE,
                        'text' => 'Arbol encontrado',
                        'response' => ($resultCompensacion),
                        'type' => 'success'
                    )));
            }
        }
            
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode(array(
                'text' => 'Error, no se enviaron parámetros o el árbol no se encontró.',
                'type' => 'danger'
            )));
        
    }
    
    public function calcular(){
        
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
//        print_r($request);
        try{
            if(count($request) > 0 && reset($request) !== null){
                $simulacionCompensacion = array();
                
                foreach($request as $arboles){
                    $simulacionCompensacion = $this->simulacion->get_simulacion_por_nombrecomun($arboles);
                }
                if(count($simulacionCompensacion) > 0){
                    $rows = array();
                    $codigo = $this->simulacion->get_current_serial();
                    $rows =  $this->simulacion->calcular_simulacion_final($simulacionCompensacion, $codigo);
                    $responde = $this->simulacion->save($rows);
                    
                    if($responde !== FALSE){
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array(
                                'success'       => TRUE,
                                'text'          => 'Simulación realizada',
                                'response'      => $simulacionCompensacion,
                                'silumacion'    => $codigo,
                                'fecha'         => date('Y-m-d'),
                                'type'          => 'success'
                            )));
                    }
                    
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(array(
                            'success' => TRUE,
                            'text' => 'Simulación no realizada',
                            'type' => 'error'
                        )));
                }
            }
           
        }catch(Exception $e){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(array(
                        'text' => 'Error 500, no se calculó la simulación'.$e->getMessage().' --- '.$e->getTraceAsString(),
                        'type' => 'danger'
                )));
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode(array(
                    'text' => 'Error, no se enviaron parámetros',
                    'type' => 'danger'
            )));
        
    }
    
    public function imprimir_simulacion($codigo){
        $parametros = $this->get_arreglo_configuracion_pdf();
        $this->load->library('pdf', $parametros['parameters']); 
        $simulaciones = $this->simulacion->get_by_codigo($codigo);
        if(count($simulaciones) > 0){
            $dataTable = array();
            $total = 0;
            foreach($simulaciones as $simulacion){
                $dataTable[] = array(
                    'codigo'                => $simulacion->codigo,
                    'nombrecomun'           => $simulacion->nombrecomun,
                    'cantidadnombrecomun'   => $simulacion->cantidadnombrecomun,
                    'fecharegistro'         => date('Y-d-m', strtotime($simulacion->fecharegistro)),
                    'valor'                 => number_format($simulacion->valor),
                );
                $total += $simulacion->valor;
            }
            
           $this->load->view('simulacioncompensacion/imprimir_simulacion_pdf', 
                array(   
                    'data' => $dataTable, 
                    'dataTotal'=> array(
                                        0 => array( 'fecharegistro' => '<b>TOTAL</b>', 
                                                    'valor'         => '<b>'.number_format($total).'</b>'
                                                )
                                        ),
                    'codigo' => $codigo, 
                    'parametros' => $parametros
                )
            );
           return;
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode(array(
                    'text' => "Error, no se encontró la simulación con el código {$codigo}",
                    'type' => 'danger'
            )));
    }
    
    private function get_arreglo_configuracion_pdf(){
        
        $piePagina = 'Este proceso es una simulación no definitiva y no constituye un recibo de pago u obligación, su propósito es orientar acerca de los costos posibles para un proyecto que amerite compensacion silvicultural , para mayor información remitirse a las oficinas del DAGMA con este documento.';
        $titulo = 'Simulación costo compensación silvicultural';
        $header = 'El resultado de su consulta es:';
        $logo = 'assets/img/siga114.jpg';

        $parameters=array(
            'paper'         =>'letter',   //paper size
            'orientation'   =>'portrait',  //portrait or lanscape
            'type'          =>'color',   //paper type: none|color|colour|image
            'options'       =>array(1, 1, 1) //I specified the paper as color paper, so, here's the paper color (RGB)
        );
        $columnHeader=array(
            'codigo'                =>'<b>Código</b>',
            'nombrecomun'           =>'<b>Nombre Común</b>',
            'cantidadnombrecomun'   =>'<b>Cantidad</b>',
            'fecharegistro'         =>'<b>Fecha de Registro</b>',
            'valor'                 =>'<b>Valor</b>',
        );
        return array(
            'parameters'    => $parameters,
            'columnHeader'  => $columnHeader,
            'titulo'        => $titulo,
            'header'        => $header,
            'logo'          => $logo,
            'piePagina'     => $piePagina,
        );
        
        
    }
}

