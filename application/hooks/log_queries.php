<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function log_queries() 
{
	$CI =& get_instance();
	$times = $CI->db->query_times;
	foreach ($CI->db->queries as $key=>$query) {
            error_log("Query: ". $query." | ".$times[$key] );
//            log_message("debug", "Query: ". $query." | ".$times[$key] );
	}
 
	$CI->output->_display();
}