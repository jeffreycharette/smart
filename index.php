<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
ob_start ("ob_gzhandler");
list($name,$service)=explode(".",$_GET['file']);
/*if ( $name != "mobile" ) {
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
header('Location: http://smart.wearecharette.com/mobile.html');
}*/

$mode=$_GET['mode'];
$file=preg_replace("/[^a-z0-9-]/", "-", strtolower($name)).".".preg_replace("/[^a-z0-9.]/", "-", strtolower($service));
if ($file==".") {$file="";}
$script="";
require("classes/ioDB.class.php");
$read_db = new smart_readDB();
$read_db->open();
$newobject_id=$_COOKIE[session_name()];
require("classes/user.class.php");
ini_set('session.save_path', '/home/19349/data/tmp');
ini_set('session.gc_maxlifetime', '43200');
session_set_cookie_params(43200);
session_start();

if(!isset($_SESSION["user"]) || !is_numeric($_SESSION["user"]->loginID)) {
	$error = "Please login or sign up for an account.";
}
if($_SESSION["IP"] != $_SERVER["REMOTE_ADDR"]) {
	if (!isset($error)) {$error = "Please login from another computer.";}
}
if (isset($_SESSION['HTTP_USER_AGENT']) &&
	$_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
	if (!isset($error)) {$error = "Please login from another computer.";}
}
else {
  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
}

if (!isset($error)) {
	$uid=$_SESSION["user"]->loginID;
}

/* ACCESS ROUTING */
if ($name=="edit") {
	if ($_SESSION["user"]->username!="admin" && $_SESSION["user"]->username!="architect") {
		$_SESSION["history"]="";
		header('Location: http://smart.wearecharette.com/login.html');
	}
}

/* CHECK FOR FILE */
if (file_exists($file."html")) {
	$data = file_get_contents($file);
}
else {
	$type=cleanData($_GET['type']);
	$id=cleanData($_GET['id']);
	$name=cleanData($name);
	$service=cleanData($service);
	
	if ($name=="") {
		$name="index";
		$service="html";
	}
	if ($type!="") {
		$selection="collections.type='".$type."'";
	}
	if ($id!="" && $name!="edit") {
		$selection="collections.id='".$id."'";
	}
	else {
		$selection="collections.type='page' AND collections.name='".$name."'";
	}
	$sql="SELECT collections.id as cid, collections.parent as parent, collections.name as cname,entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE ".$selection." AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y'";
	$result=$read_db->query($sql);
	if ($read_db->numRows($result) > 0) {
		while ($row = $read_db->fetchArray()) {
			$_POST['cname']=$row['cname'];
			if ($row['type']=="query") {
				$query[$row['id']]=$row['value'];
				$value[$row['id']]["json"]=str_replace("{","",str_replace("}","",$row['value']));
			}
			elseif ($row['type']=="tpl") {
				$value['tpl']=$row['value'];
			}
			elseif ($row['type']=="content") {
				$content=$row['value'];
			}
			else {
				$value[$row['id']]["value"]=$row['value'];
			}
			$value[$row['id']]["name"]=$row['name'];
			if ($row['type']=="") {
				$row['type']="text";
			}
			$value[$row['id']]["type"]=$row['type'];
			$cid=$row['cid'];
			$parent=$row['parent'];
		}
		$value['tpl']=str_replace("{menu}",display_menu($cid,$parent,$read_db),$value['tpl']);
		$value['tpl']=str_replace("{content}",$content,str_replace("{type}",rtrim(strtolower($type),"s"),$value['tpl']));
		if ($mode!="edit") {
			$value['tpl']=str_replace("{facebook}",'<iframe src="http://www.facebook.com/plugins/like.php?href=http%253A%252F%252Fsmart.wearecharette.com%252F&amp;layout=standard&amp;show_faces=false&amp;width=470&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:470px; height:35px;" allowTransparency="true"></iframe>',$value['tpl']);
		}
	}
	else {
		//Page not found add status of 200 do search and return first 5 results (could redirect based on confidence HIGH=page, MIDDLE=list results, LOW=add, HACK DETECTED=kill a man just to watch him die)
		$value['tpl']="<div id=\"sidebar\"></div><div id=\"main_content\"><h3>404 page not found</h3><p class=\"large\">&ldquo;".ucwords($file)."&rdquo; no longer exists or was entered incorrectly.</p></div>";
		$file="404.".$service;
	}
	if (is_array($query)) {
		foreach ($query as $key=>$v) {
			if (file_exists($key.".".$service)) {
				$value[$key]["value"] = file_get_contents($key.".".$service);
				if ($service=="json") {
					$value[$key]["value"] = json_decode($value[$key]["value"], true);
				}
			}
			else {
				$options=json_decode($v, true);
				
				//override values with GET if same type
				foreach ($options as $option_key=>$option_value) {
					if ($options['allow-override']) {
						if (is_array($_GET)) {
							foreach ($_GET as $rk=>$rv) {
								if ($options['type']!="" || $type=="all" || $options['id']!="") {
									if ($rk!="file"  && $rk!="id" && $rv!="") {
										if ($rk=="eid") {
											$rk="id";
										}
										$options[$rk]=$rv;
									}
								}
							}
						}
					}
				}
				// SETUP MODEL
				$result=$read_db->query("SHOW TABLE STATUS LIKE 'entities'");
				$row = $read_db->fetchArray();
				$next_id=$row['Auto_increment'];
				$type=$options['type'];
				if ($type=="") {
					if ($options['id']!="") {
						$result = $read_db->query("SELECT type FROM collections WHERE collections.id='".$options['id']."'");
					}
					elseif ($options['name']!="") {
						$result = $read_db->query("SELECT type FROM collections WHERE collections.name='".$options['name']."'");
					}
					$row = $read_db->fetchArray($result);
					$type=$row['type'];
				}
				
				// set variables for template
				$result = $read_db->query("SELECT collections.type as ctype, entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE collections.type='model' AND collections.name='".$type."' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' AND entities.name='variables'");
				if ($read_db->numRows($result) > 0) {
					$row = $read_db->fetchArray($result);
					$edtr=$row['value'];
				}
				
				$sql="SELECT collections.type as ctype, entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE collections.type='model' AND collections.name='".$type."' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' GROUP BY entities.name ORDER BY entities.name,entities.id";
				$result=$read_db->query($sql);
				if ($read_db->numRows($result) > 0) {
					$editortpl_new="{name}{first}{last}{title}{sub title}{type}{order}{category}{color}{frequency}{cost}{email}<div class=\"tdate\">{start}{end}{occurs}</div>{location}{instructor}{summary image}{summary title}{summary}{summary link}{content}{case study}{info}{size}{price}{article}{article image}{news text}{news link}{text}{link name}{link}{text 1}{link 1}{text 2}{link 2}{text 3}{link 3}{text 4}{link 4}{signup link}{quote}{home caption}{caption}{thumb}{images}{main link text}{main link}{slideshow}{left slideshow}{left slideshow link text}{left slideshow link}{right slideshow}{right slideshow link text}{right slideshow link}".$edtr;
					$edtr="";
					while ($row = $read_db->fetchArray()) {
						$model['type']=$row['type'];
						$model['name']=$row['name'];
						$mname=str_replace(" ","-space-",$model['name']);
						if ($model['type']=="tpl") {
							$model['tpl']=$row['id'];
						}
						if ($options['id']!="") {
							$newform="<input id=\"set_option\" type=\"hidden\" value=\"".$key."\" name=\"set_option\" />";
						}
						if ($model['type']=="textarea" || $model['type']=="content") {
							$model['value']=print_input($mname."_".$model['type'],$model['name'],"","","textarea"," id=\"textarea_new".$z."\" style=\"width:97%;height:200px;\" rows=\"8\" class=\"textarea\" ");
							$z++;
						}
						elseif (stristr($model['type'],"image")) {
							$o++;
							list($trash,$opts)=explode(":",$model['type']);
							$imgs_new=print_input($model['name']."_".$model['type']."_".$next_id,$model['name'],"","","hidden_textarea"," id=\"image_new".$o."\" ")."<br /><div class=\"images-box\"><label for=\"pictures\">".$model['name']."</label><ul class=\"imglist\" id=\"item_".$next_id."\"></ul><a id=\"images_".$next_id."_".$opts."\" class=\"upload\" href=\"#\" title=\"Click to upload\">upload</a></div>";
							$model['value']=$imgs_new;
						}
						elseif (stristr($model['type'],"limit")) {
							$l++;
							list($trash,$lmt)=explode(":",$model['type']);
							$height=ceil($lmt/100)*14;
							$model['value']=print_input($mname."_".$model['type'],$model['name'],"","","textarea"," id=\"limit_new_".$l."\" class=\"limit\" rows=\"10\" cols=\"60\" style=\"height:".$height."px;width:97%\"")."<div id=\"limit_new_info_" . $l . "\" class=\"limit_info\">Character Limit ".print_input("limit","","",$lmt,"text"," size=\"3\"")."</div>\n";
						}
						elseif ($model['name']=="start") {
							$z++;
							$model['value']="<div class=\"bx\">".print_input($model['name']."_daterange",$model['name'],"",date('m/d/Y'),"text"," id=\"date_new".$z."\" class=\"daterange\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_1\" class=\"calicon\"></span></div>";
						}
						elseif ($model['name']=="end") {
							$z++;
							$model['value']="<div class=\"bx\" style=\"width: 150px;\">".print_input($model['name']."_".$model['type'],$model['name'],"",date('m/d/Y'),"text"," id=\"date_new".$z."\" class=\"".$model['type']."\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_2\" class=\"calicon\"></span></div>";
						}
						elseif ($model['type']=="select") {
							$z++;
							$data=json_decode($row['value'],true);
							if ($model['name']=="occurs") {
								$val="once";
							}
							$model['value']="<div class=\"bx\" style=\"width: 130px;\">".print_input($model['name']."_".$model['type'],$model['name'],$data,$val,"select_array"," style=\"width:auto;\" id=\"until_".$z."\" class=\"".$model['name']." select\"")."</div><div class=\"bx until until_".$z."\" style=\"display:none;\">".print_input("until_date","until","",date('m/d/Y'),"text"," id=\"until_new".$z."\" class=\"date\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_3\" class=\"calicon\"></span></div><div class=\"bx until dates_until_".$z."\" style=\"display:none;\">".print_input("dates_multiple","dates","",date('m/d/Y'),"text"," id=\"dates_new".$z."\" class=\"multiple\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_3\" class=\"calicon\"></span></div>";
						}
						else {
							$model['value']=print_input($mname."_".$model['type'],$model['name'],"","","text"," id=\"text_new".$z."\" class=\"".$model['type']."\" ")."<br />";
						}
						if (stristr($editortpl_new,"{".$model['name']."}")) {
							$editortpl_new=str_replace("{".$model['name']."}",$model['value']."{".$model['name']."}",$editortpl_new);
						}
						elseif (strlen($model['name']) > 1 && $model['type'] != "tpl" && $model['type'] != "content") {
							$editortpl_new=$editortpl_new.$model['value']."{".$model['name']."}";
						}
					}
					$newform="<div class=\"box\"><form name=\"entities\" method=\"post\" action=\"edit.html?id=".$cid."\"><h2 style=\"color:#000000;float:left;\">Add New</h2><input style=\"float:right\" value=\"Publish\" class=\"save\" type=\"submit\" /><br /><br /><hr /><input id=\"type\" type=\"hidden\" value=\"".$type."\" name=\"type\" /><input id=\"etpl\" type=\"hidden\" value=\"".$model['tpl']."\" name=\"etpl\" /><input id=\"create_check\" type=\"hidden\" value=\"1\" name=\"create_check\" />\n".$newform;
					$newform.=str_replace("<div class=\"tdate\"></div>","",preg_replace("/{(.*?)}/is","",$editortpl_new));
					$newform.="</form><br /><br /></div>";
					$editortpl_new="";
				}	
					//SET OPTIONS
					//echo $options['type']." : ".$type;	
					if ($options['type']!="") {
						$collection_type="collections.type='".$options['type']."' AND";
					}
					else {
						$collection_type="";
					}
									
					if ($options['separator']=="hr" || $options['separator']=="br") {$separator="<".$options['separator']." />";}
					if ($options['limit']!="") {
						$lmt=$options['limit'];
						if ($options['page']!="") {
							if ($options['page']<=0) {$options['page']=1;}
							$begin=($options['page']-1)*$options['limit'];
							$lmt=$begin.",".$lmt;
						}
						$lmt=" LIMIT ".$lmt;
					}
          if ($options['field']!="" && $options['sort']!="") {
             if ($options['sort_type']=="numeric") {
                     $result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['field']."' AND collections.active='y' ORDER BY entities.value + 0 ".$options['sort']);
             }
             else {
                     $result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['field']."' AND collections.active='y' ORDER BY entities.value ".$options['sort']);
             }
             if ($read_db->numRows($result) > 0) {
                     $order_field="";
                     while ($row = $read_db->fetchArray()) {
                             $order_field.=$row['id'].",";
                     }
                     $order_field=rtrim($order_field,",");
                     $order_field="FIELD(collections.id, ".$order_field."),";
             }
          }
					if ($options['group']!="") {
						$entity_match="AND collections.id IN (SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['group']."' AND collections.active='y')";
						//$entity_match="AND collections.id IN (SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['group']."' AND collections.active='y' GROUP BY entities.value)";
					}
					elseif ($options['on']!="" && $options['match']!="") {
						if ($options['limit']!="") {
							$result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['on']."' AND entities.value='".$options['match']."' AND collections.active='y' GROUP BY id");
							$cnt=$read_db->numRows($result);
						}
						$entity_match="AND collections.id IN (SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND entities.name='".$options['on']."' AND entities.value='".$options['match']."' AND collections.active='y')";
					}				
					elseif ($options['search']!="") {
						$options['search']=ltrim(rtrim($options['search']));
						//$options['search']=urlencode($options['search']);
						$search=explode(" ",$options['search']);
						$sql_search="";
						foreach($search as $string) {
							if (($string!="the" && $string!="with" && $string!="and" && $string!="that"  && $string!="these" && $string!="those" && $string!="them" && $string!="they" && $string!="there" && $string!="their") && strlen($string)>2) {
								 $sql_search.="LCASE(entities.value)='".strtolower($string)."' OR LCASE(entities.value) LIKE '% ".strtolower($string)."%' OR LCASE(entities.value) LIKE '%".strtolower($string)." %' OR ";	
							}
						}
						$sql_search=" AND (".rtrim($sql_search," OR").")";
                                                if ($cnt=="") {
                                                        $result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE collections.type!='model' AND entities.type!='content' AND entities.type!='tpl' AND collections.name!='results' AND collections.name!='404' AND collections.name!='edit' AND collections.name!='logout' AND collections.name!='login' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id".$sql_search." AND collections.active='y' GROUP BY collections.id");
                                                        $cnt=$read_db->numRows($result);
                                                }
						$collection_type="";
						$result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE collections.type!='model' AND entities.type!='content' AND entities.type!='tpl' AND collections.name!='results' AND collections.name!='404' AND collections.name!='edit' AND collections.name!='logout' AND collections.name!='login' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id".$sql_search
." AND collections.active='y' GROUP BY collections.id".$lmt);
      			if ($read_db->numRows($result) > 0) {
              while ($row = $read_db->fetchArray()) {
              	$limit_fields.=$row['id'].",";
              }
              $limit_fields.=rtrim($limit_fields,",");
              $entity_match="AND collections.id IN (".$limit_fields.")";
							$order_field="collections.type,collections.id,";
            }
						else {
							$value[$key]["value"]="<p>No results found.</p>";
							$separator="";
						}
					}
					elseif ($options['id']!="") {
						$entity_match="AND collections.id='".$options['id']."'";
						if ($mode!="") {
							$entity_match="AND collections.type = (SELECT collections.type FROM collections WHERE collections.id='".$options['id']."')";
						}
					}
					elseif ($options['random']=="true") {
						$result=$read_db->query("SELECT collections.id FROM collections WHERE collections.type='".$options['type']."' ORDER BY RAND() LIMIT 1");
						if ($read_db->numRows($result) > 0) {
							while ($row = $read_db->fetchArray()) {
								$entity_match="AND collections.id='".$row['id']."'";
							}
						}
						if ($mode!="") {
							$entity_match="AND collections.type = '".$options['type']."'";
						}
						$options['random']=="false";
					}
					elseif ($options['name']!="") {
						$entity_match="AND collections.name='".$options['name']."'";
					}
					elseif ($options['limit']!="") {
						if ($cnt=="") {
							$result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' GROUP BY id ORDER BY ".$order_field."collections.type");
							$cnt=$read_db->numRows($result);
						}
						$result=$read_db->query("SELECT collections.id FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' GROUP BY id ORDER BY ".$order_field."collections.type ".$lmt);
						if ($read_db->numRows($result) > 0) {
							while ($row = $read_db->fetchArray()) {
								$limit_fields.=$row['id'].",";
							}
							$limit_fields.=rtrim($limit_fields,",");
							$entity_match="AND collections.id IN (".$limit_fields.")";
						}
					}
					else {
						$entity_match="";
					}
					if ($value[$key]["value"]!="<p>No results found.</p>") {
						$sql="SELECT collections.name as cname, collections.id as cid, collections.type as ctype, entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE ".$collection_type." cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' ".$entity_match." ORDER BY ".$order_field."entities.type, entities.name ".$lmt_one;
						$result=$read_db->query($sql);
					}
					if ($options['page']!="" && $options['limit']!="") {
						$options['page']=intval($options['page']);
						$pages=ceil($cnt/$options['limit']);
						if ($pages>0) {
							$prev=$options['page']-1;
							if ($prev<=0) {
								$prev=$pages;
							}
							$next=$options['page']+1;
							if ($next>$pages) {
								$next=1;
							}
							if ($options['search']!="") {
								$search="&search=".$options['search'];
							}
							if ($type=="all") {
								$url=cleanData($_POST['cname']).".html?id=".$options['id']."&type=".$type."&page=".$options['page']."&match=".$options['match']."&on=".$options['on'].$search;
							}
							else {
								$url=cleanData($_POST['cname']).".html?id=".$options['id']."&type=".$options['type']."&page=".$options['page'].$search."&";
							}
							if ($pages!=1) {
								$pagination="<a title=\"first page\" class=\"start\" href=\"".preg_replace("/page=(\d+)&/","page=1&",$url)."\">start</a><a title=\"previous\" class=\"prev\" href=\"".preg_replace("/page=(\d+)&/","page=".$prev."&",$url)."\">prev</a><span class=\"left\">Page ".$options['page']." of ".$pages." </span><a title=\"next\" class=\"next\" href=\"".preg_replace("/page=(\d+)&/","page=".$next."&",$url)."\">next</a><a title=\"last page\" class=\"end\" href=\"".preg_replace("/page=(\d+)&/","page=".$pages."&",$url)."\">end</a>";
							}
						}
						else {
							$pagination="No results found.";
						}
					}
					if ($read_db->numRows($result) > 0) {
						while ($row = $read_db->fetchArray()) {
							if ($row['type']=="query") {
								$query2[$row['id']]=$row['value'];
							}
							elseif ($row['type']=="tpl") {
								$arr[$row['cid']]["tpl"]=$row['value'];
							}
							else {
								$arr[$row['cid']][$row['id']]['name']=$row['name'];
								if ($row['type']=="") {
									$row['type']="text";
								}
								$arr[$row['cid']][$row['id']]['type']=$row['type'];
								$row['value'] = mb_convert_encoding($row['value'],'HTML-ENTITIES','UTF-8');
								if ($file=="rss.html" && ($row['type']=="text" || stristr($row['type'],"limit"))) {
									$row['value']=xml_character_encode(str_replace("&mdash;","-",str_replace("&ldquo;","\"",str_replace("&rdquo;","\"",str_replace("&rsquo;","'",$row['value'])))));
								}
								$arr[$row['cid']][$row['id']]['value']=$row['value'];
							}
							$arr[$row['cid']]["ctype"]=$row['ctype'];
							$arr[$row['cid']]["cname"]=$row['cname'];
						}
					}
					else {
						$value[$key]["value"]="<p>No results found.</p>";
						$separator="";
					}
				/*else {
					$result = $read_db->query("INSERT INTO collections (id,name,type) VALUES ('','".$type."','model')");
					$result = $read_db->query("SELECT LAST_INSERT_ID();");
					$row = $read_db->fetchArray($result);
					$cid = $row['LAST_INSERT_ID()'];
					$sql="SELECT collections.id as cid, collections.type as ctype, entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE collections.type='".$type."' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' GROUP BY entities.name";
					$result=$read_db->query($sql);
					if ($read_db->numRows($result) > 0) {
						$new=array();
						while ($row = $read_db->fetchArray()) {
							$new[$row['id']]['name']=$row['name'];
							$new[$row['id']]['type']=$row['type'];
							$new[$row['id']]['value']=$row['value'];
						}
						foreach ($new as $nkey => $nvalue) {
							if ($nvalue['type']=="tpl") {
								$read_db->query("INSERT INTO entities (id,name,type,value) VALUES ('','".$nvalue['name']."','".$nvalue['type']."','".$nvalue['value']."')");
							}
							else {
								$read_db->query("INSERT INTO entities (id,name,type,value) VALUES ('','".$nvalue['name']."','".$nvalue['type']."','".$nvalue['type']."')");
							}
							$result = $read_db->query("SELECT LAST_INSERT_ID();");
							$row = $read_db->fetchArray($result);
							$eid = $row['LAST_INSERT_ID()'];
							$read_db->freeResult();
							$result = $read_db->query("INSERT INTO cid_eid (cid,eid) VALUES ('".$cid."','".$eid."')");
						}
					}
				}*/
				if (is_array($arr)) {
					$value['q_editor'].="<div class=\"edits\" id=\"set".$key."\">";
					foreach ($arr as $k=>$v) {
						if ($options['id']==$k) {$selected="selected";} else {$selected="";}
						
						//set variables for template
						$result2 = $read_db->query("SELECT collections.type as ctype, entities.id,entities.name,entities.value,entities.type FROM collections,cid_eid,entities WHERE collections.type='model' AND collections.name='".$type."' AND cid_eid.cid=collections.id AND cid_eid.eid=entities.id AND collections.active='y' AND entities.name='variables'");
						if ($read_db->numRows($result2) > 0) {
							$row2 = $read_db->fetchArray($result2);
							$edtr=$row2['value'];
						}
						$editortpl="{name}{first}{last}{title}{sub title}{type}{order}{category}{color}{frequency}{cost}{email}{date}<div class=\"tdate\">{start}{end}{occurs}{until}{dates}</div>{location}{instructor}{summary image}{summary title}{summary}{summary link}{content}{case study}{info}{size}{price}{article}{article image}{news text}{news link}{text}{link name}{link}{text 1}{link 1}{text 2}{link 2}{text 3}{link 3}{text 4}{link 4}{signup link}{quote}{home caption}{caption}{thumb}{images}{main link text}{main link}{slideshow}{left slideshow}{left slideshow link text}{left slideshow link}{right slideshow}{right slideshow link text}{right slideshow link}".$edtr;
						$edtr="";

							//$editortpl="{name}{first}{last}{title}{sub title}{type}{order}{category}{color}{frequency}{cost}{email}{date}<div class=\"tdate\">{start}{end}{occurs}{until}{dates}</div>{location}{instructor}{summary}{summary link}{content}{case study}{info}{size}{price}{article}{news text}{news link}{text}{link name}{link}{text 1}{link 1}{text 2}{link 2}{text 3}{link 3}{text 4}{link 4}{signup link}{quote}{home caption}{caption}{thumb}{images}{main link text}{main link}{slideshow}{left slideshow}{left slideshow link text}{left slideshow link}{right slideshow}{right slideshow link text}{right slideshow link}";
						if ($options['id']!="") {
							$set_option="<input id=\"set_option\" type=\"hidden\" value=\"".$key."\" name=\"set_option\" />";
						}
						$value['q_editor'].="<div class=\"box ".$selected."\"><form name=\"entities\" method=\"post\" action=\"edit.html?id=".$cid."\"><input id=\"submit_check\" type=\"hidden\" value=\"1\" name=\"submit_check\" />\n".print_input("id","","",$k,"hidden","")."<input style=\"float:right\" value=\"Publish\" class=\"save\" type=\"submit\" /><br />";
						if ($options['id']!="") {
							$value['q_editor'].=$set_option;
						}						
						$deleteform="<form name=\"delete\" method=\"post\" action=\"edit.html?id=".$cid."\"><input id=\"delete_check\" type=\"hidden\" value=\"1\" name=\"delete_check\" size=\"15\"/>".print_input("id","","",$k,"hidden","");
						if ($options['id']!="") {
                                                        $deleteform.=$set_option;
                                                }
						$deleteform.="<input value=\"remove\" class=\"delete\" type=\"submit\"></form>";
						
						if ($arr[$k]['tpl']!="") {
							if ($options['tpl']!="") {
								$result=$read_db->query("SELECT value FROM entities WHERE id='".$options['tpl']."'");
								if ($read_db->numRows($result) > 0) {
									$row = $read_db->fetchArray();
									$arr[$k]['tpl']=$row['value'];
								}
							}
							if (is_array($v)) {
								$z=1;
								$o=1;
								foreach ($v as $kk=>$vv) {
									if ($vv['type']!="tpl" && $kk!="tpl") {
										if ($vv['type']=="date" || $vv['type']=="daterange") {
											if (isValidDateTime($vv['value'])) {
												$$vv['name']=$vv['value'];
												$vv['value']=date('m/d/Y',convert_date_string($vv['value']));
											}
											elseif (stristr($vv['value'],"/")) {
												list($month,$day,$year)=explode("/",$vv['value']);
												$vv['value']=$year."-".$month."-".$day." 00:00:00";
												if (isValidDateTime($vv['value'])) {
													$$vv['name']=$vv['value'];
													$vv['value']=date('m/d/Y',convert_date_string($vv['value']));
												}
												else {
													$vv['value']="";
													$$vv['name']=$vv['value'];
												}
											}
											else {
												$vv['value']="";
												$$vv['name']=$vv['value'];
											}
										}
										elseif ($vv['type']=="multiple") {
											if ($vv['value']!="") {
												$tdates=explode(",",$vv['value']);
												$adates="";
												foreach ($tdates as $tv) {
													if (isValidDateTime($tv)) {
														$multiple_name=$tv;
														$multiple_name=date('M. j',convert_date_string($tv));
													}
													elseif (stristr($tv,"/")) {
														list($month,$day,$year)=explode("/",$tv);
														$tv=$year."-".$month."-".$day." 00:00:00";
														if (isValidDateTime($tv)) {
															$multiple_name=$tv;
															$multiple_name=date('M. j',convert_date_string($tv));
														}
														else {
															$tv="";
															$multiple_name=$tv;
														}
													}
													else {
														$tv="";
														$multiple_name=$tv;
													}
													$multiple.=" + ".$multiple_name;
													$adates.=$year.$month.$day.",";
												}
												$multiple=ltrim($multiple," + ");
												$adates=rtrim($adates,",");
											}
										}
										else {
											$$vv['name']=$vv['value'];
											$arr[$k]['tpl']=str_replace("{".$vv['name']."}",$vv['value'],$arr[$k]['tpl']);
											$sanitary_name = sanitize_filename($vv['value']);
											$arr[$k]['tpl']=str_replace("{".$vv['name'].".sanitize}",$sanitary_name,$arr[$k]['tpl']);
											$arr[$k]['tpl']=str_replace("{anchor_link}","#entry_".$k,$arr[$k]['tpl']);
											$arr[$k]['tpl']=str_replace("{urlencoded_".$vv['name']."}",urlencode($vv['value']),$arr[$k]['tpl']);
										}
										if ($vv['type']=="textarea" || $vv['type']=="content") {
											if (stristr($editortpl,"{".$vv['name']."}")) {
												$editortpl=str_replace("{".$vv['name']."}",print_input($kk,$vv['name'],"",$vv['value'],"textarea"," id=\"textarea".$kk."\" style=\"width:97%;height:200px;\" rows=\"8\" class=\"textarea\" "),$editortpl);
											}
											elseif (strlen($vv['name']) > 1) {
												$editortpl=$editortpl.print_input($kk,$vv['name'],"",$vv['value'],"textarea"," id=\"textarea".$kk."\" style=\"width:97%;height:200px;\" rows=\"8\" class=\"textarea\" ");
											}
										}
										elseif (stristr($vv['type'],"image")) {
											$o++;
											list($trash,$opts)=explode(":",$vv['type']);
											preg_match("/original\.(.*?)\?/is",$vv['value'],$matches);
											$imgs="<br /><div class=\"images-box\"><label for=\"pictures\">".$vv['name']."</label><ul class=\"imglist\" id=\"item_".$kk."\"><li><a href=\"/uploaded/images/".$kk."/full.".$matches[1]."?".mt_rand()."\"><img src=\"/uploaded/images/".$kk."/147x147.".$matches[1]."?".mt_rand()."\" width=\"147\" height=\"147\" /></a></li></ul><a id=\"images_".$kk."_".$opts."\" class=\"upload\" href=\"#\" title=\"Click to upload\">upload</a></div>";
											if (stristr($editortpl,"{".$vv['name']."}")) {
												$editortpl=str_replace("{".$vv['name']."}",$imgs,$editortpl);
											}
											elseif (strlen($vv['name']) > 1) {
												$editortpl=$editortpl.$imgs;
											}
											$arr[$k]['tpl']=str_replace("{small_image}","<img src=\"/uploaded/images/".$kk."/147x147.".$matches[1]."?".mt_rand()."\" width=\"147\" height=\"147\" />",$arr[$k]['tpl']);
										}
										elseif (stristr($vv['type'],"limit")) {
											$l++;
											list($trash,$lmt)=explode(":",$vv['type']);
											$height=ceil($lmt/100)*14;
											if (stristr($editortpl,"{".$vv['name']."}")) {
												$editortpl=str_replace("{".$vv['name']."}",print_input($kk,$vv['name'],"",$vv['value'],"textarea"," id=\"limit_".$l."\" class=\"limit\" rows=\"10\" cols=\"60\" style=\"height:".$height."px;width:97%\"")."<div id=\"limit_info_" . $l . "\" class=\"limit_info\">Character Limit ".print_input("limit","","",$lmt,"text"," size=\"3\"")."</div>\n",$editortpl);
											}
											elseif (strlen($vv['name']) > 1) {
												$editortpl=$editortpl.print_input($kk,$vv['name'],"",$vv['value'],"textarea"," id=\"limit_".$l."\" class=\"limit\" rows=\"10\" cols=\"60\" style=\"height:".$height."px;width:97%\"")."<div id=\"limit_info_" . $l . "\" class=\"limit_info\">Character Limit ".print_input("limit","","",$lmt,"text"," size=\"3\"")."</div>\n";
											}
										}
										elseif ($vv['name']=="start") {
											$editortpl=str_replace("{".$vv['name']."}","<div class=\"bx\">".print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"daterange\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_1\" class=\"calicon\"></span></div>",$editortpl);
										}
										elseif ($vv['name']=="end") {
											$editortpl=str_replace("{".$vv['name']."}","<div class=\"bx\" style=\"width: 150px;\">".print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"date\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_2\" class=\"calicon\"></span></div>",$editortpl);
										}
										elseif ($vv['name']=="occurs") {
											//$data=json_decode('{"once":"once","weekly":"weekly","monthly":"monthly","yearly":"yearly","select":"on select dates"}',true);
											$data=json_decode('{"once":"once","select":"on select dates"}',true);
											$editortpl=str_replace("{".$vv['name']."}","<div class=\"bx\" style=\"width: 130px;\">".print_input($kk,$vv['name'],$data,$vv['value'],"select_array"," style=\"width:auto;\" id=\"select".$k."\" class=\"occurs ".$vv['type']."\"")."</div>",$editortpl);
											$sval=$vv['value'];
										}
										elseif ($vv['name']=="until") {
											$editortpl=str_replace("{".$vv['name']."}","<div class=\"bx until select".$k."\" style=\"{u}\">".print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"".$vv['type']."\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_3\" class=\"calicon\"></span></div>",$editortpl);
										}
										elseif ($vv['name']=="dates") {
											$editortpl=str_replace("{".$vv['name']."}","<div class=\"bx until dates_select".$k."\" style=\"{d}\">".print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"multiple\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_3\" class=\"calicon\"></span></div>",$editortpl);
										}
										else {
											if (stristr($editortpl,"{".$vv['name']."}")) {
												$editortpl=str_replace("{".$vv['name']."}",print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"".$vv['type']."\" "),$editortpl);
											}
											elseif (strlen($vv['name']) > 1) {
												$editortpl=$editortpl.print_input($kk,$vv['name'],"",$vv['value'],"text"," id=\"text".$kk."\" class=\"".$vv['type']."\" ");
											}
										}
									}
									$z++;
								}
								if ($sval=="once") {
									$editortpl=str_replace("{u}","display:none",$editortpl);
									$editortpl=str_replace("{d}","display:none",$editortpl);
								}
								elseif ($sval=="select") {
									$editortpl=str_replace("{u}","display:none",$editortpl);
									$editortpl=str_replace("{d}","display:block",$editortpl);
								}
								else {
									$editortpl=str_replace("{u}","display:block",$editortpl);
									$editortpl=str_replace("{d}","display:none",$editortpl);
								}
							}
							if ($options['date']) {
								if ($options['date']=="first of month" || $options['date']=="beginning of month") {
									$dates['thismonth']=date('Y-m-01 H:i:s');
								}
								elseif ($options['date']=="first of year" || $options['date']=="beginning of year") {
									$dates['thismonth']=date('Y-01-01 H:i:s',strtotime("now"));
								}
								elseif ($options['date']=="next month") {
									$dates['thismonth']=date('Y-m-01 H:i:s',strtotime("now +1 month"));
								}
								elseif ($options['date']=="last month") {
									$dates['thismonth']=date('Y-m-01 H:i:s',strtotime("now -1 month"));
								}
								else {
									$dates['thismonth']=date('Y-m-d H:i:s',strtotime($options['date']));
								}
								
								if ($options['range']=="") {
									$dates['nextmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']." +1 month"));
								}
								elseif ($options['range']=="last of month" || $options['range']=="end of month") {
									$dates['nextmonth']=date('Y-m-t H:i:s',strtotime($dates['thismonth']));
								}
								elseif ($options['range']=="last of year" || $options['range']=="end of year") {
									$dates['nextmonth']=date('Y-12-31 H:i:s',strtotime($dates['thismonth']));
								}
								elseif ($options['range']=="next month") {
									$dates['nextmonth']=date('Y-m-01 H:i:s',strtotime($dates['thismonth']." +1 month"));
								}
								elseif ($options['range']=="last month") {
									$dates['nextmonth']=date('Y-m-01 H:i:s',strtotime($dates['thismonth']." -1 month"));
								}
								elseif ($options['range']=="first of month" || $options['range']=="beginning of month") {
									$dates['nextmonth']=date('Y-m-01 H:i:s');
								}
								elseif ($options['range']=="first of year" || $options['range']=="beginning of year") {
									$dates['nextmonth']=date('Y-01-01 H:i:s',strtotime("now"));
								}
								elseif ($options['range']=="now") {
									$dates['nextmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']));
								}
								else {
									$dates['nextmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']." ".$options['range']));
								}
                                                                if ($options['archive']!="" && $options['archive']!="-") {
                                                                        $now=date('Y',strtotime("now"));
                                                                        if (is_numeric($options['archive'])) {
                                                                                $dates['thismonth']=date('Y-m-d H:i:s',strtotime($options['archive']."-01-01 00:00:00"));
                                                                        }
                                                                        else {
                                                                                $dates['thismonth']=date('Y-m-d H:i:s',strtotime($now."-01-01 00:00:00"));
                                                                        }
                                                                        if ($options['archive']==$now) {
                                                                                $dates['nextmonth']=date('Y-m-d H:i:s',strtotime("-1 day"));
                                                                        }
                                                                        else {
                                                                                $dates['nextmonth']=date('Y-m-d H:i:s',strtotime($options['archive']."-12-31 00:00:00"));
                                                                        }
                                                                }
								$dates['type']=$options['type'];
								if (isValidDateTime($dates['thismonth'])) {
									$dates['lastmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']." -1 month"));
									/*//starting
									$options['date']=" && ((start_date >= '".$dates['thismonth']."' && start_date <= '".$dates['nextmonth']."')";
									//spanning
									$options['date'].=" || ('".$dates['thismonth']."' > start_date && '".$dates['nextmonth']."' < end_date)";
									//ending
									$options['date'].=" || (end_date >= '".$dates['thismonth']."' && end_date <= '".$dates['nextmonth']."'))";*/
									$aend=$end;
									if ($adates!="" && !is_array($adates)) {
										$adates=explode(",",rtrim($adates,","));
										if (is_array($adates)) {
											$lval=0;
											foreach ($adates as $aval) {
												if ($aval>$lval) {
													$mval=$aval;
												}
												$lval=$aval;
											}
											if ($start=="") {
												$start=substr($adates[0],0,4)."-".substr($adates[0],4,2)."-".substr($adates[0],6,2)." 00:00:00";
												$reset=1;
											}
											$mval=substr($mval,0,4)."-".substr($mval,4,2)."-".substr($mval,6,2)." 00:00:00";
											if ($mval>$end) {
												$aend=$mval;
											}
										}
									}
									/*if ($options['archive']!="" && $options['archive']!="-" && is_numeric($options['archive']) && $options['archive']==$now) {
										if (($start >= $dates['thismonth'] && $start <= $dates['nextmonth']) && ($aend >= $dates['thismonth'] && $aend <= $dates['nextmonth'])) {
											//yay
										}
										else {
											$arr[$k]['tpl']="";
										}
									}
									else {*/
                                                                        	if (($start >= $dates['thismonth'] && $start <= $dates['nextmonth']) || ($dates['thismonth'] > $start && $dates['nextmonth'] < $aend) || ($aend >= $dates['thismonth'] && $aend <= $dates['nextmonth'])) {
                                                                                	//yay
                                                                        	}
                                                                        	else {  
                                                                                	$arr[$k]['tpl']="";
                                                                        	}								
									//}
								}
								else {
									$options['date']="";
								}
							}
							if ($options['group']!="") {
								if ($last_value==$$options['group']) {
									$arr[$k]['tpl']="";
								}
								$last_value=$$options['group'];
							}
							if (isValidDateTime($start) && $start!="0000-00-00 00:00:00") {
								$start_date=strtotime($start);
								$start=date('M. j, Y',$start_date);
							}
							if ($end=="" || $end=="0000-00-00 00:00:00") {
								$end=$start;
							}
							if (isValidDateTime($end) && $end!="0000-00-00 00:00:00") {
								$end_date=strtotime($end);
								$end=date('M. j, Y',$end_date);
								if ($start_date==$end_date) {
									$start=date('m/d/Y',$start_date);
									$end="";
								}
								elseif (date('Y',$start_date)==date('Y',$end_date)) {
									$start=date('M. j',$start_date)." - ";
									if (date('M',$start_date)==date('M',$end_date)) {
										$end=date('j, Y',$end_date);
									}
								}
								else {
									$start.=" &mdash; ";
								}
								if ($options['date']!="") {
									$start_day=date('Ymd',$start_date);
									$end_day=date('Ymd',$end_date);
									$thismonth=date('Ymd',strtotime($dates['thismonth']));
									$nextmonth=date('Ymd',strtotime($dates['nextmonth']));
									if ($start_day<$thismonth) {
										$start_day=$thismonth;
									}
									if ($end_day>$nextmonth) {
										$end_day=$nextmonth;
									}
									for ($x=$start_day;$x<=$end_day;$x++) {
										if ($dates[$x]!="") {
											$dates[$x]['id'].="-";
											$dates[$x]['title'].=", ";
										}
										$dates[$x]['id'].="entry_".$k;
									}
									if ($adates!="") {
										if (is_array($adates)) {
											foreach ($adates as $avals) {
												if ($dates[$avals]!="") {
													$dates[$avals]['id'].="-";
													$dates[$avals]['title'].=", ";
												}
												$dates[$avals]['id'].="entry_".$k;
											}
										}
									}
								}
							}
							if ($arr[$k]["ctype"]=="page") {
								$arr[$k]['tpl']=str_replace("{more_link}",$arr[$k]['cname'].".html",$arr[$k]['tpl']);
							}
							else {
								$arr[$k]['tpl']=str_replace("{more_link}","detail.html?eid=".$k,$arr[$k]['tpl']);
							}
							$arr[$k]['tpl']=str_replace("{eid}",$k,$arr[$k]['tpl']);
							if ($start=="0" || $start=="12") {$start="";}
							if ($end=="0" || $end=="12") {$end="";}
							if ($multiple!="") {
								$arr[$k]['tpl']=str_replace("{dates}",$multiple,$arr[$k]['tpl']);
							}
							else {
								$arr[$k]['tpl']=str_replace("{dates}",$start."<span class=\"nowrap\">".$end."</span>",$arr[$k]['tpl']);
							}
							$multiple="";
						}
						else {
							$arr[$k]['tpl'].="<div id=\"$k\">";
							if (is_array($v)) {
								foreach ($v as $kk=>$vv) {
									$arr[$k]['tpl'].="<span class=\"key\">".$vv['name']."</span><span class=\"value\">".$vv['value']."</span>";
									if ($vv['type']!="tpl" && $kk!="tpl") {
										$value['q_editor'].="<b>".$vv['name']."</b>".$vv['value'];
									}
								}
							}
							$arr[$k]['tpl'].="</div>";
						}
						$value['q_editor'].=str_replace("<div class=\"tdate\"></div>","",preg_replace("/{(.*?)}/is","",$editortpl));
						//$arr[$k]['tpl']=preg_replace("/{(.*?)}/is","",$arr[$k]['tpl']);
						
						$value['q_editor'].="</form><br />".$deleteform."</div>";
	
						if ($arr[$k]['tpl']!="") {
							$ct++;
							$arr[$k]['tpl']=str_replace("{alink}","<a class=\"anchor\" name=\"entry_".$k."\"></a>",$arr[$k]['tpl']);
							if ($options['columns']!="" && is_numeric($options['columns'])) {
								if (($ct%$options['columns'])==0 && $options['columns']>1) {
									$arr[$k]['tpl']=str_replace("class=\"","style=\"margin-right:0;\" class=\"",$arr[$k]['tpl']);
								}
								if ($options['columns']<=1 || (($ct%$options['columns'])==0 && $options['columns']>1)) {
									$arr[$k]['tpl']=$arr[$k]['tpl'].$separator;
								}
							}
							else {
								$arr[$k]['tpl']=$arr[$k]['tpl'].$separator;
							}
						}
						if ($options['id']!="") {
							if ($options['id']==$k) {
								$value[$key]["value"].=$arr[$k]['tpl'];
							}
						}
						else {
							$value[$key]["value"].=$arr[$k]['tpl'];
						}
					}
					$value['q_editor'].=$newform."</div>";
					$newform="";
				}
				else {
					$value['q_editor'].="<div class=\"edits\" id=\"set".$key."\">".$newform."</div>";
					$newform="";
					$value[$key]["value"]="<p>No results found.</p>";
					$separator="";
				}
				if (!is_array($dates)) {
					$dates['thismonth']=date('Y-m-01 H:i:s');
					$dates['nextmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']." +1 month"));
					$dates['lastmonth']=date('Y-m-d H:i:s',strtotime($dates['thismonth']." -1 month"));
				}
				$value['tpl']=str_replace("{".$options['type']."_calendar}",get_calendar($dates),$value['tpl']);
				$value['tpl']=str_replace("{pagination}",$pagination,$value['tpl']);
				$value['tpl']=str_replace("{cnt}",$cnt,$value['tpl']);
				$value['tpl']=str_replace("{search}",$options['search'],$value['tpl']);
				if ($separator!="") {
					$value[$key]["value"]=substr($value[$key]["value"],0,-6);
					$separator="";
				}
				$options['archive']="";
				if ($value[$key]["value"]=="") {
					if ($options['date']!="") {
						$value[$key]["value"]="<p>No results between <i>".date('M. j, Y',strtotime($dates['thismonth']))." - ".date('M. j, Y',strtotime($dates['nextmonth']))."</i>.</p>";
					}
					else {
						$value[$key]["value"]="<p>No results found.</p>";
					}
				}
				$arr="";
				if (!file_exists($key.".".$service)) {
					if ($service=="json") {
						//file_put_contents($key.".".$service,json_encode($value[$key]["value"]));
					}
					else {
						//file_put_contents($key.".".$service,$value[$key]["value"]);
					}
				}
			}
		}
	}
	$parsedValues=parseRequest($read_db);
	$value['tpl']=str_replace("{id}",$id,$value['tpl']);
	$value['id']=$id;
	$props="<div id=\"props_box\"><form method=\"POST\" action=\"edit.html?id=".$cid."\"><input type=\"hidden\" id=\"type\" value=\"meta\" name=\"type\" /><input type=\"hidden\" id=\"create_check\" value=\"1\" name=\"create_check\" /><h3 style=\"color:#000000;\" class=\"left\">Search Engine Data</h3><input type=\"submit\" class=\"create\" value=\"Publish\" style=\"margin-right: 4px;\"><br /><br /><hr /><label for=\"title_meta\">title</label> <input type=\"text\" style=\"width: 96%;\" value=\"{title_value}\" name=\"title_meta\" id=\"title_meta\" /><label for=\"keywords_meta\">keywords</label><textarea wrap=\"virtual\" name=\"keywords_meta\" rows=\"4\" style=\"width: 96%;\" id=\"keywords_meta\">{keywords_value}</textarea><br><label for=\"description_meta\">description</label><textarea wrap=\"virtual\" name=\"description_meta\" rows=\"4\" style=\"width: 96%;\" id=\"description_meta\">{description_value}</textarea></form></div>";
	$add_page="<div id=\"add_box\"><form method=\"POST\" action=\"edit.html?id=".$cid."\"><input type=\"hidden\" id=\"type\" value=\"meta\" name=\"type\" /><input type=\"hidden\" id=\"create_check\" value=\"1\" name=\"create_check\" /><h2>Add Page</h2><br /><label for=\"page_name\">Page Name</label> <input type=\"text\" style=\"width: 96%;\" value=\"{page_name}\" name=\"page_name\" id=\"page_name\" /><label for=\"page_layout\">Page Layout</label><textarea wrap=\"virtual\" name=\"page_layout\" rows=\"4\" style=\"width: 96%;\" id=\"page_layout\">{page_layout}</textarea><br><br><input type=\"submit\" class=\"create\" value=\"Publish\" style=\"margin-right: 4px;\"></form></div>";
	//$new_page="<div style=\"display:none;\">{new_url}{new_layout}{new_content}{new_queries}{new_seo}</div>";
	if ($value['tpl']!="") {
		if (is_array($value)) {
			foreach ($value as $key=>$val) {
				if ($val['type']=="meta") {
					$find="{".$val['name']."}";
					$props=str_replace("create_check","submit_check",str_replace("{".$val['name']."_value}",$val['value'],str_replace($val['name']."_meta",$key,$props)));
				}
				else {
					$find="{".$key."}";
				}
				//$replace=stripslashes(htmlentities($value, ENT_QUOTES, 'ISO-8859-15'));
				if ($val['type']=="javascript") {
					$script.=$val['value'];
					$val['value']="";
				}
				elseif ($val['type']!="meta" && $file!="rss.html" && $key!="id" && $val['name']!="<") {
					if ($val['type']=="query") {
						$settings.="<label for=\"".$val['name']."_name\">".$val['name']."</label> <textarea  style=\"width:97%;height:50px;\" rows=\"2\" name=\"".$val['name']."_json\" id=\"".$val['name']."_json\">".$val['json']."</textarea>";
					}
					elseif ($val['type']!="tpl" && $val['type']!="content") {
						$settings.="<label for=\"".$val['name']."_name\">".$val['name']."</label> <input type=\"text\" style=\"width: 96%;\" value=\"".$val['type']."\" name=\"".$val['name']."_type\" id=\"".$val['name']."_type\" />";
					}
					$replace="<span id=\"".$key."\" class=\"query\">".$val['value']."</span>";
				}
				else {
					$replace=$val['value'];
				}
				if ($key!="tpl" && $key!="id" && $val['type']!="query" && $val['type']!="tpl" && $val['type']!="javascript" && $val['type']!="meta") {
					$value['editor'].="<div class=\"edits\" id=\"set".$key."\"><div class=\"box\"><form name=\"entities\" method=\"post\" action=\"edit.html?id=".$cid."\"><input id=\"submit_check\" type=\"hidden\" value=\"1\" name=\"submit_check\" size=\"15\"/>\n".print_input("id","","",$key,"hidden","")."<input style=\"float:right\" value=\"Publish\" class=\"save\" type=\"submit\" />\n";
					if ($val['type']=="date" || $val['type']=="daterange") {
						if (stristr($val['value'],"/")) {
							list($month,$day,$year)=explode("/",$val['value']);
							$val['value']=$year."-".$month."-".$day." 00:00:00";
						}
						if (isValidDateTime($val['value'])) {
							$val['value']=date('m/d/Y',convert_date_string($val['value']));
						}
					}
					if ($val['type']=="textarea" || $val['type']=="content") {
						$value['editor'].="<br />".print_input($key,$val['name'],"",$val['value'],"textarea"," id=\"textarea".$key."\" style=\"width:97%;height:200px;\" rows=\"8\" class=\"textarea\" ");
					}
					elseif (stristr($val['type'],"image")) {
						$o++;
						preg_match("/original\.(.*?)\?/is",$val['value'],$matches);
						list($trash,$opts)=explode(":",$val['type']);
						$value['editor'].="<br /><div class=\"images-box\"><label for=\"pictures\">".$val['name']."</label><ul class=\"imglist\" id=\"item_".$key."\"><li><a href=\"/uploaded/images/".$key."/full.".$matches[1]."?".mt_rand()."\"><img src=\"/uploaded/images/".$key."/147x147.".$matches[1]."?".mt_rand()."\" width=\"147\" height=\"147\" /></a></li></ul><a id=\"images_".$key."_".$opts."\" class=\"upload\" href=\"#\" title=\"Click to upload\">upload</a></div>";
					}
					elseif (stristr($val['type'],"limit")) {
						$l++;
						list($trash,$lmt)=explode(":",$val['type']);
						$value['editor'].=print_input($key,$val['name'],"",$val['value'],"textarea"," id=\"limit_".$l."\" class=\"limit\" rows=\"10\" cols=\"60\" style=\"height:70px;width:97%\"")."<div id=\"limit_info_" . $l . "\" class=\"limit_info\">Character Limit ".print_input("limit","","",$lmt,"text"," size=\"3\"")."</div>\n";
					}
					elseif ($val['name']=="start") {
						$value['editor'].="<br />".print_input($key,$val['name'],"",$val['value'],"text"," id=\"text".$key."\" class=\"".$val['type']."\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_1\" class=\"calicon\"></span></div>";
					}
					elseif ($val['name']=="end") {
						$value['editor'].="<br />".print_input($key,$val['name'],"",$val['value'],"text"," id=\"text".$key."\" class=\"".$val['type']."\" ")."<span class=\"calicon_p\"><img width=\"14\" height=\"14\" border=\"0\" src=\"/img/calendar.png\" alt=\"date_2\" class=\"calicon\"></span><br />";
					}
					else {
						$value['editor'].="<br />".print_input($key,$val['name'],"",$val['value'],"text"," id=\"text".$key."\" class=\"".$val['type']."\" ");
					}
					$value['editor'].="</form></div></div>";
				}
				$value['tpl']=str_replace($find,$replace,$value['tpl']);
			}
		}
	}
	else {
		$value['tpl']="<div>";
		if (is_array($value)) {
			foreach ($value as $key=>$val) {
				if ($val['type']!="javascript") {
					$value['tpl'].="<span id=\"".$key."\" class=\"query\"><span class=\"key\">".$key."</span><span class=\"value\">".$val['value']."</span></span>";
					if ($key!="tpl" && $key!="id" && $val['type']!="query" && $val['type']!="tpl") {
						$value['editor'].="<div class=\"edits\" id=\"set".$key."\"><div class=\"box\"><form name=\"entities\" method=\"post\" action=\"edit.html?id=".$cid."\"><input id=\"submit_check\" type=\"hidden\" value=\"1\" name=\"submit_check\" size=\"15\"/>\n".print_input("id","","",$key,"hidden","")."<input style=\"float:right\" value=\"Publish\" class=\"save\" type=\"submit\" />";
						if ($val['type']=="text" || $val['type'] == 'link') {
							$value['editor'].=print_input($key,$val['name'],"",$val['value'],"text"," class=\"".$val['type']."\" ");
						}
						elseif ($val['type']=="checkbox") {
							$value['editor'].=print_input($key,$val['name'],"",$val['value'],"checkbox"," id=\"checkbox".$key."\" class=\"".$val['type']."\" ");
						}
						elseif ($val['type']=="textarea" || $val['type']=="image" || $val['type']=="content") {
							$value['editor'].=print_input($key,$val['name'],"",$val['value'],"textarea"," id=\"textarea".$key."\" style=\"width:90%;height:250px;\" rows=\"8\" class=\"".$val['type']."\" ");
						}
						$value['editor'].="</form></div></div>";
					}
				}
			}
		}
		$value['tpl'].="</div>";
	}
	$settings="<div id=\"settings_box\"><form method=\"POST\" action=\"edit.html?id=".$cid."\"><input type=\"hidden\" id=\"type\" value=\"meta\" name=\"type\" /><input type=\"hidden\" id=\"create_check\" value=\"1\" name=\"create_check\" /><h2 class=\"left\">Settings</h2><input type=\"submit\" class=\"create\" value=\"Save\" style=\"margin-right: 4px;\"><br /><br />".$settings."</form></div>";
	if ($options['search']=="") {
		$value['tpl']=str_replace("{editor}",$value['editor'].$value['q_editor'].$props.$add_page.$settings,$value['tpl']);
	}
	if (is_array($parsedValues)) {
		foreach ($parsedValues as $key=>$val) {
			$value['tpl']=str_replace("{".$key."}",$val,$value['tpl']);
		}
	}
	$value['tpl']=preg_replace("/{(.*?)}/is","",$value['tpl']);
	if ($mode!="edit") {
		$value['tpl']=str_replace("<!--google-->","",$value['tpl']);
        }
	$value['tpl']=str_replace("<!--script-->",$script,$value['tpl']);
	$value['tpl']=str_replace("{id}",$id,$value['tpl']);
	if ($service=="json") {
		foreach ($value as $jk => $jv) {
			if (is_numeric($jk) && $jv['name']=="query") {
				$jv['value']=preg_replace("/{(.*?)}/is","",$jv['value']);
				$json.=json_encode(array("id"=>$jk,"value"=>$jv['value'],"pagination"=>$pagination)).",";
			}
			/*elseif (!is_numeric($jk)) {$json[$jk]=$jv;}*/
		}
		$json=rtrim($json,",");
		$data='{"query":['.$json.']}';
	}
	else {
		$data=$value['tpl'];
	}
	if (!file_exists($file)) {
		//file_put_contents($file,$data);
	}
	
	$read_db->close();
}

if ($service=="json") {
	header('Content-type: text/x-json');
}
elseif ($service=="rss") {
	header("Content-Type: application/xml; charset=ISO-8859-1");
}
elseif ($file=="rss.html") {
	header("Content-Type: application/xml; charset=ISO-8859-1");
}
else {
	header('Content-type: text/html');
}

echo $data;

function get_calendar($dates) {
	if (isValidDateTime($dates['lastmonth']) && isValidDateTime($dates['thismonth']) && isValidDateTime($dates['nextmonth'])) {
		$url=cleanData($_POST['cname']).".html?id=".$_GET['id']."&type=".$dates['type']."&";
		//$url.=http_build_query($_REQUEST);
		/*if ($_POST['id']!="") {$page.="id=".$_POST['id'];}else{$page.="type=".$_POST['type'];}
		if ($_POST['template']!="") {$page.="&template=".$_POST['template'];}
		if ($_POST['order']!="") {$page.="&order=".$_POST['order'];}
		if ($_POST['limit']!="") {$page.="&limit=".$_POST['limit'];}
		if ($_POST['separator']!="") {$page.="&separator=".$_POST['separator'];}
		if ($_POST['columns']!="") {$page.="&columns=".$_POST['columns'];}
		if ($_POST['range']!="") {$page.="&range=".$_POST['range'];}
		if ($_POST['match']!="") {$page.="&match=".$_POST['match'];}
		if ($_POST['on']!="") {$page.="&on=".$_POST['on'];}*/
		$calendar="<div id=\"cal_head\">";
		//last month
		$lmonth=date('Y-m-01',strtotime($dates['lastmonth']));
		$calendar.="<div class=\"sm_month_l\"><a href=\"".$url."date=".$lmonth."\">&lt; prev</a></div>";
		//next month
		$nmonth=date('Y-m-01',strtotime($dates['thismonth']." +1 month"));
		$calendar.="<div class=\"sm_month_r\"><a href=\"".$url."date=".$nmonth."\">next &gt;</a></div>";
		//this month
		$calendar.="<div id=\"cal_loader\"></div><div class=\"sm_month\">".date('F Y', strtotime($dates['thismonth']))."</div>";
		$calendar.="</div>";
		$calendar.="<div class=\"cal\"><table summary=\"".$dates['type']." Calendar\">";
		//days of the week
		//$calendar.="<thead><tr><td class=\"caldow\">Sun</td><td class=\"caldow\">Mon</td><td class=\"caldow\">Tue</td><td class=\"caldow\">Wed</td><td class=\"caldow\">Thu</td><td class=\"caldow\">Fri</td><td class=\"caldow\">Sat</td></tr></thead>";
		$calendar.="<tbody>";
		$weekdays=date('w',strtotime($dates['thismonth']));
		//blanks before
		for ($i=0;$i<$weekdays;$i++) {
			$calendar.="<td class=\"calspace\"> </td>";
		}
		$yearmonth=date('Ym',strtotime($dates['thismonth']));
		$days=date('t',strtotime($dates['thismonth']));
		for ($i=1;$i<=$days;$i++) {
			$day=str_pad($i,2,0,STR_PAD_LEFT);
			if ($dates[$yearmonth.$day]!="") {
				$calendar.="<td class=\"calevent\"><a title=\"".$dates[$yearmonth.$day]['title']."\" href=\"#".$dates[$yearmonth.$day]['id']."\">".$i."</a>";
			}
			else {
				$calendar.="<td>".$i;
			}
			$calendar.="</td>";
			//blanks after
			if ($i==$days) {
				$weekdays=7-date('w',strtotime($dates['nextmonth']));
				if ($weekdays<7) {
					for ($c=1;$c<$weekdays;$c++) {
						$calendar.="<td class=\"calspace\"> </td>";
					}
				}
			}
			if ((($i+$weekdays)%7)==0) {
				$calendar.="</tr><tr>";
			}
		}
		$calendar.="</tr></tbody></table></div>";
	}
	else {
		$calendar="The date entered is not valid.";
	}

	return $calendar;
}


function parseRequest($read_db) {
	if ($_POST['cname']=="logout") {
		return user_logout_action();
	}
	elseif ($_POST['cname']=="login") {
		return user_login_action($read_db);
	}
	elseif(isset($_POST['name']) && isset($_POST['email'])) {
		$_POST['response']=emailit();
		return $_POST;
	}
	elseif ($_SESSION["user"]->username=="admin" || $_SESSION["user"]->username=="architect") {
		$submit_check = cleanData($_POST['submit_check']);
		$create_check = cleanData($_POST['create_check']);
		$delete_check = cleanData($_POST['delete_check']);
		$set_option = cleanData($_POST['set_option']);
		$type = cleanData($_POST['type']);
		$eid = cleanData($_POST['id']);
		$cid = cleanData($_GET['id']);
		if ($submit_check == "1") {
			//action = "update";
			if (is_array($_POST)) {
				foreach ($_POST as $key=>$value) {
					$id=strtolower(cleanData($key));
					$value=cleanData($value);
					if (is_numeric($id) && $id > 0) {
						$result = $read_db->query("SELECT type FROM entities WHERE id='".$id."'");
						if($read_db->numRows($result) == 1) {
							$row = $read_db->fetchArray($result);
							$type = $row['type'];
							$read_db->freeResult();
							if ($type=="date" || $type=="daterange") {
								if (!isValidDateTime($value)) {
									list($month,$day,$year)=explode("/",$value);
									$date=$year."-".$month."-".$day." 00:00:00";
									if (isValidDateTime($date)) {
										$value=$date;
									}
								}
							}
							/*if ($type=="multiple") {
								if ($value!="") {
									$tdates=explode(",",$value);
									$value="";
									foreach ($tdates as $tv) {
										if (!isValidDateTime($tv)) {
											list($month,$day,$year)=explode("/",$tv);
											$date=$year."-".$month."-".$day." 00:00:00";
											if (isValidDateTime($date)) {
												$tv=convert_date_string($date);
											}
										}
										$value.=$tv.",";
									}
									$value=sort(explode(",",rtrim($value,",")));
									$value=implode(",",$value);
								}
							}*/
							if (stristr($type,"limit:") || $type=="text") {
                                                                $value = mb_convert_encoding($value,'HTML-ENTITIES','UTF-8');
                                                                $value = str_replace("'","&rsquo;",$value);
                                                                $value = strip_tags($value);
							}
							$result = $read_db->query("UPDATE entities SET value='".$value."' WHERE id='".$id."'");
						}
					}
				}
			}
			
			if (is_numeric($set_option) && ($set_option > 0)) {
				$result = $read_db->query("SELECT value FROM entities WHERE id='".$set_option."'");
				if($read_db->numRows($result) == 1) {
					$row = $read_db->fetchArray($result);
					$tval = $row['value'];
					$options=json_decode($tval,true);
					$options['id']=$eid;
					if ($options['allow-override']!="false") {
						$result = $read_db->query("UPDATE entities SET value='".json_encode($options)."' WHERE id='".$set_option."'");
					}
					//$message="entity.name saved and selected"
				}
			}
			if (!$result) {
				//error = "system";
			}
			//data = $id;		
			$read_db->freeResult();
		}
		elseif ($create_check == "1" && $type!="") {
			if ($type!="meta") {
				$etpl = cleanData($_POST['etpl']);
				if ($type=="page") {
					$result = $read_db->query("INSERT INTO collections (id,name,type) VALUES ('','".$_POST['title_text']."','".$type."')");
				}
				else {
					$result = $read_db->query("INSERT INTO collections (id,name,type) VALUES ('','".$type."','".$type."')");
				}
				$result = $read_db->query("SELECT LAST_INSERT_ID();");
				$row = $read_db->fetchArray($result);
				$cid = $row['LAST_INSERT_ID()'];
				$read_db->freeResult();
			}
			else {
				$cid = cleanData($_GET['id']);
			}
			if (is_array($_POST)) {
				foreach ($_POST as $key=>$value) {
					if ($key!="type" && $key!="create_check" && $key!="set_option" && $key!="submit" && $key!="submit_x" && $key!="etpl" && $key!="cname" && $key!="limit") {
						$value = cleanData($value);
						$eid="";
						list($name,$type,$eid)=explode("_",strtolower(cleanData($key)));
						if ($eid=="") {
							if ($type=="date" || $type=="daterange") {
								if (!isValidDateTime($value)) {
									list($month,$day,$year)=explode("/",$value);
									$date=$year."-".$month."-".$day." 00:00:00";
									if (isValidDateTime($date)) {
										$value=$date;
									}
								}
							}
							if (stristr($type,"limit:") || $type=="text") {
                                                                $value = mb_convert_encoding($value,'HTML-ENTITIES','UTF-8');
                                                                $value = str_replace("'","&rsquo;",$value);
                                                                $value = strip_tags($value);
							}
							$result = $read_db->query("INSERT INTO entities (id,name,type,value) VALUES ('','".str_replace("-space-"," ",$name)."','".$type."','".$value."')");
							$result = $read_db->query("SELECT LAST_INSERT_ID();");
							$row = $read_db->fetchArray($result);
							$eid = $row['LAST_INSERT_ID()'];
							$read_db->freeResult();
						}
						$result = $read_db->query("INSERT INTO cid_eid (cid,eid) VALUES ('".$cid."','".$eid."')");
					}
				}
				if ($type!="meta") {
					$result = $read_db->query("INSERT INTO cid_eid (cid,eid) VALUES ('".$cid."','".$etpl."')");
				}
			}
			if (is_numeric($set_option) && ($set_option > 0)) {
				$result = $read_db->query("SELECT value FROM entities WHERE id='".$set_option."'");
				if($read_db->numRows($result) == 1) {
					$row = $read_db->fetchArray($result);
					$tval = $row['value'];
					$options=json_decode($tval,true);
					$options['id']=$cid;
					if ($options['allow-override']!="false") {
						$result = $read_db->query("UPDATE entities SET value='".json_encode($options)."' WHERE id='".$set_option."'");
					}
					//$message="entity.name saved and selected"
				}
			}
			if (!$result) {
				//error = "system";
			}
			$read_db->freeResult();
		}
		elseif ($delete_check=="1" && $eid!="") {
			$result = $read_db->query("UPDATE collections SET active='n' WHERE id='".$eid."'");
			$result = $read_db->query("SELECT type FROM collections WHERE id='".$eid."'");
			$row = $read_db->fetchArray($result);
			$type=$row['type'];
			if (is_numeric($set_option) && ($set_option > 0)) {
				$result = $read_db->query("SELECT value FROM entities WHERE id='".$set_option."'");
				if($read_db->numRows($result) == 1) {
					$row = $read_db->fetchArray($result);
					$tval = $row['value'];
					$options=json_decode($tval,true);
					$options['type']=$type;
					$result = $read_db->query("UPDATE entities SET value='".json_encode($options)."' WHERE id='".$set_option."'");
					//$message="entity.name saved and selected"
				}
			}
		}
		/*elseif ($_POST['imgitems']!="" && $_POST['iid']!="") {
			$iid=cleanData($_POST['iid']);
			$iid=str_replace("item_","",$iid);
			$query = "UPDATE inventory SET images=\"".cleanData($_POST['imgitems'])."\" WHERE id='".$iid."' AND active='y'";
			$result = db->query($query);
			$errors['return_val']=$return_val;
			return true;
		}*/
		elseif (isset($_FILES['pictures'])) {
			list($type,$id,$options)=explode("_",strtolower(cleanData($_GET['imagesid'])));
			$filename=imageHandler();
			if (is_array($filename)) {
				//put in head error bar
				//$return_val=$filename['message_e'];
				echo "invalid";
				exit;
			}
			else {
				list($width,$height,$extra)=explode("x",cleanData($options));
				$h=$height;
				if (extension_loaded('gd') || extension_loaded('gd2')) {
					//Get Image size info
					list($width, $height, $image_type) = getimagesize($filename);
				}
				if ($h=="147") {
					$value="<a class=\"lightbox\" href=\"".str_replace("original","full",$filename)."?".mt_rand()."\"><img src=\"".$filename."?".mt_rand()."\" width=\"".$width."\" height=\"".$height."\" /></a>";
				}
				else {
					$value="<img src=\"".$filename."?".mt_rand()."\" width=\"".$width."\" height=\"".$height."\" />";
				}
				if (is_numeric($id) && $id > 0) {
					$result = $read_db->query("INSERT INTO entities (id,name,type,value) VALUES ('".$id."','images','image:".$options."','".$value."') ON DUPLICATE KEY UPDATE value='".$value."'");
				}
				$img=str_replace("original","147x147",$filename);
				echo "<li><a href=\"".$filename."?".mt_rand()."\"><img src=\"".$img."?".mt_rand()."\" width=\"147\" height=\"147\" /></a></li>";
				exit;
			}
			$errors['return_val']=$return_val;
			return $errors;
		}
	}
}

function emailit() {
	$content=email_action();
	if ($content=="true") {
    return "<p>Thank you ".$_POST["name"]." for signing up for our mailing list!</p>";
  }
  else {
		return "<p>Your signup failed, check the address and try again.</p>";
	}
}

function email_action() {
if (validateEmail($_POST["email"])) {
				/***************************************************\
				 * PHP 4.1.0+ version of email script. For more
				 * information on the mail() function for PHP, see
				 * http://www.php.net/manual/en/function.mail.php
				\***************************************************/

				// First, set up some variables to serve you in
				// getting an email.  This includes the email this is
				// sent to (yours) and what the subject of this email
				// should be.  It's a good idea to choose your own
				// subject instead of allowing the user to.  This will
				// help prevent spam filters from snatching this email
				// out from under your nose when something unusual is put.

				$sendTo = "jeffrey@wearecharette.com";
				$subject = "Mailing List Sign-up from smartdesign.com";

				// variables are sent to this PHP page through
				// the POST method.  $_POST is a global associative array
				// of variables passed through this method.  From that, we
				// can get the values sent to this page from Flash and
				// assign them to appropriate variables which can be used
				// in the PHP mail() function.

				// header information not including sendTo and Subject
				// these all go in one variable.  First, include From:
				$headers = "From: " . strip_tags($_POST["name"]) . "\r\n";
				// next include a replyto
				$headers .= "Reply-To: " . strip_tags($_POST["email"]) . "\r\n";
				// often email servers won't allow emails to be sent to
				// domains other than their own.  The return path here will
				// often lift that restriction so, for instance, you could send
				// email to a hotmail account. (hosting provider settings may vary)
				// technically bounced email is supposed to go to the return-path email
				$headers .= "Return-path: " . strip_tags($_POST["email"]);

				// now we can add the content of the message to a body variable
				$message = "Name: " . strip_tags($_POST["name"]) . "
" . "Email: " . strip_tags($_POST["email"]);

				// once the variables have been defined, they can be included
				// in the mail function call which will send you an email
				mail($sendTo, $subject, $message, $headers);
	return true;
}
else {
        $errors['form_message_e'] = "Invalid email address.<br />";
        foreach ($_POST as $key => $value) {
                $key_name=str_replace("_"," ",$key);
                if ($value=="" && !stristr($options, $key)) {
                        $key_error=str_replace(" ","_",$key_name)."_e";
                        $errors[$key_error].=ucwords($key_name) ." was left blank.<br />";
                }
        }
}
return $errors;
}

function imageHandler() {
	ini_set("memory_limit","200M");
	$path="uploaded/images/";
	list($trash,$id,$options)=explode("_",strtolower(cleanData($_GET['imagesid'])));
	list($width,$height,$extra)=explode("x",cleanData($options));
	
	if(isset($_FILES['pictures'])) {
		foreach ($_FILES['pictures']['error'] as $key => $err) {
			if(!empty($_FILES['pictures']['error'][$key])) {
				switch($_FILES['pictures']['error'][$key])
				{
					case '1':
						$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
						break;
					case '2':
						$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
						break;
					case '3':
						$error = 'The uploaded file was only partially uploaded';
						break;
					case '4':
						$error = 'No file was uploaded.';
						break;
			
					case '6':
						$error = 'Missing a temporary folder';
						break;
					case '7':
						$error = 'Failed to write file to disk';
						break;
					case '8':
						$error = 'File upload stopped by extension';
						break;
					case '999':
					default:
						$error = 'No error code avaiable'.$_FILES['pictures']['error'];
				}
			}
			elseif(empty($_FILES['pictures']['tmp_name'][$key]) || $_FILES['pictures']['tmp_name'][$key] == 'none') {
				$error = 'Please choose a file before you click upload.';
			}
			else {
				if (!(stristr($_FILES['pictures']['type'][$key],'image')) || stristr($_FILES['pictures']['name'][$key],'.php') || stristr($_FILES['pictures']['name'][$key],'.js') || stristr($_FILES['pictures']['name'][$key],'.swf') || stristr($_FILES['pictures']['name'][$key],'.as') || stristr($_FILES['pictures']['name'][$key],'.pl') || stristr($_FILES['pictures']['name'][$key],'.phps') || !(stristr($_FILES['pictures']['name'][$key],'.'))) {
					$error="The file ".$_FILES['pictures']['name'][$key]." is not a valid image type or filename.";
				}
				else {
					if ($_FILES['pictures']['size'][$key] > 11) {
						if ($err == UPLOAD_ERR_OK) {
							$dir_id=$path.$id;
							if(is_dir($dir_id)==FALSE) {mkdir($dir_id, 0755);}
							
							$tmp_name = $_FILES["pictures"]["tmp_name"][$key];
							$name = sanitize_filename($_FILES["pictures"]["name"][$key]);
							list($name,$extension)=explode(".",$name);
							$name = $dir_id."/full.".$extension;
							
							if (exif_imagetype($tmp_name)) {
								if ($width=="" && $height=="") {
									$returnval=resize($tmp_name,982,648,$name);
								}
								else {
									if ($height=="147") {
										//should be using $extra
										$returnval=resize($tmp_name,$width,$height,$name);
									}
									else {
										$returnval=resize($tmp_name,$width,$height,$name,FALSE,TRUE);
									}
									$returnval=resize($tmp_name,982,648,$dir_id."/full.".$extension);
								}
								$x++;
								if (is_array($returnval)) {
									$error=$returnval['message_e'];
								}
								else {
									$returnval=resize($tmp_name,147,147,$dir_id."/147x147.".$extension,TRUE);
									if (is_array($returnval)) {
										$error=$returnval['message_e'];
									}
									else {
										$returnval=resize($tmp_name,314,314,$dir_id."/314x314.".$extension,FALSE,TRUE);
										if (is_array($returnval)) {
											$error=$returnval['message_e'];
										}
										else {
											$returnval=resize($tmp_name,481,481,$dir_id."/481x481.".$extension,TRUE);
											if (is_array($returnval)) {
												$error=$returnval['message_e'];
											}
											else {
												$returnval=resize($tmp_name,314,481,$dir_id."/314x481.".$extension,FALSE,TRUE);
												if (is_array($returnval)) {
													$error=$returnval['message_e'];
												}
												else {
													$returnval=resize($tmp_name,648,481,$dir_id."/648x481.".$extension,TRUE);
													if (is_array($returnval)) {
														$error=$returnval['message_e'];
													}
													else {
														$returnval=resize($tmp_name,982,481,$dir_id."/982x481.".$extension,FALSE,TRUE);
														if (is_array($returnval)) {
															$error=$returnval['message_e'];
														}
													}
												}
											}
										}
									}
								}
								$msg=$x . " file(s) uploaded successfully.";
							}
							else {
								$error="Error loading, file contains errors.";
							}
						}
						else {
							$error="The file ".$_FILES['pictures']['name'][$key]." is too big.";
						}
					}
					else {
						$error="The file ".$_FILES['pictures']['name'][$key]." is empty, please check the file.";
					}
				}
			}
		}
		@unlink($_FILES['pictures']);
	}
	if ($error!="") {
		$errors['message_e']=$error;
		return $errors;
	}
	else {
		return $name;
	}
}

function user_login_action($read_db) {
	if (is_array($_POST)) {
		$_POST = array_map("cleanData", $_POST);
	}
	
	if(isset($_POST["submit"]) || isset($_POST["submit_x"]) || isset($_POST["submit_y"])) {
		if(!(validateEmail($_POST['email']))) {$errors['email_e']="Use your full email address. ex. yourname@domain.com";}
		if(strlen($_POST['password']) <= 7) {$errors['password_e']="Your password is required to sign-in and must be at least 8 characters long.";}
		if (!is_array($errors)) {
			$loginQuery = "SELECT id, first, last, email, username FROM users WHERE email = '". addslashes($_POST['email']) . "' AND password = '". sha1($_POST['password']) . "'";
			$result = $read_db->query($loginQuery);
			if($read_db->numRows($result) == 1) {
				$userData = $read_db->fetchObject($result);
				$name=$userData->first . " " . $userData->last;
				$_SESSION["user"] = new User($userData->id, $name, $userData->email, $userData->username);
				$_SESSION['token'] = md5(uniqid(rand(),TRUE));
				$_SESSION["IP"] = $_SERVER["REMOTE_ADDR"];
				$_SESSION["timestamp"] = time();
				// combine guest cart
				// $read_db->query("UPDATE cart SET loginID='".$userData->id."' WHERE session='".newobject_id."' AND active='y'");
				header('Location: http://smart.wearecharette.com/edit.html');
			}
			else {
				$errors['message_e']="Incorrect email/password combination, ".$_POST['email'].".";
			}
		}
	}
	unset($_POST['password']);
	$_POST=array_merge($_POST,(array)$errors);
return $_POST;
}

function user_logout_action() {
	$_POST['email']=$_SESSION["user"]->email;
	// Initialize the session.
	// If you are using session_name("something"), don't forget it now!
	session_start();
	// Unset all of the session variables.
	$_SESSION = array();
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	// Finally, destroy the session.
	session_destroy();
return false;
}

function get_tree($root=1,$read_db) {
	$result = $read_db->query('SELECT id,name,parent FROM collections WHERE parent='.$root.' && type="page" && collections.active="y" ORDER BY sort ASC;');
	$numrows = $read_db->numRows($result);
	if ($numrows>0) {
		$tree.="<ul>";
		while ($row = $read_db->fetchArray($result)) {
			$tree.="<li><a title=\"".$row['name']."\" href=\"edit.html?id=".$row['id']."\">".str_replace(" Of "," of ",ucwords(str_replace("_"," ",str_replace("_fslash_"," / ",str_replace("_and_"," &amp; ",$row['title'])))))."</a>{id=".$row['id']."}</li>";
			$ids.=$row['id'].",";
		}
		$tree.="</ul>";
		$ids=rtrim($ids,",");
		$result = $read_db->query('SELECT id,name,parent FROM content WHERE parent IN ('.$ids.') && type="page" ORDER BY sort ASC;');
		while ($row = $read_db->fetchArray($result)) {
			$list[$row['parent']].="<li><a title=\"".$row['name']."\" href=\"edit.html?id=".$row['id']."\">".str_replace(" Of "," of ",ucwords(str_replace("_"," ",str_replace("_fslash_"," / ",str_replace("_and_"," &amp; ",$row['title'])))))."</a></li>";
		}
		foreach ($list as $key=>$value) {
			$tree=str_replace("{id=".$key."}","<ul>".$value."</ul>",$tree);
		}
	}

	return "<ul id=\"sitemap\"><li><a href=\"".$_GET['x']."?id=1\">Home (index)</a>".$tree."</ul></ul>";
}

function display_menu($root,$parent,$read_db) {
	$mode=cleanData($_GET['mode']);
	if ($mode!="") {
		$mode="?mode=".$mode;
	}
	if ($parent=="" || $parent==0) {
		$parent=2;
	}
	$result = $read_db->query('SELECT id,name,parent FROM collections WHERE parent='.$parent.' && type="page" && collections.active="y" ORDER BY sort ASC;');
	$numrows = $read_db->numRows($result);
	if ($numrows>0) {
		$list.="<ul>";
		while ($row = $read_db->fetchArray($result)) {
			$a++;
			if ($root==$row['id']) {
				$class="class=\"sel\"";
			}
			else {
				$class="";
			}
			$list.="<li $class><a href=\"".strtolower($row['name']).".html".$mode."\">".str_replace(" Of "," of ",ucwords(str_replace("_"," ",str_replace("_fslash_"," / ",str_replace("_and_"," &amp; ",$row['name'])))))."</a></li>";
			if (($a%4)==0) {
				$list.="</ul>";
				if ($numrows!=$a) {
					$list.="<ul>";			
				}
			}
		}
		if (($a%4)!=0) {
			$list.="</ul>";
		}
	}
	if ($parent!="" && $parent!=2) {
		$root=$parent;
		$result = $read_db->query('SELECT id,name,parent FROM collections WHERE id='.$root.' && type="page" AND collections.active="y";');
		$numrows = $read_db->numRows($result);
		if ($numrows>0) {
			$row = $read_db->fetchArray($result);
			$parent=$row['parent'];
			if ($parent!="") {
				$result = $read_db->query('SELECT id,name,parent FROM collections WHERE parent='.$parent.' && type="page" AND collections.active="y" ORDER BY sort ASC;');
				$numrows = $read_db->numRows($result);
				if ($numrows>0) {
					$list2.="<ul>";
					while ($row = $read_db->fetchArray($result)) {
						$t++;
						if ($root==$row['id']) {
							$class="class=\"sel\"";
						}
						else {
							$class="";
						}
						$list2.="<li $class><a href=\"".strtolower($row['name']).".html".$mode."\">".str_replace(" Of "," of ",ucwords(str_replace("_"," ",str_replace("_fslash_"," / ",str_replace("_and_"," &amp; ",$row['name'])))))."</a></li>";
						if (($t%4)==0) {
							$list2.="</ul>";
							if ($numrows!=$t) {
								$list2.="<ul>";			
							}
						}
					}
					if (($t%4)!=0) {
						$list2.="</ul>";
					}
				}
			}
		}
	}
	elseif ($root!="2") {
		$result = $read_db->query('SELECT id,name,parent FROM collections WHERE parent='.$root.' && parent!=1 && type="page" AND collections.active="y" ORDER BY sort ASC;');
		$numrows = $read_db->numRows($result);
		if ($numrows>0) {
			$list.="<ul>";
			while ($row = $read_db->fetchArray($result)) {
				$r++;
				if ($root==$row['id']) {
					$class="class=\"sel\"";
				}
				else {
					$class="";
				}
				$list.="<li $class><a href=\"".strtolower($row['name']).".html".$mode."\">".str_replace(" Of "," of ",ucwords(str_replace("_"," ",str_replace("_fslash_"," / ",str_replace("_and_"," &amp; ",$row['name'])))))."</a></li>";
				if (($r%4)==0) {
					$list.="</ul>";
					if ($numrows!=$r) {
						$list.="<ul>";			
					}
				}
			}
			if (($r%4)!=0) {
				$list.="</ul>";
			}
		}
	}
	return $list2.$list;
}


/*	HELPERS	*/

function sanitize_filename($filename, $forceextension="") {
	/*
	1. Remove leading and trailing dots
	2. Remove dodgy characters from filename, including spaces and dots except last.
	3. Force extension if specified
	*/
	
	$defaultfilename = "none";
	$dodgychars = "[^0-9a-zA-Z()_-]"; // allow only alphanumeric, underscore, parentheses and hyphen
	
	$filename = preg_replace("/^[.]*/","",$filename); // lose any leading dots
	$filename = preg_replace("/[.]*$/","",$filename); // lose any trailing dots
	$filename = $filename?$filename:$defaultfilename; // if filename is blank, provide default
	
	$lastdotpos=strrpos($filename, "."); // save last dot position
	$filename = preg_replace("/$dodgychars/","-",$filename); // replace dodgy characters
	$afterdot = "";
	if ($lastdotpos !== false) { // Split into name and extension, if any.
	$beforedot = substr($filename, 0, $lastdotpos);
	if ($lastdotpos < (strlen($filename) - 1))
	$afterdot = substr($filename, $lastdotpos + 1);
	}
	else // no extension
	$beforedot = $filename;
	
	if ($forceextension)
	$filename = $beforedot . "." . $forceextension;
	elseif ($afterdot)
	$filename = $beforedot . "." . $afterdot;
	else
	$filename = $beforedot;
	
	return strtolower($filename);
}

function resize($img, $thumb_width, $thumb_height, $newfilename, $square=FALSE, $crop=FALSE) {
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        $error="GD is not loaded";
        return false;
    }

    //Get Image size info
    list($width_orig, $height_orig, $image_type) = getimagesize($img);
	if ($thumb_width=="") {$thumb_width=$width_orig;}
	if ($thumb_height=="") {$thumb_height=$height_orig;}
	$max_width=$thumb_width;
	$max_height=$thumb_height;

	switch ($image_type) {
		case 1: $im = imagecreatefromgif($img); break;
		case 2: $im = imagecreatefromjpeg($img);  break;
		case 3: $im = imagecreatefrompng($img); break;
		$error="Unsupported filetype!";  break;
	}
	
	$src_x=0;
	$src_y=0;
	if ($crop) {
		$ratio_orig = $width_orig/$height_orig;
	   
		if ($thumb_width/$thumb_height > $ratio_orig) {
		   $thumb_height = $thumb_width/$ratio_orig;
		}
		else {
		   $thumb_width = $thumb_height*$ratio_orig;
		}
	   
		$src_x = $thumb_width/2;  //horizontal middle
		$src_y = $thumb_height/2; //vertical middle
	}
	elseif ($square){
		if($width_orig>$height_orig) {
			$src_x = ceil(($width_orig-$height_orig)/2);
			$width_orig=$height_orig;
			$width_orig=$width_orig;
		}else{
			$src_y = ceil(($height_orig-$width_orig)/2);
			$height_orig=$width_orig;
			$width_orig=$width_orig;
		}
	}
	else {
		if ($width_orig!=$thumb_width || $height_orig!=$thumb_height) {
			if ($height_orig>$width_orig) {
				$aspect_ratio = (float) $width_orig / $height_orig;
				/*** calulate the thumbnail width based on the height ***/
				$thumb_width = round($thumb_height * $aspect_ratio);
			   
			
				while($thumb_width>$max_height+1)
				{
					$thumb_height-=1;
					$thumb_width = round($thumb_height * $aspect_ratio);
				}
			}
			else {
				$aspect_ratio = (float) $height_orig / $width_orig;
				/*** calulate the thumbnail height based on the height ***/
				$thumb_height = round($thumb_width * $aspect_ratio);
			   
			
				while($thumb_height>$max_width+1) {
					$thumb_width-=1;
					$thumb_height = round($thumb_width * $aspect_ratio);
				}
			}
		}
	}
		
	$newImg = imagecreatetruecolor(round($thumb_width), round($thumb_height));
   
	/* Check if this image is PNG or GIF, then set if Transparent*/ 
	if(($image_type == 1) OR ($image_type==3)) {
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $thumb_width, $thumb_height, $transparent);
	}
	else {
		imagefill($newImg, 0, 0, imagecolorallocate($newImg, 255, 255, 255));
	}
	imagecopyresampled($newImg, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig);
	if ($crop) {
		$thumb = imagecreatetruecolor($max_width, $max_height);
		imagecopyresampled($thumb, $newImg, 0, 0, ($src_x-($max_width/2)), ($src_y-($max_height/2)), $max_width, $max_height, $max_width, $max_height);
	   
		//Generate the file, and rename it to $newfilename
		switch ($image_type) {
			case 1: imagegif($thumb,$newfilename); break;
			case 2: imagejpeg($thumb,$newfilename, 60);  break;
			case 3: imagepng($thumb,$newfilename, 5); break;
			$error="Failed resize image!";  break;
		}
	}
	else {
		//Generate the file, and rename it to $newfilename
		switch ($image_type) {
			case 1: imagegif($newImg,$newfilename); break;
			case 2: imagejpeg($newImg,$newfilename, 60);  break;
			case 3: imagepng($newImg,$newfilename, 5); break;
			$error="Failed resize image!";  break;
		}
	}
	
	if ($error!="") {
		$errors['message_e']=$error;
		return $errors;
	}
	else {
		return $newfilename;
	}
}

	function cleanData($data) {
		if(get_magic_quotes_gpc()){
			$data = stripslashes($data);
		}
		return mysql_real_escape_string(trim($data));
	}
	
	function isValidDateTime($dateTime) {
		if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
			if (checkdate($matches[2], $matches[3], $matches[1])) {
				return true;
			}
		}
		return false;
	}
	
	function convert_date_string($date_string) {
		list($date, $time) = explode(" ", $date_string);
		list($hours, $minutes, $seconds) = explode(":", $time);
		list($year, $month, $day) = explode("-", $date);
		return mktime($hours, $minutes, $seconds, $month, $day, $year);
	}

	function print_input($name,$label,$data,$value,$type,$extra="") {	
		if ($type == 'text' || $type == 'link') {
			if ($label) {
				$output.="<label for=\"$name\">$label</label> ";
			}
			$output.="<input $extra type=\"text\" name=\"$name\" value=\"$value\" />";	
		}
		elseif ($type == 'hidden') {
			$output.="<input $extra type=\"hidden\" name=\"$name\" value=\"$value\" />";	
		}
		elseif ($type == 'checkboxes') {
			if ($label) {
				$output = "<br /><label for=\"$name\">$label</label>";
			}
			$name=$name . "[]";
			$i=0;
			$data=explode(",",$data);
			foreach($data as $el) {
				if ($value==$el) {
					$output.="$el <input $extra type=\"checkbox\" name=\"$name\" value=\"$el\" CHECKED />";
				}
				else {
					$output.="$el <input $extra type=\"checkbox\" name=\"$name\" value=\"$el\" />";
				}
				if ($i>3) {
					$output.="<br />";
					$i=0;
				}
				$i++;
			}
			$output.="<br />";
		}
		elseif ($type == 'checkbox') {
			if ($label) {
				$output = "<label style=\"display:inline;\" for=\"$name\">$label</label>";
			}
			if ($value=="true") {
				$output.="<input $extra type=\"checkbox\" name=\"$name\" value=\"true\" CHECKED />";
			}
			else {
				$output.="<input $extra type=\"checkbox\" name=\"$name\" value=\"true\" />";
			}
		
			$output.="<br />";
		}
		elseif ($type == 'textarea' || $type == 'content') {
			if ($label) {
				$output.="<label for=\"$name\">$label</label>";
			}
			$output.="<textarea $extra name=\"$name\" wrap=\"virtual\">$value</textarea><br />";
		}
		elseif ($type == 'hidden_textarea') {
			$output.="<textarea style=\"display:none;\" $extra name=\"$name\" wrap=\"virtual\">$value</textarea>";
		}
		elseif ($type == 'select_list') {
			if ($label) {
				$output = "<label for=\"$name\">$label</label>";
			}
			$output.="<select $extra name=\"$name\" id=\"$name\">";
			//$output .= "<option value=\"new\">create new</option>";
			foreach ($data as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $key => $val) {
						if ($val!="") {
							$special=array(' &nbsp; ','&raquo; ','&oplus; ');
							$val=str_replace($special,"",$val);
							if ($val == $value) {
								$output .= "<option selected value=\"" . $val . "\">" . $val . "</option>";
							}
							else {
								$output .= "<option value=\"" . $val . "\">" . $val . "</option>";
							}
						}
					}
				}
			}
			$output.="</select>";
			$output.=print_input("new","","","","text"," class=\"new\" style=\"display:none\"");
		}
		elseif ($type == 'select_array') {
			if ($label) {
				$output = "<label for=\"$name\">$label</label>";
			}
			$output.="<select $extra name=\"$name\" id=\"$name\">";
			if (is_array($data)) {
				foreach ($data as $key => $val) {
					if ($val!="") {
						$special=array(' &nbsp; ','&raquo; ','&oplus; ');
						$val=str_replace($special,"",$val);
						if ($key == $value) {
							$output .= "<option selected value=\"" . $key . "\">" . $val . "</option>";
						}
						else {
							$output .= "<option value=\"" . $key . "\">" . $val . "</option>";
						}
					}
				}
			}
			$output.="</select>";
		}
		/*elseif ($type == 'select_db') {
			$output.="<select $extra name=\"$name\" id=\"$name\">";
			if ($label) {
				$output .= "<option value=\"-1\">".$label."</option><option class=\"multi\" value=\"-1\">multi-race event</option>";
			}
			$result=db->query("SELECT * FROM $tbl ORDER BY type,id desc");
			while ($row=db->fetchArray($result)) {
				if ($data == $row['id']) {
					$output .= "<option selected value=\"".$row['id']."\">".$row['title']." (swim ".$row['swim'].", bike ".$row['cycle'].", run ".$row['run'].")</option>";
				}
				else {
					$output .= "<option value=\"".$row['id']."\">".$row['title']." (swim ".$row['swim'].", bike ".$row['cycle'].", run ".$row['run'].")</option>";
				}
			}
			$output.="</select>";
		}*/
		elseif ($type == 'multi_select') {
			$output.="<select $extra name=\"$name\" id=\"$name\" multiple>";
			$result=$read_db->query("SELECT id, title FROM $tbl ORDER BY id");
			while ($row=$read_db->fetchArray($result)) {
				if ($value == $row['id']) {
					$output .= "<option selected value=\"" . $row['id'] . "\">" . $row['title'] . "</option>";
				}
				else {
					$output .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "</option>";
				}
			}
			$output.="</select>";
		}
		elseif ($type == 'number') {
			if ($label) {
				$output.="<label for=\"$name\">$label</label> ";
			}
			$output.="<input $extra type=\"number\" name=\"$name\" value=\"$value\" /><br />";	
		}
		elseif ($type == 'password') {
			if ($label) {
				$output.="<label for\"$name\">$label</label> ";
			}
			$output.="<input $extra type=\"password\" name=\"$name\" value=\"$value\" /><br />";	
		}
		elseif ($type == 'file') {
			if ($label) {
				$output.="<label for=\"$name\">$label</label> ";
			}
			$output.="<input $extra type=\"file\" name=\"$name\" value=\"$value\" /><br />";	
		}
		elseif ($type == 'none') {
			$output.="<br /><label for\"$name\">$label</label> <br /><br />";
		}
	return $output;
	}
function xml_character_encode($string, $trans='') {
  $trans = (is_array($trans)) ? $trans : get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
  foreach ($trans as $k=>$v)
    $trans[$k]= "&#".ord($k).";";

  return strtr($string, $trans);
}
?>
