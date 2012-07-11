<?php
/*
Property of IOMATIX Inc.
You may not alter, replicate, or reuse the contents in this file.
May 11th, 2006
*/
function getCurrentTimestamp() {
        $today = getdate();
        $mon = $today['mon'];
        if ($mon < 10) {
                        $mon = "0" . $mon;
        }

        $mday = $today['mday'];

        if ($mday < 10) {
                        $mday = "0" . $mday;
        }

        $year = $today['year'];

        $hours = $today['hours'];

        $minutes = $today['minutes'];

        $seconds = $today['seconds'];

        return "$year$mon$mday$hours$minutes$seconds";
}

function encryptPassword($password) {
        if (getHttpHost() == 'localhost')
        {
                return "";
        }
        else
        {
                $val = exec("/usr/local/bin/blowfish -f '/usr/local/bin/express.key' -e $password");
                return $val;
        }
}

function decryptPassword($password) {
        $val = exec("/usr/local/bin/blowfish -f '/usr/local/bin/express.key' -d $password");
        return $val;
}


function getHttpHost() {
        $httpHost = "localhost";

        if (isset($_SERVER["HTTP_HOST"]))
        {
                $httpHost = $_SERVER["HTTP_HOST"];
        }

        return $httpHost;
}

function getCookieURL() {
        switch (getHttpHost())
        {

        case "localhost":
                $cookieURL = "";
                break;
                
        default:
                $cookieURL = ".iomatix.com";
                break;
        }

        return $cookieURL;
}

function getCookieAccountUID() {
        $account_uid = 0;

        if (isset($_COOKIE["account_uid"]))
        {
                $account_uid = $_COOKIE["account_uid"];
        }

        return $account_uid;
}

function setCookieAccountUID($account_uid) {
        logDebug("SET COOKIE account_uid = $account_uid for ".getCookieURL());
        setCookie("account_uid", $account_uid, time()+60*60*24*365, "/", getCookieURL());
}

function getCookieAccountEmail() {
        $account_email = "";

        if (isset($_COOKIE["account_email"]))
        {
                $account_email = $_COOKIE["account_email"];
        }

        return $account_email;
}

function setCookieAccountEmail($email) {
        logDebug("SET COOKIE account_email = $email for ".getCookieURL());
        setCookie("account_email", $email, time()+60*60*24*365, "/", getCookieURL());
}

function getCookieReferralID() {
        $referral_id = "";

        if (isset($_COOKIE["referral_id"]))
        {
                $referral_id = $_COOKIE["referral_id"];
        }

        return $referral_id;
}

function setCookieReferralID($referral_id) {
        logDebug("SET COOKIE referral_id = $referral_id for ".getCookieURL());
        setCookie("referral_id", $referral_id, time()+60*60*24*365, "/", getCookieURL());
}

function getCookieDeviceUID() {
        $device_uid = -1;
        
        if (isset($_COOKIE["device_uid"]))
        {
                $device_uid = $_COOKIE["device_uid"];
        }

        return $device_uid;
}

function setCookieDeviceUID($device_uid) {
        logDebug("SET COOKIE device_uid = $device_uid for ".getCookieURL());
        setCookie("device_uid", $device_uid, time()+60*60*24*365, "/", getCookieURL());
}

function getCookieManufacturer() {
        $manufacturer = "";

        if (isset($_COOKIE["manufacturer"]))
        {
                $manufacturer = $_COOKIE["manufacturer"];
        }

        return $manufacturer;
}

function setCookieManufacturer($manufacturer) {
        logDebug("SET COOKIE manufacturer = $manufacturer for ".getCookieURL());
        setCookie("manufacturer", $manufacturer, time()+60*60*24*365, "/", getCookieURL());
}

function getCookieLang() {
        $lang = "";

        if (isset($_COOKIE["lang"]))
        {
                $lang = $_COOKIE["lang"];
        }

        return $lang;
}

function setCookieLang($lang) {
        logDebug("SET COOKIE lang = $lang for ".getCookieURL());
        setCookie("lang", $lang, time()+60*60*24*365, "/", getCookieURL());
}

function checkLanguageSettings() {
        //
        // return $lang.
        // check whether language is passed in thru the url or if it is set thru user's cookie
        //

        $lang_array = array("pt","en","es","de","it","fr");
        if (isset($_GET["lang"]))
        {
                $lang = trim($_GET["lang"]);
                if (in_array($lang,$lang_array) == false)
                {
                        $lang = "en";
                }
                setCookieLang($lang);
                        }
        else
        {
                $lang = getCookieLang();
                if (in_array($lang,$lang_array) == false)
                {
                        $lang = "en";
                        setCookieLang($lang);
                }
        }
        return $lang;
}

function checkReferralID() {
        if (isset($_GET["r_id"]))
        {
                $referral_id = TRIM($_GET["r_id"]);
                setCookieReferralID($referral_id);
        }
        else
        {
                $referral_id = getCookieReferralID();
        }
        return $referral_id;
}

function logInfo($msg, $error_handler = null) {
        if ($error_handler)
        {
                $old_error_handler = set_error_handler($error_handler);
                trigger_error($msg, E_USER_NOTICE);
                trigger_error($msg, E_USER_WARNING);
                $old_error_handler = set_error_handler($old_error_handler);
        }
        else
        {
                trigger_error($msg, E_USER_NOTICE);
                trigger_error($msg, E_USER_WARNING);
        }
}

function logError($msg, $error_handler = null) {
        if ($error_handler)
        {
                $old_error_handler = set_error_handler($error_handler);
                trigger_error($msg, E_USER_ERROR);
                $old_error_handler = set_error_handler($old_error_handler);
        }
        else
        {
                trigger_error($msg, E_USER_ERROR);
        }
}

function logDebug($msg, $error_handler = null) {
        if ($error_handler)
        {
                $old_error_handler = set_error_handler($error_handler);
                trigger_error($msg, E_USER_WARNING);
                $old_error_handler = set_error_handler($old_error_handler);
        }
        else
        {
                //trigger_error($msg, E_USER_WARNING);
        }
}

function validEmail($emailaddress) {
        // Decides if the email address is valid. Checks syntax and MX records,
        // for total smartass value. Returns "valid", "invalid-mx" or
        // "invalid-form".

        // Validates the email address. I guess it works. *shrug*
        if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $emailaddress, $check))
        {

                if (function_exists("checkdnsrr"))
                {
                        if (checkdnsrr(substr(strstr($check[0], '@'), 1), "ANY") )
                        {
                                return true;
                        }
                }
                else
                {
                        return true;
                }
        }
        return false;
}

function content($read_db, $title) {
	$sql = "select text from content where title = '$title'";
	$read_db->query($sql);
	$page = $read_db->fetchObject();
	$output = $page->text;
return $output;
}

function content_details($read_db, $title) {
	$sql = "select * from content where title = '$title'";
	$read_db->query($sql);
	$page = $read_db->fetchObject();
	$output = array($page->text,$page->page_title,$page->description,$page->keywords);
return $output;
}

function sitemap($read_db) {
	$sql = "select * from content";
	$read_db->query($sql);
	while ($entry = $read_db->fetchArray()) {
		$output.="<div class=\"bg_h3\"><h3><a href=\"".$entry['title'].".html\">".strtoupper($entry['page_title'])."</a> ".substr($entry['created'],5,2)."/".substr($entry['created'],8,2) . "/" . substr($entry['created'],0,4) . "</h3></div>";
		$output.="<br /><p>" .$entry['description'] . "</p>";
	}
return $output;
}

function stories($read_db, $category, $num) {
	$sql = "select * from stories where category = '$category' order by last_updated desc limit $num";
	$read_db->query($sql);
	while ($story = $read_db->fetchArray()) {
		$output.="<hr /><br /><h3>" . $story['title'] . "</h3>";
		$sm=substr($story['last_updated'],4,2);
		$sd=substr($story['last_updated'],6,2);
		$sy=substr($story['last_updated'],0,4);
		$output.="<br /><fieldset><legend>" . $sm . "/" . $sd . "/" . $sy . "</legend>";
		$output.="<br />" .$story['text'] . "</fieldset><br /><br /><hr />";
	}
return $output;
}

function validateEmail($email) {
   return eregi("^[_\.0-9a-zA-ZÊ¯Â∆ÿ≈-]+@([0-9a-zA-ZÊ¯Â∆ÿ≈][0-9a-zA-ZÊ¯Â∆ÿ≈-]+\.)+[a-zA-Z]{2,6}$", $email);
}

function printrow($namefield, $printfield, $nameprop, $type, $options="none")
{	
	$options = explode(",",$options);
	if ($type == 'text') {
		$output.="$namefield <br />";
		$output.="<input type=\"text\" name=\"$nameprop\" value=\"$printfield\" /><br />";	
	}
	elseif ($type == 'checkboxes') {
		$output = "$namefield<br /><br />";
		$nameprop=$nameprop . "[]";
		$i=0;
		foreach($options as $value) {
			if ($printfield==$value) {
				$output.="$value <input type=\"checkbox\" name=\"$nameprop\" value=\"$value\" CHECKED />";
			}
			else {
				$output.="$value <input type=\"checkbox\" name=\"$nameprop\" value=\"$value\" />";
			}
			if ($i>3) {
				$output.="<br />";
				$i=0;
			}
			$i++;
		}
		$output.="<br />";
	}
	elseif ($type == 'textarea') {
		$output.="$namefield <br />";
		$output.="<textarea rows=\"5\" cols=\"30\" name=\"$nameprop\" wrap=\"virtual\" />$printfield</textarea><br />";
	}
	elseif ($type == 'textarea_defined') {
		$output.="$namefield <br />";
		$output.="<textarea rows=\"25\" cols=\"60\" name=\"$nameprop\" wrap=\"virtual\" />$printfield</textarea><br />";
	}
	elseif ($type == 'select') {
		$output.="$namefield";
		$output.=" <select name=\"$nameprop\">";
		foreach($options as $value) {
			if ($printfield == $value) {
				$output.="<option selected value=\"" . $value . "\">" . $value . "</option>";
			}
			else {
				$output.="<option value=\"" . $value . "\">" . $value . "</option>";
			}
		}
		$output.="</select><br />";
	}
	elseif ($type == 'multi select') {
		$output.="$namefield<br />";
		$nameprop=$nameprop . "[]";
		$output.="<br /> <select name=\"$nameprop\" multiple=\"multiple\">";
		foreach($options as $value) {
			if ($printfield == $value) {
				$output.="<option selected value=\"" . $value . "\">" . $value . "</option>";
			}
			else {
				$output.="<option value=\"" . $value . "\">" . $value . "</option>";
			}
		}
		$output.="</select><br /><br />";
	}
	elseif ($type == 'none') {
		$output.="<br />$namefield <br /><br />";
	}
return $output;
}

function select_box($read_db,$s_name,$s_value,$s_label,$s_post) {
	$output.="<select name=\"$s_name\">";
	while($row = $read_db->fetchArray()) {
		if ($s_post == $row[$s_value]) {
			$output.="<option selected value=\"" . $row[$s_value] . "\">" . $row[$s_label] . "</option>";
		}
		else {
			$output.="<option value=\"" . $row[$s_value] . "\">" . $row[$s_label] . "</option>";
		}
	}
	$output.="</select>";
return $output;
}

function catalog($read_db, $another_db, $category="all") {
	if ($category <> "all") {
		$where = "where category='" . $category . "' ";
	}
	$i=0;
	$sql = "select distinct(title), description, category, price from inventory " . $where . "group by category, description, title";
	//echo $sql;
	$read_db->query($sql);
	while ($item = $read_db->fetchArray()) {
		$title=$item['title'];
		$description=$item['description'];
		$img=str_replace(" ","_",$title);
		$output.="<a name=\"" . $img . "_" . $i . "\"></a><fieldset style=\"background:#eeeedd;border:1px solid #ddd;height:228px;\"><legend style=\"background:#fff;border:1px solid #ddd;font:125% Georgia,Serif;color:#CC0000;\">&nbsp;" . $title . "&nbsp;</legend><input type=\"hidden\" name=\"item\" value=\"" . $i . "\" />";
		$output.="<ul class=\"hoverbox_store\">
						<li>
							<a href=\"#top\" onclick=\"javascript:submit_form('add','" . $title . "','','" . $i . "');\"><img src=\"img/store/" . $img . "_1.jpg\" alt=\"" . $title . "\" /><img src=\"img/store/" . $img . "_2.jpg\" alt=\"" . $title . "\" class=\"flip\" /></a>
						</li>
					</ul>";
		$output.="<b>Price</b>: " . $item['price'];
		$num_items=1;
		if (stristr($title,"20 plus")) {
			$num_items=20;
		}
		$output.="<br /><b>Quantity</b>: <input size=\"3\" name=\"qnty\" value=\"" . $num_items . "\" type=\"text\">";
		if ($item['category'] == "tshirt" || stristr($description,"T-shirt")) {
			$output.="<br /><b>Description</b>: " . $description;
			
			$sql = "select id, title, product from inventory where title='" . $title . "' order by product desc";
			$another_db->query($sql);
			$sizes="";
			while ($titles = $another_db->fetchArray()) {
				$product=$titles['product'];
				if (stristr($product, "(small 7-8)")) {
					$sizes.="small 7-8,";
				}
				if (stristr($product, "(medium 10-12)")) {
					$sizes.="medium 10-12,";
				}
				if (stristr($product, "(large 14-16)")) {
					$sizes.="large 14-16,";
				}
				if (stristr($product, "(small)")) {
					$sizes.="small,";
				}
				if (stristr($product, "(medium)")) {
					$sizes.="medium,";
				}
				if (stristr($product, "(large)")) {
					$sizes.="large,";
				}
				if (stristr($product, "(x-large)")) {
					$sizes.="x-large,";
				}
				if (stristr($product, "(2x-large)")) {
					$sizes.="2x-large,";
				}
				if (stristr($product, "(3x-large)")) {
					$sizes.="3x-large,";
				}
			}
			$sizes=substr_replace($sizes,"",-1);
			$output.=printrow("", "large", "size", "select", $sizes);
		}
		else {
			if ($colors=stristr($description,"Color (s)</b>: ")) {
				$colors = strtolower(str_replace("Color (s)</b>: ","",str_replace(", ",",",$colors)));
				$output.="<br />" . printrow("<b>Select Color</b>:", "", "size", "select", $colors);
			}
			else {
				$output.="<br /><b>Description</b>: " . $description;
				$output.="<input type=\"hidden\" name=\"size\" value=\"\" />";
			}
		}
		$output.="<hr style=\"display:block;\" /><a href=\"#top\" onclick=\"javascript:submit_form('add','" . $title . "','','" . $i . "');\">+ add</a></fieldset><br /><br />";
		$i++;
	}
return $output;
}

function catalog_list($read_db, $category="all") {
	if ($category <> "all") {
		$where = "where category='" . $category . "' ";
	}
	$i=0;
	$last="";
	$sql = "select distinct(title), description, category, price from inventory " . $where . "group by category, description, title";
	$read_db->query($sql);
	$output.="<ul>";
	while ($item = $read_db->fetchArray()) {
		$category=$item['category'];
		$title=$item['title'];
		$name=str_replace(" ","_",$title);
		if ($category <> "staff") {
			if ($category <> $last) {
				$output.="</ul>";
				$output.="<ul>";
				$output.="<li style=\"background:none;margin:0 0 0 -15px;\"><a href=\"" . $category . ".html\"><h4>" . ucwords($category) . "</h4></a></li>";
				$i=0;
			}
			$output.="<li><a href=\"" . $category . ".html#" . $name . "_" . $i . "\">" . $title . "</a></li>";
		}
		else {
			if ($category <> $last) {
				$staff_output.="</ul>";
				$staff_output.="<ul>";
				$staff_output.="<li style=\"background:none;margin:0 0 0 -15px;\"><a href=\"" . $category . ".htm\"><h4>" . ucwords($category) . "</h4></a></li>";
				$i=0;
			}
			$staff_output.="<li><a href=\"" . $category . ".htm#" . $name . "_" . $i . "\">" . $title . "</a></li>";
		}
		$last=$category;
		$i++;
	}
	$output.=$staff_output . "</ul>";
return $output;
}

function search($read_db, $search) {
	if ($search != "search CVAD" && $search != "" && $search != " ") {
		$search=removeTags(removeAttributes($search));
		$output="<h3>SEARCH RESULTS FOR &ldquo;<i><span class=\"bg_green_3\">$search</span></i>&rdquo;</h3><br />";
		$lsearch=strtolower($search);
		$usearch=ucwords($search);
		$sql = "select * from content where (text like '%$search%' or text like '%$lsearch%' or text like '%$usearch%') AND (title!='contact_form' AND title!='banners' AND title!='menu_main' AND title!='menu_top')";
		$result = $read_db->query($sql);
		$nrows = $read_db->numRows($result);
		while ($rows = $read_db->fetchArray()) {
			$text=$rows['text'];
			//$date = substr($rows['last_updated'], 4, 2) . "/" . substr($rows['last_updated'], 6, 2) . "/" . substr($rows['last_updated'], 0, 4);
			$results .= "<h2>" . ucwords(str_replace("intl ","intl. ",str_replace("_"," ",str_replace("index","home",$rows['title'])))) . " | <a href='" . $rows['title'] . ".html'>read more</a></h2><p>";
			// <i>".substr_count($text,$lsearch)." occurances</i>
			$text=str_replace($usearch,"<span class=\"bg_green_3\">$usearch</span>",str_replace($lsearch,"<span class=\"bg_green_3\">$lsearch</span>",removeTags(removeAttributes($text))));
			$wpos=strpos($text,'>') + strpos($text,$lsearch)-100;
			if ($wpos>0) {
				$before=". . . ";
			}
			else {
				$wpos=0;
			}
			$results.=$before . substr($text,$wpos,'400')." . . .</p><hr />";
		}
		if ($results) {
			$output.=$results;
		}
		else {
			$output.="<p>Sorry, no results found for &ldquo;<i><span class=\"bg_green_3\">$search</span></i>&rdquo;.</p>";
		}
	}
	else {
		$output.="<h3>You must specify a search value.</h3>";
	}
return $output;
}

function removeAttributes($tagSource) {
	$stripAttrib = "' (style|class|id)=\"(.*?)\"'i";
	$tagSource = stripslashes($tagSource);
	$tagSource = preg_replace($stripAttrib, '', $tagSource);
	return $tagSource;
}

function removeTags($source) {
	$allowedTags='<a>';
	$source = strip_tags($source, $allowedTags);
	return preg_replace('/<(.*?)>/ie', "'<'.removeAttributes('\\1').'>'", $source);
}
?>
