<?php

/**
 * Simulacioncompensacion Model
 *
 *
 * @package    	simulacioncompensacion
 * @author     	Victor Gonzalez <victor@sudo.com.co>
 * @version    	1
 */
class Simulacioncompensacion_model extends CI_Model  {
    
    protected $_smdlv;
    protected $_valorIndiviualArbol;
    protected $_rangosFAT;
    protected $_rangosFDAP;
    protected $_saltSimulacion;
    
    private $_cache_fact_imp_eco;
    private $_cache_emplazamiento;
    private $_cache_vitalidad;
    private $_cache_infraestructura;
    
    
    private $_grupo_fact_imp_eco;
    private $_grupo_emplazamiento;
    private $_grupo_vitalidad;
    private $_grupo_infraestructura;
    
    function __construct()
    {
        parent::__construct();
        $this->inicializar();
    }
    
    public function get_salt_simulacion(){
        return $this->_saltSimulacion;
    }
    
    public function get_valor_indiviual_arbol(){
        return $this->_valorIndiviualArbol;
    }
    
    public function get_smdlv(){
        return $this->_smdlv;
    }
    
    private function inicializar(){
        $this->_smdlv = 24640;
        $this->_valorIndiviualArbol = 93600;
        $this->_saltSimulacion = 106;
        $this->_rangosFAT[4] = 2;
        $this->_rangosFAT[6] = 4;
        $this->_rangosFAT[8] = 6;
        $this->_rangosFAT[10] = 8;
        $this->_rangosFAT[12] = 10;
        $this->_rangosFAT[18] = 12;
        
        $this->_rangosFDAP[1] = 2;
        $this->_rangosFDAP[25] = 3;
        $this->_rangosFDAP[50] = 4;
        $this->_rangosFDAP[75] = 5;
        $this->_rangosFDAP[100] = 6;
        $this->_rangosFDAP[125] = 7;
        $this->_rangosFDAP[150] = 8;
        $this->_rangosFDAP[175] = 9;
        $this->_rangosFDAP[200] = 10;
//        $this->_rangosFDAP[0] = 0;
        
        $this->_cache_fact_imp_eco = array();
        $this->_cache_emplazamiento = array();
        $this->_cache_vitalidad = array();
        $this->_cache_infraestructura = array();
        
        $this->_grupo_fact_imp_eco = 1;
        $this->_grupo_emplazamiento = 2;
        $this->_grupo_vitalidad = 3;
        $this->_grupo_infraestructura = 4;

    }
    
     public function calcularsimarbol(stdClass $arbol){
        $equivalencia = null;
        foreach($this->_rangosFAT as $coutaInferior => $equivalencia){
            if((int)$coutaInferior >= (int)$arbol->alt_total){
//                error_log( "calcularsimarbol = ".(int)$coutaInferior." - ".(int)$arbol->alt_total);
                $arbol->factorAlturaTotal= $equivalencia;
                break;
            };
        }
        if(!isset($arbol->factorAlturaTotal) && $equivalencia !== null){
            $arbol->factorAlturaTotal = $equivalencia;
        }
        
        $equivalencia = null;
        foreach($this->_rangosFDAP as $coutaInferior => $equivalencia){
//            echo "coutaInferior: ".$coutaInferior. " , "." p_altpechogrueso ".(float)$arbol->p_altpechogrueso;
            if((int)$coutaInferior < (float)$arbol->p_base){
                $arbol->factorDiametro = $equivalencia;
                break;
            };
        }
        if(!isset($arbol->factorDiametro) && $equivalencia !== null){
            $arbol->factorDiametro = 0;
        }
        $arbol->valoresFactores[] = $arbol->factorDiametro;
        $arbol->valoresFactores[] = $arbol->factorAlturaTotal;
        $concepto=array();
        //FIEoP ecológica registrada
        if(isset($this->_cache_fact_imp_eco[$this->_grupo_fact_imp_eco.$arbol->fact_imp_eco])){
            $concepto = $this->_cache_fact_imp_eco[$this->_grupo_fact_imp_eco.$arbol->fact_imp_eco];
        }else{
            $concepto = $this->darbol->get_by_grupo_concepto($this->_grupo_fact_imp_eco, $arbol->fact_imp_eco);
            $this->_cache_fact_imp_eco[$this->_grupo_fact_imp_eco.$arbol->fact_imp_eco]=$concepto;
        }
        $arbol = $this->procesarArregloFactor($concepto, $arbol, 'fact_imp_eco');
        
        //"L. registrada"
        if(isset($this->_cache_emplazamiento[$this->_grupo_emplazamiento.$arbol->emplazamiento])){
            $concepto = $this->_cache_emplazamiento[$this->_grupo_emplazamiento.$arbol->emplazamiento];
        }else{
            $concepto = $this->darbol->get_by_grupo_concepto($this->_grupo_emplazamiento, $arbol->emplazamiento);
            $this->_cache_emplazamiento[$this->_grupo_emplazamiento.$arbol->emplazamiento]=$concepto;
        }
        $arbol = $this->procesarArregloFactor($concepto, $arbol, 'emplazamiento');
        
        //Estado fitosanitario 
        if(isset($this->_cache_vitalidad[$this->_grupo_vitalidad.$arbol->vitalidad])){
            $concepto = $this->_cache_vitalidad[$this->_grupo_vitalidad.$arbol->vitalidad];
        }else{
            $concepto = $this->darbol->get_by_grupo_concepto($this->_grupo_vitalidad, $arbol->vitalidad);
            $this->_cache_vitalidad[$this->_grupo_vitalidad.$arbol->vitalidad]=$concepto;
        }
        $arbol = $this->procesarArregloFactor($concepto, $arbol, 'vitalidad');
        
        //Riesgo de Volcamiento
        $arbol = $this->procesarArregloFactor($this->darbol->get_riesgo_volcamiento($arbol->inclinacion), $arbol, 'inclinacion');
        
        //Afectacion a infraestructura
        if(isset($this->_cache_infraestructura[$this->_grupo_infraestructura.$arbol->infraestructura])){
            $concepto = $this->_cache_infraestructura[$this->_grupo_infraestructura.$arbol->infraestructura];
        }else{
            $concepto = $this->darbol->get_by_grupo_concepto($this->_grupo_infraestructura, $arbol->infraestructura);
            $this->_cache_infraestructura[$this->_grupo_infraestructura.$arbol->infraestructura]=$concepto;
        }
        $arbol = $this->procesarArregloFactor($concepto, $arbol, 'infraestructura');
        
        $arbol->totalCompensacion = $this->calcularValorCompensacion($arbol->valoresFactores);
        $arbol->totalArboles = ceil($arbol->totalCompensacion/$this->_valorIndiviualArbol);
        return $arbol;
    }
    
    private function calcularValorCompensacion(Array $factores){
        $totalCompensacion = 1;
        foreach ($factores as $factor){
            $totalCompensacion = $totalCompensacion*(float)$factor;
        }
        $totalCompensacion = $totalCompensacion*$this->_smdlv;
        return $totalCompensacion;
    }
    
    private function procesarArregloFactor(Array $arrayResult, stdClass $arbol, $nombre){

        if(count($arrayResult) > 0){
            $result = reset($arrayResult);
            $factor = 'factor'.$nombre;
            $factorNombre = 'factor'.$nombre.'Nombre';
            $arbol->$factor = $result->factor;
            $arbol->$factorNombre = $result->conceptofactor_nombre;
            $arbol->valoresFactores[] = $result->factor;
        }
        return $arbol;
    }
    
    public function get_simulacion_por_nombrecomun($arboles){
        $simulacionCompensacion = array();
        foreach ($arboles as $arbol){
            if(isset($simulacionCompensacion[trim($arbol->idnombrecomun)])){
                $simulacionCompensacion[trim($arbol->idnombrecomun)]['totalCompensacion'] += $arbol->totalCompensacion;
                $simulacionCompensacion[trim($arbol->idnombrecomun)]['numArboles'] += 1;
            }else{
                $simulacionCompensacion[trim($arbol->idnombrecomun)]['totalCompensacion'] = $arbol->totalCompensacion;
                $simulacionCompensacion[trim($arbol->idnombrecomun)]['numArboles'] = 1;
                $simulacionCompensacion[trim($arbol->idnombrecomun)]['nombrecomun'] = $arbol->nombrecomun;
            }
        }
        return $simulacionCompensacion;
    }
    
    public function calcular_simulacion_final($simulacionCompensacion, $codigo){
        $rows = array();
        
//        $codigo = $this->simulacion->get_current_serial();
        if($codigo === FALSE){
            throw new Exception('No se puede acceder a la secuencia de la tabla simulacioncompensacion');
        }
        
        foreach($simulacionCompensacion as $idnombrecomun => $valores){
            $rows [] = array(
//               'codigo' => $codigo+$this->_saltSimulacion,
               'codigo' => $codigo,
               'idnombrecomun' => $idnombrecomun,
               'cantidadnombrecomun' => $valores['numArboles'],
               'valor' => $valores['totalCompensacion']
            );
        }
        return $rows;
    }


    public function save($data){
        if(is_array($data) && !empty($data))
            return $this->db->insert_batch('simulacioncompensacion', $data); 
        
        return FALSE;
    }
    
    public function get_current_serial(){
        $stmt = "select MAX(codigo) AS last_value from simulacioncompensacion";
        $last_record = $this->db->query($stmt);
        
        if ($last_record->num_rows() > 0){
           $row = $last_record->row();
        
//           log_message('debug', 'Last generated ID: '.$row->last_value);
           if($row->last_value === NULL) return $this->_saltSimulacion;
           
           return $row->last_value+1;
        }
        return FALSE;
    }
    
    public function get_by_codigo($codigo){
        if($codigo !== ''){
            $query = $this->db
                    ->select('simulacioncompensacion.codigo, '
                            . 'b_nombrecomun.nombrecomun,'
                            . 'simulacioncompensacion.cantidadnombrecomun, '
                            . 'simulacioncompensacion.fecharegistro, '
                            . 'simulacioncompensacion.valor')
                    ->join('b_nombrecomun', 'b_nombrecomun.idnombrecomun = simulacioncompensacion.idnombrecomun')
                    ->where('simulacioncompensacion.codigo', $codigo)
                    ->get('simulacioncompensacion');
            return $query->result();    
        }
        return FALSE;
    }
}