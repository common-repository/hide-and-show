<?php
/*
Plugin Name: hide_and_show
Plugin URI: http://feelfree.homelinux.com
Description: hide and show links in the sidebar
Version: 0.4
Author: cedric
Author URI: http://feelfree.homelinux.com
*/

class hide_show {
	function links($alpha) {
		$dir=substr(__FILE__,strlen($_SERVER['DOCUMENT_ROOT']),strrpos(__FILE__,"/")-strlen($_SERVER['DOCUMENT_ROOT']));
		//echo $dir;
		global $wpdb;
		if ($alpha==1){
			$results=$wpdb->get_results("select link_id,link_url,link_name,link_category,cat_id,cat_name from $wpdb->links,$wpdb->linkcategories where link_category=cat_id order by cat_id,link_name ASC");}
		else {
			$results=$wpdb->get_results("select link_id,link_url,link_name,link_category,cat_id,cat_name from $wpdb->links,$wpdb->linkcategories where link_category=cat_id order by cat_id,link_id ASC");
		}
		$cat='';
		foreach ($results as $item) {
			if ($cat!=$item->cat_name) {
				if ($cat!="") {
					echo "</ul>";
				}
				$toggle='-1';
				$toggle=$_COOKIE["list".$item->cat_id];
				echo "<h2><a href='javascript:hl_openCloseOptions(\"list$item->cat_id\",\"list$item->cat_id\",\"0\",\"".$dir."\");'>";
				if ($toggle==1) {
					//echo "<img id='img_list".$item->cat_id."' src='/wp-content/plugins/hide_and_show/moins.png'>";
					echo "<img id='img_list".$item->cat_id."' src='".$dir."/moins.png'>";
				} else {
					//echo "<img id='img_list".$item->cat_id."' src='/wp-content/plugins/hide_and_show/plus.png'>";
					echo "<img id='img_list".$item->cat_id."' src='".$dir."/plus.png'>";
				}
				echo "&nbsp;".$item->cat_name."</h2>";
				echo "</a>";
				if ($toggle==1) {
					echo "<ul id='list$item->cat_id' style='display:block;visibility:visible;'>";
				} else {
					echo "<ul id='list$item->cat_id' style='display:none;visibility:visible;'>";
				}
				$cat=$item->cat_name;
			}
			echo "<li><a href='".$item->link_url."'>".$item->link_name."</a></li>";
		}
		echo "</ul>";
	}
}

function hide_show_javascript_func() {
echo "<script language=\"Javascript\">\n";
echo "function hl_getE(id)\n";
echo "{\n";
echo "	if(document.getElementById) {\n";
echo "		return document.getElementById(id);\n";
echo "	} else if(document.all) {\n";
echo "		return document.all[id];\n";
echo "	} else return;\n";
echo "}\n";

echo "function hl_openClose(id,mode,dir)\n";
echo "{\n";
echo "	element = hl_getE(id);\n";
echo "	img = hl_getE('img_'+id);\n";
echo "	if(element.style) {\n";
echo "		if(mode == 0) {\n";
echo "			if(element.style.display == 'block' ) {\n";
echo "				element.style.display = 'none';\n";
echo "				img.src = dir+'/plus.png';\n";
echo "				img.alt='[+]';\n";
echo "			} else {\n";
echo "				element.style.display = 'block';\n";
echo "				img.src = dir+ '/moins.png';\n";
echo "				img.alt='[-]';\n";
echo "			}\n";
echo "		} else if(mode == 1) {\n";
echo "			element.style.display = 'block';\n";
echo "			img.src = dir+'/moins.png';\n";
echo "			img.alt='[-]';\n";
echo "		} else if(mode == -1) {\n";
echo "			element.style.display = 'none';\n";
echo "			img.src = dir+'/plus.png';\n";
echo "			img.alt='[+]';\n";
echo "		}\n";
echo "	}\n";
echo "}\n";

echo "function hl_createCookie(name,value) {\n";
echo "	document.cookie = name+'='+value+';path=/;';\n";
echo "}\n";

echo "function hl_readCookie(name) {\n";
echo "	var nameEQ = name + '=';\n";
echo "	var ca = document.cookie.split(';');\n";
echo "	for(var i=0;i < ca.length;i++) {\n";
echo "		var c = ca[i];\n";
echo "		while (c.charAt(0)==' ') c = c.substring(1,c.length);\n";
echo "			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);\n";
echo "	}\n";
echo "	return null;\n";
echo "}\n";

echo "function hl_openCloseOptions(id,cookie_name,mode,dir)\n";
echo "{\n";
echo "	if (mode != null) {\n";
echo "		hl_openClose(id,mode,dir);\n";
echo "		e = hl_getE(id);\n";
echo "		if (e.style.display == 'block') {\n";
echo "			cookie_value = '1';\n";
echo "		} else {\n";
echo "			cookie_value = '-1';\n";
echo "		}\n";
echo "		hl_createCookie(cookie_name,cookie_value);\n";
echo "	} else {\n";
echo "		cookie = hl_readCookie(cookie_name);\n";
echo "		if (!cookie) {\n";
echo "			cookie = -1\n";
echo "		}\n";
echo "		hl_openClose(id,cookie);\n";
echo "	}\n";
echo "}\n";
echo "</script>\n";

}

add_action('wp_head', 'hide_show_javascript_func');
?>
