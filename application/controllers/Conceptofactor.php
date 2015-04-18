<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conceptofactor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
        }
        public function index(){
                $this->output('conceptofactor/conceptofactor_view',NULL);
        }
        
	public function output($view, $output = null)
	{
		$this->load->view($view,$output);
	}
        
        public function grupoconceptofactor(){
            $crud = new grocery_CRUD();
            $crud->set_model('Grocery_crud_postgresql_model');
//                $crud->set_theme('twitter-bootstrap');
            $crud->set_table('grupoconceptofactor');
            $crud->set_primary_key('id','grupoconceptofactor');
            $crud->fields('nombre', 'descripcion' );
            $crud->columns('nombre', 'descripcion');
            $crud->display_as('nombre','Nombre');
            $crud->display_as('descripcion','Descripción');
            $crud->set_rules('nombre', 'Nombre', 'required|callback_nombre_grupo_check');
            $output = $crud->render();

            $this->output('conceptofactor/conceptofactor_view',$output);
        }
        
        public function nombre_grupo_check($nombre){
            
            $id = $this->uri->segment(4);
            if(!empty($id) && is_numeric($id)){
                $nombre_old = $this->db->where("id",$id)->get('grupoconceptofactor')->row()->nombre;
                $this->db->where("nombre !=", $nombre_old);
            }
            
            $num_row = $this->db->where('nombre',$nombre)->get('grupoconceptofactor')->num_rows();
            if ($num_row >= 1){
                $this->form_validation->set_message('nombre_grupo_check', 'El nombre ya existe.');
                return FALSE;
            }else{
                return TRUE;
            }
        }
        public function concepto($operation = null)
	{
            try{
                
                $crud = new grocery_CRUD();
                $crud->set_model('Grocery_crud_postgresql_model');
//                $crud->set_theme('twitter-bootstrap');
                $crud->set_table('conceptofactor');

                $crud->fields('codigo', 'codigoequivalencia', 'nombre', 'grupoconceptofactor_id','descripcion' ,  'factor');
                $crud->columns('codigo', 'codigoequivalencia', 'nombre', 'grupoconceptofactor_id','descripcion' ,  'factor');
                $crud->display_as('codigo','Código');
                $crud->display_as('nombre','Nombre');
                $crud->display_as('descripcion','Descripción');
                $crud->display_as('factor','Factor');
                $crud->display_as('grupoconceptofactor_id','Grupo Concepto Factor');
                $crud->display_as('codigoequivalencia','Código Equivalencia');
                
                $crud->set_primary_key('id','conceptofactor');
                $crud->set_primary_key('id','grupoconceptofactor');
                $crud->set_rules('codigo', 'Código', 'required|callback_codigo_check');
//                $crud->set_rules('codigoequivalencia', 'Código Equivalencia', 'is_unique[conceptofactor.codigoequivalencia]');
                $crud->required_fields('codigo','nombre', 'grupoconceptofactor_id', 'factor');
                $crud->set_relation('grupoconceptofactor_id','grupoconceptofactor','nombre');
                
                $output = $crud->render();

                $this->output('conceptofactor/conceptofactor_view',$output);


            }catch(Exception $e){
                show_error($e->getMessage().' --- '.$e->getTraceAsString());
            }
	}
        
        public function codigo_check($codigo){
            $id = $this->uri->segment(4);
            if(!empty($id) && is_numeric($id)){
                $codigo_old = $this->db->where("id",$id)->get('conceptofactor')->row()->codigo;
                $this->db->where("codigo !=", $codigo_old);
            }
            $num_row = $this->db->where('codigo',$codigo)->get('conceptofactor')->num_rows();
            if ($num_row >= 1){
                $this->form_validation->set_message('codigo_check', 'El código ya existe.');
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        public function simulacion($operation = null)
	{
            try{
                
                $crud = new grocery_CRUD();
                $crud->set_model('Grocery_crud_postgresql_model');
//                $crud->set_theme('twitter-bootstrap');
                $crud->set_table('simulacioncompensacion');

                $crud->fields('codigo', 'idnombrecomun', 'cantidadnombrecomun', 'fecharegistro','valor');
                $crud->columns('codigo', 'idnombrecomun', 'cantidadnombrecomun', 'fecharegistro','valor');
                $crud->display_as('codigo','Código');
                $crud->display_as('idnombrecomun','Nombre Común');
                $crud->display_as('cantidadnombrecomun','Cantidad Nombre Común');
                $crud->display_as('fecharegistro','Fecha');
                $crud->display_as('valor','Valor');
                
                $crud->set_primary_key('id','simulacioncompensacion');
                $crud->set_primary_key('idnombrecomun','b_nombrecomun');
                
                $crud->required_fields('codigo', 'idnombrecomun', 'cantidadnombrecomun','valor');
                $crud->set_relation('idnombrecomun','b_nombrecomun','nombrecomun');
                $crud->unset_edit();
                $output = $crud->render();

                $this->output('conceptofactor/conceptofactor_view',$output);


            }catch(Exception $e){
                show_error($e->getMessage().' --- '.$e->getTraceAsString());
            }
	}
//        public function nombrecomun(){
//            try{
//                $crud = new grocery_CRUD();
//                $crud->set_model('Grocery_crud_postgresql_model');
//    //                $crud->set_theme('twitter-bootstrap');
//                $crud->set_table('confactornombrecomun');
//                $crud->set_primary_key('id','confactornombrecomun');
//                $crud->set_primary_key('idnombrecomun','b_nombrecomun');
//                $crud->set_primary_key('id','conceptofactor');
//
//                $crud->fields('nombrecomun_id', 'conceptofactor_id', 'descripcion');
//                $crud->columns('nombrecomun_id', 'conceptofactor_id', 'descripcion');
//                $crud->set_subject('Concepto factor por nombre comun');
//                $crud->set_relation('nombrecomun_id','b_nombrecomun','nombrecomun');
//                $crud->set_relation('conceptofactor_id','conceptofactor','nombre');
//                $crud->required_fields('conceptofactor_id','nombrecomun_id');
//
//                $crud->display_as('descripcion','Descripción');
//                $crud->display_as('conceptofactor_id','Concepto Factor');
//                $crud->display_as('nombrecomun_id','Nombre Común');
//
//                $output = $crud->render();
//
//                $this->output('conceptofactor/conceptofactor_view',$output);
//            }catch(Exception $e){
//                show_error($e->getMessage().' --- '.$e->getTraceAsString());
//            }
//        }

}