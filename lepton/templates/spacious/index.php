<?php

/**
 *  @template       spacious
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2010-2014 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
    include( LEPTON_PATH . '/framework/class.secure.php' );
} 
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
    {
        $root .= $oneback;
        $level += 1;
    } 
    if ( file_exists( $root . '/framework/class.secure.php' ) )
    {
        include( $root . '/framework/class.secure.php' );
    } 
    else
    {
        trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
    }
}
// end include class.secure.php

//initialize twig template engine
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

// prependpath to make sure, twig is looking in this module template folder first
$loader->prependPath( dirname(__FILE__)."/templates/" );

//load classes
require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader );
// End of template-engines settings.

//Selecting the corrrect template
switch (PAGE_ID ) {
	case 3: 
		$template_name = "start.lte";
		break;
	case 6: 
		$template_name = "left_sidebar.lte";
		break;
	case 7: 
		$template_name = "right_sidebar.lte";
		break;
	case 8: 
		$template_name = "full_width.lte";
		break;			
	default:
		$template_name = "no_sidebar_centered.lte";
}

//    AnyNews config ...    Keep in mind to set all params in this way
$config = array(
    'group_id_type' => 'group_id',
    'group_id' => 0,
    'display_mode' => 1,
    'start_news_item' => 0,
    'max_news_items' => 10,
    'max_news_length' => -1,
    'strip_tags' => true,
    'allowed_tags' => '<p><a><img>',
    'custom_placeholder' => false,
    'sort_by' => 1,
    'sort_order' => 1,
    'not_older_than' => 0,
    'lang_id' => 'AUTO',
    'lang_filter' => false,
);
	$anynews_html = $twig_util->capture_echo( "displayNewsItems( \$GLOBALS['config'] );");

/**
* 
* All the page_content is defined here...
*
*/

$page_content=array    (

    /*    set standard parameters  */
	'DEFAULT_CHARSET'	=> defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
	'simplepagehead' 	=> $twig_util->capture_echo("simplepagehead();"),
	'page_title1' 	=> $twig_util->capture_echo("page_title();"),
	'page_title' 	=> PAGE_TITLE,	
	'description' 	=> $twig_util->capture_echo("page_description();"),
	'keywords' 		=> $twig_util->capture_echo("page_keywords();"),
	'TEMPLATE_DIR'	=> TEMPLATE_DIR,
	'page_id'       => PAGE_ID,
 	'home_url'   	=> LEPTON_URL,
	'viewport'	    => "<meta name='viewport' content='width=device-width, initial-scale=1'>",
	'meta_ie'	    => "<meta http-equiv='X-UA-Compatible' content='IE=EmulateIE7' />",	
	'ie6_no'	    => "<!--[if lte IE 6]><script type='text/javascript' charset='utf-8' src='" . TEMPLATE_DIR."/ie_js/ie6_no.js'></script><![endif]-->",
	'ie7_no'	    => "<!--[if lte IE 7]><script type='text/javascript' charset='utf-8' src='" . TEMPLATE_DIR."/ie_js/ie7_no.js'></script><![endif]-->",	
	'css_ie'        => "<!--[if lte IE 8]><style type='text/css' media='all'>@import '".TEMPLATE_DIR."/css/css-ie.css'</style><![endif]-->",
    
	/*    include jquery core and migrate */	
	'jquery'	=> "<script type='text/javascript' src='".LEPTON_URL."/modules/lib_jquery/jquery-core/jquery-core.min.js' ></script>",
	'migrate'	=> "<script type='text/javascript' src='".LEPTON_URL."/modules/lib_jquery/jquery-core/jquery-migrate.min.js' ></script>",
	'custom'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/spacious-custom.js' ></script>",	
	
	
	/*    register modfiles  LEPTON 1series only - deprecated, will be replaced with get_page_headers in LEPTON 2series */	
//	'get_page_headers'		=> $twig_util->capture_echo("get_page_headers();"),		
	'register_frontend_css'	=> $twig_util->capture_echo ("register_frontend_modfiles('css');"),
	'register_frontend_js'	=> $twig_util->capture_echo ("register_frontend_modfiles('js');"),	
	
    /*    set some customs  */	
	'lep_header'    => $twig_util->capture_echo("page_header();"),
	'logo'	        => "<a href='".LEPTON_URL."'><img src='".TEMPLATE_DIR."/img/logo.png' width='100' height='100' alt='home' title='logo' /></a>",
	'home'	        => "<a href='".LEPTON_URL."'><img src='".TEMPLATE_DIR."/img/blank.gif' width='900' height='180' alt='home' title='home' /></a>",
	'my_name'	    => "<a href='".LEPTON_URL."' title='Spacious' rel='home'>Spacious</a>",		
	'headimage'	    => "<a href='".LEPTON_URL."'><img class='head_img' src='".TEMPLATE_DIR."/img/1.jpg' width='900' height='180' alt='home' title='home'/></a>",
	'menu_name'	    => "Menu",	
	'slider'   		=> "[[sectionpicker?sid=10]]",	
	'search'   		=> "[[searchbox_spacious?msg=Search]]",
	'edit_page'   	=> "[[editthispage]]",
	
    /*    set sides  */
    'side1'	  		=> "Archives",
	'side2'	  		=> "Meta",	

	/*    set standard content  */
    'content1' => $twig_util->capture_echo("page_content(1);"),
    'content2' => $twig_util->capture_echo("page_content(2);"),
    'content3' => $twig_util->capture_echo("page_content(3);"),
    'content4' => $twig_util->capture_echo("page_content(4);"),
    'content5' => $twig_util->capture_echo("page_content(5);"),
    'content6' => $twig_util->capture_echo("page_content(6);"),		
	'anynews'  => $anynews_html,
	
    /*    navigation  */	
	'navimain'		=> show_menu2( 1, SM2_ROOT, SM2_ALL, SM2_TRIM|SM2_BUFFER|SM2_PRETTY),
	'navimain2'		=> show_menu2( 2, SM2_ROOT, SM2_ALL, SM2_TRIM|SM2_BUFFER|SM2_PRETTY),
//	'breadcrumb'  	=> show_menu2(1, SM2_ROOT, SM2_ALL, SM2_CRUMB|SM2_BUFFER),
	'breadcrumb'  	=> show_menu2(1, SM2_ROOT, SM2_MAX+1, SM2_CRUMB|SM2_BUFFER, '<span class="[class]"> >> [a][menu_title]</a>', '</span>', '', '', 'You are here:  <span class="[class]" >> [a][menu_title]</a>'),


    /*    set js files in footer */
    'cycle'	  		=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.cycle.all.min.js' ></script>",
	'slider'	  	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/spacious-slider-setting.js' ></script>",
	'navigation'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/navigation.js' ></script>",
	
    /*    foot section  */	
	'footer1'	    => "Text widget",
	'footer1_text'  => "<p>We built themes that are eye catching and easy to use, with lots of options and templates to change the look of your site as you wish.</p><p>Also we provide extensive support for all our themes.</p>",
	'footer2'	    => "Recent News",
	'footer2_text'	=> $anynews_html, //has to be styled individually, replace or use rpwe-block in style.css
	'footer3'	    => "Custom Menu",
	'footer3_text'  => show_menu2( 2, SM2_ROOT, SM2_START, SM2_TRIM|SM2_BUFFER|SM2_PRETTY),	//menu has to be styled via outpu classes, replace .menu-footer-menu-container, #menu-footer-menu and .menu
	'footer4'   	=> "Contact Information",
	'footer4_text' 	=> "<ul>
							<li>Sukedhara | Kathmandu | Nepal</li>
							<li>Phone: (977) 985238979</li>
							<li>Fax: (977) 123-4567</li>
							<li>Email: office@cms-lab.com</li>
							<li>Website: www.cms-lab.com</li>
						</ul>",
	
	
    /*    foot block  */	
    'lep_footer'    => $twig_util->capture_echo("page_footer();"),
	'power'		    => "design by <a href='http://themegrill.com/' target='_blank'>themegrill</a> | template by <a href='http://cms-lab.com' target='_blank'>cms-lab</a>",
);

echo $parser->render( 
	$template_name,	//	template-filename
	$page_content	//	template-data
);
?>