<?php

/* ========================================
						   _     
	 _ _ ___ ___ ___ ___  |_|___ 
	| | | -_|   | . |   |_| |  _|
	 \_/|___|_|_|___|_|_|_|_|_|  
 
Venon Web Developers, venon.ir
201405
version 1.2
=========================================*/

function hook_testimonial($vars) {
	// single
	$result = mysql_query("SELECT * FROM `mod_venon_testimonial_post` WHERE `status` = 1 order by RAND() LIMIT 1");
	$data = mysql_fetch_array($result);
	
	$testimonial['testimonial']['single']['name'] = $data['name'];
	$testimonial['testimonial']['single']['message'] = $data['message'];
	$testimonial['testimonial']['single']['date'] = fromMySQLDate($data['date']);
	$testimonial['testimonial']['single']['time'] = fromMySQLDate($data['date'],$data['date']);
	
	// multiple
	$result = mysql_query("SELECT * FROM `mod_venon_testimonial_post` WHERE `status` = 1 order by RAND() LIMIT 5");
	$i=1;
	while($data = mysql_fetch_array($result)){
		$testimonial['testimonial']['multiple'][$i]['name'] = $data['name'];
		$testimonial['testimonial']['multiple'][$i]['message'] = $data['message'];
		$testimonial['testimonial']['multiple'][$i]['date'] = fromMySQLDate($data['date']);
		$testimonial['testimonial']['multiple'][$i]['time'] = fromMySQLDate($data['date'],$data['date']);
		
		$i = $i+1;
	}

	return $testimonial;
}

add_hook("ClientAreaPage",1,"hook_testimonial");

?>