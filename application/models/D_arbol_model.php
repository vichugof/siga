<?php

/**
 * D_arbol Model
 *
 *
 * @package    	simulacioncompensacion
 * @author     	Victor Gonzalez <victor@sudo.com.co>
 * @version    	1
 */
class D_arbol_model extends CI_Model  {
    
    protected $_inclinacion_estandar;
    function __construct()
    {
        parent::__construct();
        $this->_inclinacion_estandar = 30;
    }
    
    function get_by_id($id)
    {
        $query = $this->db
                ->select('d_arbol.idarbol, d_arbol.idarbol2, b_nombrecomun.idnombrecomun, b_nombrecomun.nombrecomun, p_base, vitalidad, inclinacion, emplazamiento, alt_total, d_interferencia.descripinterferencia AS infraestructura, fact_imp_eco')
                ->join('b_nombrecomun', 'b_nombrecomun.idnombrecomun = d_arbol.nombrecomun')
                ->join('d_interferencia', 'd_interferencia.idarbol2 = d_arbol.idarbol2', 'left');
                if(is_array($id)){
//                   $query = $query->where_in('idarbol',$id);
                   $query = $query->where_in('d_arbol.idarbol2',$id);
                }else{
//                   $query = $query->where('idarbol', $id);
                   $query = $query->where('d_arbol.idarbol2', $id);
                }
                
                $query = $query->get('d_arbol');
        return $query->result();
    }
    
    function get_by_grupo_concepto($grupoconcepto_id, $codigoequivalencia){
         $query = $this->db
                ->select('conceptofactor.nombre AS conceptofactor_nombre, factor')
                ->join('conceptofactor', 'conceptofactor.grupoconceptofactor_id = grupoconceptofactor.id')
                ->where('grupoconceptofactor.id', $grupoconcepto_id)
                ->where('codigoequivalencia', $codigoequivalencia)
                ->limit(1)
                ->get('grupoconceptofactor');
        return $query->result();
    }
    
    function get_riesgo_volcamiento($inclinacion){
        return array(
            (object)array(
                    'factor' => ($inclinacion > $this->_inclinacion_estandar) ? 0 : 1,
                    'conceptofactor_nombre' => ($inclinacion > $this->_inclinacion_estandar) ? 'CON RIESGO' : 'SIN RIESGO'
            ),
        );
    }
    
   
}