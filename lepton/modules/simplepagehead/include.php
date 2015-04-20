<?php

/**
 *  @module         SimplePageHead
 *  @version        see info.php of this module
 *  @authors        Chio, Thorn, FreeSbee, cms-lab
 *  @copyright      2006-2011 Chio
 *  @copyright      2011-2014 cms-lab 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 */

// Function to display a nearly complete html head, also for news module pages. 
// Invoke function from template after "<head>" with:
//    simplepagehead();     for xhtml
//    simplepagehead("");   for html4

// Displays: Charset, Title, Meta-Description, Meta-Keywords, Meta-Language, meta name="generator"
// Plus (default): Link to favicon.ico (in TEMPLATE_DIR), no-ImageToolbar and "robots"="noindex,nofollow" on some pages.


if (!function_exists('simplepagehead')) {
	function simplepagehead($endtag="/", $norobotstag=1, $notoolbartag=1, $favicon=1, $generator=1) {



		// Define module vars
		// ******************
		
		// To add other modules extend this list by adding new lines, e.g.:
		// $module['module_name'] = array('table_name', 'id_name', 'title_field_name', 'description_field_name', 'keywords_field_name');
		$module['news'] = array('mod_news_posts', 'post_id', 'title', 'content_short', '');
		$module['bakery'] = array('mod_bakery_items', 'item_id', 'title', 'description', '');
		$module['gallery'] = array('mod_gallery_images', 'image_id', 'title', 'description', '');
		$module['gocart'] = array('mod_gocart_products', 'image_id', 'title', 'description','');
		$module['news2'] = array('mod_news2_posts', 'post_id', 'title', 'description', 'keywords');
		$module['topics'] = array('mod_topics', 'topic_id', 'title', 'description', 'keywords');
		
		//$module['module_name'] = array('table_name', 'id_name', 'title_field_name', 'description_field_name');



		// Register outside object
		global $database;
		global $wb;
		global $page_id;
		global $section_id;

		// Set defaults
		$the_title = ''; //$wb->page_title;
		$the_description = ''; //$wb->page_description; 
		$the_keywords = ''; //$wb->page_keywords;

		// Look for the module name of the current section		
		if(isset($page_id) && isset($section_id)) {
			$sections_query = $database->query("SELECT module FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND section_id = '$section_id'");
			$section = $sections_query->fetchRow();
			
			// Check if the module is added to the module list
			$module_name = $section['module'];
			if(array_key_exists($module_name, $module)) {

				// Prepare vars for the query string depending on the module
				$table_name = $module[$module_name][0];
				$id_name = $module[$module_name][1];
				$title_field_name = $module[$module_name][2];
				$description_field_name = $module[$module_name][3];
				$keywords_field_name = $module[$module_name][4];
				
				// Register outside object
				global $$id_name;
				$id_value = $$id_name;
	
				// Get the header data out of the DB
				$query = "SELECT $title_field_name, $description_field_name FROM ".TABLE_PREFIX."$table_name WHERE $id_name = '$id_value'";
				if ($keywords_field_name !='') {$query = "SELECT $title_field_name, $description_field_name, $keywords_field_name FROM ".TABLE_PREFIX."$table_name WHERE $id_name = '$id_value'";}
				$query_module = $database->query($query);
				$results_array = $query_module->fetchRow();
				$the_title = $results_array[$title_field_name]; 
				$the_description = strip_tags($results_array[$description_field_name]);
				if ($keywords_field_name !='') {$the_keywords = strip_tags($results_array[$keywords_field_name]);}
			}
		}

		if ($the_description == '') {
			$the_description = $wb->page_description;
		}
		else {
			$the_description = str_replace('"', '', $the_description); 
			if (strlen($the_description) > 160) {
				if(preg_match('/.{0,160}(?:[.!?:,])/su', $the_description, $match)) {   //thanks to thorn	
					$the_description = $match[0];
				}			
				if (strlen($the_description) > 160) {
					$pos = strpos($the_description, " ", 120);
					if ($pos > 0) {
						$the_description = substr($the_description, 0,  $pos);
					}					
				}
			}
		}
		
		if ($the_title=='') {$the_title = $wb->page_title; }
		if (strlen($the_title) < 15) {$the_title = WEBSITE_TITLE. " - " .$the_title; }
		

		if ($the_description == '') { $the_description = $wb->page_description; }
		if ($the_description == '') { $the_description = WEBSITE_DESCRIPTION; }
		
		if ($the_keywords == '') { $the_keywords = $wb->page_keywords; }
		if ($the_keywords == '') { $the_keywords = WEBSITE_KEYWORDS; }
		
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset='; if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; } echo "\"$endtag>\n";
		echo "<title>$the_title</title>\n";
		echo '<meta name="description" content="'.$the_description."\"$endtag>\n";
		echo '<meta name="keywords" content="'. $the_keywords ."\"$endtag>\n";
	
		$the_language = strtolower(LANGUAGE);
		echo "<meta name=\"language\" content=\"$the_language\"$endtag>\n";
		
		if ($norobotstag == 1) {
			$indexstring = '';
			if ($page_id === 0) {$indexstring = '<meta name="robots" content="noindex,nofollow"'. "$endtag>\n";}
			echo $indexstring; 
		}
		
		if ($generator == 1) {echo '<meta name="generator" content="LEPTON; http://www.lepton-cms.org"'."$endtag>\n";}
		if ($notoolbartag == 1) {echo '<meta http-equiv="imagetoolbar" content="no"'."$endtag>\n"; }		
		if ($favicon == 1) {echo '<link rel="shortcut icon" href="'.TEMPLATE_DIR.'/img/favicon.ico'."\"$endtag>\n"; }
		
	}
}

?>