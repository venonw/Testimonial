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

function widget_testimonial($vars) {

	$title = 'آخرین نظرات ارسال شده';
    $content = '<table width="100%" bgcolor="#cccccc" cellspacing="1" align="center">
	<tr bgcolor="#efefef" style="text-align:center;font-weight:bold;"><td>كد</td><td>تاريخ</td><td>نام وارد شده</td><td>متن پیام</td><td>وضعيت</td><td></td></tr>';
	
	$result = mysql_query("SELECT * FROM `mod_venon_testimonial_post` ORDER BY  `mod_venon_testimonial_post`.`id` DESC LIMIT 0, 5");
	while ($data = mysql_fetch_array($result)) {
		$id = $data['id'];
		$content .= '<tr bgcolor="#ffffff""><td>'.$data['id'].'</td><td>'.fromMySQLDate($data['date'],$data['date']).'</td>';
		
		$content .= '<td>'.$data['name'].'</td>';
		
		$content .= '<td>'.mb_substr($data['message'], 0, 35,'UTF-8').'...</td>';
		
		if ($data['status']=='0') {
			$content .= '<td style="background:#d7d7d7;">معلق</td>';
		} elseif ($data['status']=='2') {
			$content .= '<td style="background:rgb(248, 129, 129);">تاييد نشد</td>';
		} elseif ($data['status']=='1') {
			$content .= '<td style="background:rgb(157, 204, 151);">تاييد شد</td>';
		}
		
		$content .= '<td><a href="addonmodules.php?module=v_testimonial&go=edit&id='. $data['id'] .'"><img src="images/edit.gif" width="16" height="16" border="0" alt="Edit" title="مشاهده نظر"></a></td>';
	}
	
	$content .= '</table><p style="text-align:center;"><a class="btn btn-small btn-default" href="addonmodules.php?module=v_testimonial&go=dashboard">نظرهای منتظر تایید</a></p>';
 
    return array('title' => $title, 'content' => $content);
}
 
add_hook("AdminHomeWidgets",1,"widget_testimonial");
?>