<?php

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

/* ========================================
						   _
	 _ _ ___ ___ ___ ___  |_|___
	| | | -_|   | . |   |_| |  _|
	 \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201508
version 3.0
=========================================*/

function v_testimonial_config() {
    $configarray = array(
    "name" => "Venon Testimonial",
    "description" => "Whmcs Testimonial",
    "version" => "3.0",
    "author" => "Venon Web Developers. Venon.ir",
    "language" => "farsi",
    "fields" => array(
        //"License" => array ("FriendlyName" => "License", "Type" => "text", "Size" => "25", "Description" => "Enter your venon testimonial License number", "Default" => "venon-", ),
    ));
    return $configarray;
}

function v_testimonial_activate() {

    # Create Custom DB Table
    $query = "CREATE TABLE IF NOT EXISTS `mod_venon_testimonial` (
	  `setting` text NOT NULL,
	  `enable` int(11) NOT NULL,
	  `clientonly` int(11) NOT NULL,
	  `perpage` int(11) NOT NULL,
	  `status` text,
	  `localkey` text
	)";
	$result = mysql_query($query);

	$query = "CREATE TABLE IF NOT EXISTS `mod_venon_testimonial_post` (
		  `id` int(1) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) DEFAULT NULL,
		  `name` text CHARACTER SET utf8 COLLATE utf8_bin,
		  `message` text CHARACTER SET utf8 COLLATE utf8_bin,
		  `status` int(11) NOT NULL,
		  `date` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		);";
	$result = mysql_query($query);

	# Insert options
	$query = "INSERT INTO  `mod_venon_testimonial` (`setting` ,`enable` ,`clientonly` , `perpage`, `status` ,`localkey`) VALUES ('option', '1', '0', '5', NULL , NULL);";
	$result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'Venon testimonial addon successfully installed');
    return array('status'=>'error','description'=>'Error: For further assistance, please contact us at venon.ir');
    return array('status'=>'info','description'=>'Select your options below');

}

function v_testimonial_deactivate() {

    # Remove Custom DB Table
  //   $query = "DROP TABLE `mod_venon_testimonial`, `mod_venon_testimonial_post`;";
	// $result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'Venon testimonial addon successfully uninstalled');
    return array('status'=>'error','description'=>'Error: For further assistance, please contact us at venon.ir');
    return array('status'=>'info','description'=>'');

}

function v_testimonial_upgrade($vars) {
    $version = $vars['version'];
    # Run SQL Updates for V1.0 to V1.1
}

function v_testimonial_output($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $LANG = $vars['_lang'];


	echo '<div class="contexthelp"><a href="http://venon.ir" target="_blank"><img src="images/icons/help.png" border="0" align="absmiddle">پشتیبانی</a></div>';

    echo '<p>ماژول دريافت و نمايش نظر مشتريان ونون به شما اين امکان را مي دهد که ارتباط بيشتري با مشتريان خود داشته باشيد و از نظرات مشتريان در رابطه با سرويس و خدمات خود آگاه شويد.</p>';

	// tabs
	echo '<div id="clienttabs">
			<ul class="nav nav-tabs admin-tabs">
				<li class="'; if ($_GET['go']=="dashboard" or empty($_GET['go'])){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_testimonial&go=dashboard">نظرات منتظر تایید</a></li>
				<li class="'; if ($_GET['go']=="manage" or $_GET['go']=="edit"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_testimonial&go=manage">آرشیو نظرات</a></li>
				<li class="'; if ($_GET['go']=="add"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_testimonial&go=add">افزودن نظر جدید</a></li>
				<li class="'; if ($_GET['go']=="setting"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_testimonial&go=setting">تنظیمات</a></li>
				<li class="'; if ($_GET['go']=="help"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_testimonial&go=help">راهنما و کدها</a></li>
			</ul>
		</div>
		<div id="tab0box" class="tabbox tab-content admin-tabs">
			<div id="tab_content" class="tab-pane active" style="text-align:right;">';
	// tabscontent

	// dashboard
	if ($_GET['go']=="dashboard" or empty($_GET['go'])) {
		// remove post
		if (isset ($_GET[remove])) {
			mysql_query("DELETE FROM `mod_venon_testimonial_post` WHERE id=$_GET[remove]");
			echo '<div class="infobox">نظر مورد نظر با موفقیت حذف شد</div>';
		}
		// approve post
		if (isset ($_GET['approve'])) {
			$update = array("status"=>1);
			$where = array("id"=>"$_GET[approve]");
			update_query('mod_venon_testimonial_post',$update,$where);
			echo '<div class="infobox">نظر مورد نظر با موفقیت تایید شد</div>';
		}
		echo '<table width="100%">
				<tbody>
					<tr>
						<td>
							<table width="100%" class="form">
							<tbody><tr><td colspan="2" class="fieldarea" style="text-align:center;"><strong>لیست نظرهای منتظر تایید</strong></td></tr>
							<tr><td align="center">
							<div class="tablebg">
							<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
							<tbody><tr><th>کد</th><th>تاریخ</th><th>نام وارد شده</th><th>مشتری مرتبط</th><th>متن پیام</th><th>وضعیت</th><th width="20"></th><th width="20"></th><th width="20"></th></tr>';
							$table = 'mod_venon_testimonial_post';
							$fields = "id, uid, name, message, status, date";
							$sort = "id";
							$sortorder = "DESC";
							$limits = "";
							$where = array(status => 0);
							$result = select_query($table,$fields,$where,$sort,$sortorder,$limits);

							while ($data = mysql_fetch_array($result)) {
							echo '<tr><td><a href="#" target="_blank">'.$data['id'] .'</a></td>
								<td>'. fromMySQLDate($data['date'],$data['date']) .'</td>
								<td>'.$data['name'] .'</td>
								<td>';

							//user list
							$username = select_query('tblclients','firstname,lastname',array('id'=>$data[uid]));
							$userfetch = mysql_fetch_array($username);
							$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];
							echo '<a href="clientssummary.php?userid='.$data['uid'].'" target="_blank">'. $userfullname .'</a></td>
								<td><textarea style="width:500px; height:100px;">'. $data['message'] .'</textarea></td>
								<td>'; if($data['status'] == 0) {echo 'معلق';}
								elseif ($data['status'] == 1) {echo 'تایید شده';}
								elseif ($data['status'] == 2) {echo 'تایید نشده';}
								elseif ($data['status'] == 3) {echo 'حذف شده';}
								echo'</td>
								<td><a href="addonmodules.php?module=v_testimonial&go=dashboard&approve='. $data['id'] .'"><img src="images/icons/tick.png" width="16" height="16" border="0" alt="تایید" title="تایید نظر"></a></td>
								<td><a href="addonmodules.php?module=v_testimonial&go=edit&id='. $data['id'] .'"><img src="images/edit.gif" width="16" height="16" border="0" alt="ویرایش" title="ویرایش"></a></td><td><a href="addonmodules.php?module=v_testimonial&go=dashboard&remove='. $data['id'] .'"><img src="images/icons/accessdenied.png" width="16" height="16" border="0" alt="حذف" title="حذف نظر"></a></td></tr>'
								;}

							$num_rows = mysql_num_rows($result);
							if ($num_rows == 0) {
							echo '<tr><td colspan="9">'.$LANG['nothingfound'].'</td></tr>';}
							echo'</tbody></table>
							</div>
							</td></tr></tbody></table>
						</td>
					</tr>
				</tbody>
			</table>';
	};

	// manage
	if ($_GET['go']=="manage"){
		$pageNum = 1;
		if(isset($_GET['page'])){$pageNum = $_GET['page'];}
		if (isset($_GET['page'])) {$pgadrss = '&page='.$_GET['page'];}

		// pagination
		$limit = 10;

		// counting the offset
		$offset = ($pageNum - 1) * $limit;

		$count = select_query('mod_venon_testimonial_post','id');
		$counter = mysql_num_rows($count);

		$nextpage = $pageNum +1;
		$prepage = $pageNum -1;
		$ifnext = $counter/($limit*$pageNum);

		if ($counter > $limit AND $pageNum > 1) {echo '<a href="addonmodules.php?module=v_testimonial&go=manage&page='.$prepage.'">صفحه قبلی</a>';}
		if ($counter > $limit AND $ifnext > 1) {echo '<a style="float:left;" href="addonmodules.php?module=v_testimonial&go=manage&page='.$nextpage.'">صفحه بعدی</a>';}

		echo '<table width="100%">
				<tbody>
					<tr>
						<td>
							<table width="100%" class="form">
							<tbody><tr><td colspan="2" class="fieldarea" style="text-align:center;"><strong>آرشیو نظرات</strong></td></tr>
							<tr><td align="center">
							<div class="tablebg">
							<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
							<tbody><tr><th>کد</th><th>تاریخ</th><th>نام وارد شده</th><th>مشتری مرتبط</th><th>متن پیام</th><th>وضعیت</th><th width="20"></th><th width="20"></th></tr>';
							$table = 'mod_venon_testimonial_post';
							$fields = "id, uid, name, message, status, date";
							$sort = "id";
							$sortorder = "DESC";
							$limits = "$offset,$limit";
							$result = select_query($table,$fields,$where,$sort,$sortorder,$limits);

							while ($data = mysql_fetch_array($result)) {
							echo '<tr><td><a href="#" target="_blank">'.$data['id'] .'</a></td>
								<td>'. fromMySQLDate($data['date'],$data['date']) .'</td>
								<td>'.$data['name'] .'</td>
								<td>';

							//user list
							$username = select_query('tblclients','firstname,lastname',array('id'=>$data[uid]));
							$userfetch = mysql_fetch_array($username);
							$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];
							echo '<a href="clientssummary.php?userid='.$data['uid'].'" target="_blank">'. $userfullname .'</a></td>
								<td><textarea style="width:500px; height:100px;">'. $data['message'] .'</textarea></td>
								<td>'; if($data['status'] == 0) {echo 'معلق';}
								elseif ($data['status'] == 1) {echo 'تایید شده';}
								elseif ($data['status'] == 2) {echo 'تایید نشده';}
								elseif ($data['status'] == 3) {echo 'حذف شده';}
								echo'</td>
								<td><a href="addonmodules.php?module=v_testimonial&go=edit&id='. $data['id'] .'"><img src="images/edit.gif" width="16" height="16" border="0" alt="ویرایش" title="ویرایش"></a></td><td><a href="addonmodules.php?module=v_testimonial&go=dashboard&remove='. $data['id'] .'"><img src="images/icons/accessdenied.png" width="16" height="16" border="0" alt="حذف" title="حذف نظر"></a></td></tr>'
								;}

							$num_rows = mysql_num_rows($result);
							if ($num_rows == 0) {
							echo '<tr><td colspan="9">'.$LANG['nothingfound'].'</td></tr>';}
							echo'</tbody></table>
							</div>
							</td></tr></tbody></table>
						</td>
					</tr>
				</tbody>
			</table>';
	}

	// edit
	if ($_GET['go']=="edit"){
		$table = "mod_venon_testimonial_post";

		if(isset($_POST['name'])) {
			$date = toMySQLDate($_POST['date']);
			$update = array("date"=>$date,"name"=>$_POST['name'],"uid"=>$_POST['uid'],"message"=>$_POST['message'],"status"=>$_POST['status'],);
			$where = array("id"=>"$_GET[id]");
			update_query('mod_venon_testimonial_post',$update,$where);
			echo '<div class="infobox">تغییرات اعمال شده ذخیره شد.</div>';
		}

		$where = array("id"=>$_GET['id']);
		$result = select_query($table,'*',$where);
		$data = mysql_fetch_array($result);

		//user list
		$username = select_query('tblclients','firstname,lastname',array('id'=>$data[uid]));
		$userfetch = mysql_fetch_array($username);
		$userfullname = $userfetch['firstname'].' '.$userfetch['lastname'];

		echo '<form method="post" action="addonmodules.php?module=v_testimonial&go=edit&id='.$data['id'].'">
			<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tr><td width="15%" class="fieldlabel">تاریخ ارسال</td><td class="fieldarea"><input type="text" name="date" value="'. fromMySQLDate($data['date'],$data['date']).'" size="25"></td></tr>
			<tr><td class="fieldlabel">نام وارد شده</td><td class="fieldarea"><input type="text" name="name" value="'. $data['name'].'" size="70"></td></tr>
			<tr><td class="fieldlabel">مشتری مرتبط</td><td class="fieldarea"><input type="text" name="uid" value="'. $data['uid'].'" size="70"><a href="clientssummary.php?userid='.$data['uid'].'" target="_blank">'. $userfullname .'</a></td></tr>
			<tr><td class="fieldlabel">پیام وارد شده</td><td class="fieldarea"><textarea id="message" name="message" rows=10 style="width:100%; direction: rtl;">'.$data['message'].'</textarea></td></tr>
			<tr><td class="fieldlabel">وضعیت</td><td class="fieldarea">
			<select name="status">
				<option></option>
				<option value="1"';if($data['status'] == 1) {echo ' selected';} echo'>تایید شده</option>
				<option value="0"';if($data['status'] == 0) {echo ' selected';} echo'>معلق</option>
				<option value="2"';if($data['status'] == 2) {echo ' selected';} echo'>تایید نشده</option>
			</select></td></tr>
		</table>
		<p style="text-align:center;"><input class="btn btn-primary" type="submit" value="ذخیره تغییرات" class="btn btn-primary" ><a class="btn btn-default" href="addonmodules.php?module=v_testimonial&go=manage">بازگشت</a></p>';
	}

	// add
	if ($_GET['go']=="add"){
		$table = "mod_venon_testimonial_post";

		if(!empty($_POST['name']) AND !empty($_POST['message'])) {
			$date = toMySQLDate($_POST['date'],$_POST['date']);
			$values = array("date"=>$date,"name"=>$_POST['name'],"uid"=>$_POST['uid'],"message"=>$_POST['message'],"status"=>$_POST['status'],);
			$newid = insert_query($table,$values);
			echo '<div class="successbox">نظر جدید با موفقیت اضافه شد.</div>';
		} else {
			echo '<div class="infobox">توجه نمایید فیلدهای نام و متن اجباری می باشد.</div>';
		}

		echo '<form method="post" action="addonmodules.php?module=v_testimonial&go=add">
			<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tr><td width="15%" class="fieldlabel">تاریخ ارسال</td><td class="fieldarea"><input type="text" name="date" value="'. fromMySQLDate(date('Y-m-d H:i'),date('Y-m-d H:i')) .'" size="25"></td></tr>
			<tr><td class="fieldlabel">نام مستعار</td><td class="fieldarea"><input type="text" name="name" size="70"></td></tr>
			<tr><td class="fieldlabel">کد مشتری مرتبط</td><td class="fieldarea"><input type="text" name="uid" size="70"></td></tr>
			<tr><td class="fieldlabel">پیام وارد شده</td><td class="fieldarea"><textarea id="message" name="message" rows=10 style="width:100%; direction: rtl;"></textarea></td></tr>
			<tr><td class="fieldlabel">وضعیت</td><td class="fieldarea">
			<select name="status">
				<option value="1"';if($data['status'] == 1) {echo ' selected';} echo'>تایید شده</option>
				<option value="0"';if($data['status'] == 0) {echo ' selected';} echo'>معلق</option>
				<option value="2"';if($data['status'] == 2) {echo ' selected';} echo'>تایید نشده</option>
			</select></td></tr>
		</table>
		<p style="text-align:center;"><input class="btn btn-primary" type="submit" value="ذخیره تغییرات" class="btn btn-primary" ><a class="btn btn-default" href="addonmodules.php?module=v_testimonial&go=manage">بازگشت</a></p>';
	}

	// setting
	if ($_GET['go']=="setting"){
		if (isset($_POST['enable'])) {
			$table = "mod_venon_testimonial";
			$update = array("enable"=>$_POST['enable'], "clientonly"=>$_POST['clientonly'], "perpage"=>$_POST['perpage']);
			$where = array("setting"=>"option");
			update_query($table,$update,$where);
		}
		$result = mysql_query('SELECT * FROM `mod_venon_testimonial` WHERE `setting` = "option"');
		$data = mysql_fetch_array($result);

		echo '<form method="post" action="addonmodules.php?module=v_testimonial&go=setting">
		<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
			<tr><td class="fieldlabel">فعال بودن ارسال نظر</td><td class="fieldarea"><label><input type="radio" name="enable" value="1"'; if ($data['enable'] == '1') {echo 'checked';} echo '/>بله</label>	<label><input type="radio" name="enable" value="0" '; if ($data['enable'] == '0') {echo 'checked';} echo ' />خیر</label></td></tr>

			<tr><td class="fieldlabel">ارسال نظر تنها برای مشتریان عضو</td><td class="fieldarea"><label><input type="radio" name="clientonly" value="1" '; if ($data['clientonly'] == '1') {echo 'checked';} echo ' />بله</label>	<label><input type="radio" name="clientonly" value="0" '; if ($data['clientonly'] == '0') {echo 'checked';} echo ' />خیر</label></td></tr>
			<tr><td class="fieldlabel">تعداد نظرات در هر صفحه</td><td class="fieldarea"><input type="text" name="perpage" value="'.$data['perpage'].'"/></td></tr>
		</table>
		<p align="center"><input type="submit" value="ذخیره" class="btn btn-primary" ></p>
		</form>
		';
	};

	// setting
	if ($_GET['go']=="help"){
		echo '<h1>راهنمای کدهای ماژول و انواع خروجی ها</h1>
		<p><b>کدهای داخلی WHMMCS</b></p>
		<p>ازکدهای زیر می توانید در فایل های tpl قالب های WHMCS استفاده نمایید. کلیه کدهای این قسمت در تمامی صفحات داخلی WHMCS قابل فراخوانی می باشند:</p>

		<div class="contentbox">
		<p><b>کدهای نمایش یک نظر به صورت رندوم:</b></p>
		<p>نمایش نام: <b>{$testimonial.single.name}</b><br>
		نمایش متن نظر: <b>{$testimonial.single.message}</b><br>
		نمایش تاریخ نظر: <b>{$testimonial.single.date}</b><br>
		نمایش تاریخ نظر به همراه زمان: <b>{$testimonial.single.time}</b><br>
		</p>
		</div>

		<div class="contentbox">
		<p><b>کدهای نمایش نظر به صورت چندتایی:</b> (حداکثر خروجی تعداد 5 نظر می باشد)</p>
		<textarea style="width: 800px; height: 100px; direction:ltr; text-align:left;">{foreach from=$testimonial.multiple key=k item=multiple}
	{if $k <= 5}
		{$testimonial.multiple.$k.name}-{$testimonial.multiple.$k.message}-{$testimonial.multiple.$k.time}<br/>
	{/if}
{/foreach}</textarea>
		</div>
		<hr/>
		<p><b>نمایش نظرات در HTML و سایر اسکریپت ها</b></p>
		<p>ماژول نظرات مشتریان ونون به شما این امکان را می دهد تا نظرات مشتریان خود را بر روی سایر سیستم ها هم نمایش دهید با توجه به اینکه نوع این خروجی جاوااسکریپت می باشد شما می توانید بر روی کلیه سیستم ها از نمایش نظرات استفاده نمایید .</p>
		<p>برای انجام این کار کافی است تا کدهای جاوا اسکریپت زیر را بر حسب نیاز در قالب های خود استفاده نمایید:</p>

		<div class="contentbox">
		<p><b>خروجی نمایش یک نظر:</b> (نوع خروجی را می توانید با ویرایش فایل testimonial.php در قسمت /* single output */ تغییر دهید)</p>
		<input type="text" value="'.htmlspecialchars('<script type="text/javascript" src="http://yourdomain.com/whmcs/testimonial.php?get=single"></script>').'" size="100"/>

		<p><b>خروجی نمایش چندتایی:</b> (نوع خروجی را می توانید با ویرایش فایل testimonial.php در قسمت /* multiple output */  و همچنین تعداد نظرات قابل نمایش را می توانید تغییر دهید.)</p>
		<input type="text" value="'.htmlspecialchars('<script type="text/javascript" src="http://yourdomain.com/whmcs/testimonial.php?get=multiple&num=5"></script>').'" size="100"/>
		</div>
		';
	}

	// enddive
			echo '</div>
	</div>';

}

function v_testimonial_sidebar($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $option1 = $vars['option1'];
    $option2 = $vars['option2'];
    $option3 = $vars['option3'];
    $option4 = $vars['option4'];
    $option5 = $vars['option5'];
    $LANG = $vars['_lang'];

    $sidebar = '<span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" /> Venon Testimonial</span>
<ul class="menu">
        <li><a href="addonmodules.php?module=v_testimonial&go=dashboard">نظرات منتظر تایید</a></li>
		<li><a href="addonmodules.php?module=v_testimonial&go=manage">آرشیو نظرات</a></li>
		<li><a href="addonmodules.php?module=v_testimonial&go=add">افزودن نظر جدید</a></li>
		<li><a href="addonmodules.php?module=v_testimonial&go=setting">تنظیمات</a></li>
        <li>نسخه: '.$version.'</li>
    </ul>';
    return $sidebar;
}

function v_testimonial_clientarea($vars) {

	$table = "mod_venon_testimonial_post";

	if(!empty($_POST['name']) AND !empty($_POST['message'])) {
		$date = date("Y-m-d H:i");
		$date = toMySQLDate($date);
		$values = array("date"=>$date,"name"=>$_POST['name'],"uid"=>$_POST['uid'],"message"=>$_POST['message'],"status"=>0,);
		$newid = insert_query($table,$values);
		$error['tot'] = 1;
	} else {
		$error['fields'] = 1;
	}


  $modulelink = $vars['modulelink'];
  $LANG = $vars['_lang'];
	$userid = $_SESSION['uid'];

	// get setting
	$result = select_query('mod_venon_testimonial','*',array('setting'=>'option'));
	$data = mysql_fetch_array($result);
	$enable = $data['enable'];
	$clientonly = $data['clientonly'];
	$perpage = $data['perpage'];

	// pagination
	$pageNum = 1;
	$getpage = $_GET['page'];
	settype($getpage, 'integer');

	if($getpage>0){$pageNum = $getpage;}
	if ($getpage>0) {$pgadrss = '&page='.$getpage;}

	$limit = $perpage;

	// counting the offset
	$offset = ($pageNum - 1) * $limit;
	$count = select_query('mod_venon_testimonial_post','id',array('status'=>1));
	$counter = mysql_num_rows($count);

	$nextpage = $pageNum +1;
	$prepage = $pageNum -1;
	$ifnext = $counter/($limit*$pageNum);

	if ($counter > $limit AND $pageNum > 1) {$pre = 1;}
	if ($counter > $limit AND $ifnext > 1) {$next = 1;}

	$table = 'mod_venon_testimonial_post';
	$fields = "id, uid, name, message, status, date";
	$where = array(
		"status" => "1",
	);
	$sort = "id";
	$sortorder = "ASC";
	$limits = "$offset,$limit";
	$result = select_query($table,$fields,$where,$sort,$sortorder,$limits);

	while($posts = mysql_fetch_array($result)){
	  $rows[] = $posts;
	}

    return array(
        'pagetitle' => 'نظرات و دیدگاه ها',
        'breadcrumb' => array('index.php?m=v_testimonial'=>'نظرات و دیدگاه ها'),
        'templatefile' => 'testimonial',
        'requirelogin' => false, # or false
        'vars' => array(
            'enable' => $enable,
            'clientonly' => $clientonly,
			'perpage' => $perpage,
			'limit' => $limit,
			'perpage' => $perpage,
			'nextpage' => $nextpage,
			'prepage' => $prepage,
			'next' => $next,
			'pre' => $pre,
			'posts' => $posts,
			'rows' => $rows,
			'error' => $error,
        ),
    );
}

?>
