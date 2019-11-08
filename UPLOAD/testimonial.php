<?php
/* ========================================
						   _     
	 _ _ ___ ___ ___ ___  |_|___ 
	| | | -_|   | . |   |_| |  _|
	 \_/|___|_|_|___|_|_|_|_|_|  
 
Venon Web Developers, venon.ir
201508
version 2.0
=========================================*/
define("CLIENTAREA", true);
//define("FORCESSL", true); // Uncomment to force the page to use https://
 
require("init.php");
 
$ca = new WHMCS_ClientArea();

function jsoutput($value){
	$value = preg_replace("/[\r\n]*/","",$value);
	$value = addslashes($value);
    echo "document.write('".$value."');";
    exit();
}

$getnum = $_GET['num'];
settype($getnum, 'integer');
if($getnum > 0 AND $getnum < 21) {$limit=$getnum;} else {$limit=3;}

// single
	$result = mysql_query("SELECT * FROM `mod_venon_testimonial_post` WHERE `status` = 1 order by RAND() LIMIT 1");
	$data = mysql_fetch_array($result);
		
	$single['name'] = $data['name'];
	$single['message'] = nl2br($data[message], true);
	$single['date'] = fromMySQLDate($data['date']);
	$single['time'] = fromMySQLDate($data['date'],$data['date']);
	
	/* single output */
	$singleop = "$single[name]<br/>$single[message]";
	
// multiple
	$result = mysql_query("SELECT * FROM `mod_venon_testimonial_post` WHERE `status` = 1 order by RAND() LIMIT $limit");
	
	while($data = mysql_fetch_array($result)){
		$multiple[name] = $data['name'];
		$multiple[message] = nl2br($data[message], true);
		$multiple[date] = fromMySQLDate($data['date']);
		$multiple[time] = fromMySQLDate($data['date'],$data['date']);
		
		/* multiple output */
		$multipleop .= "<p>$multiple[name]-$multiple[message]</p>";
	}


if ($_GET['get'] == "single"){jsoutput($singleop);}
if ($_GET['get'] == "multiple"){jsoutput($multipleop);}

?>