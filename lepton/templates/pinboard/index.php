<?php

/**
 *  @template       Pinboard
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2013-2014 CMS-LAB
 *  @license        GNU General Public License v2 or later
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
		$template_name = "four_columns.lte";
		break;			
	default:
		$template_name = "no_sidebar.lte";
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
	'ie9'		    => "<!--[if lt IE 9]><script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.html5.js'></script><![endif]-->",
	'ie7_no'	    => "<!--[if lte IE 7]><script type='text/javascript' charset='utf-8' src='" . TEMPLATE_DIR."/ie_js/ie7_no.js'></script><![endif]-->",	
	'css_ie'        => "<!--[if lte IE 8]><style type='text/css' media='all'>@import '".TEMPLATE_DIR."/css/css-ie.css'</style><![endif]-->",
    
	/*    include jquery core and migrate and jquery stuff  */	
	'jquery'		=> "<script type='text/javascript' src='".LEPTON_URL."/modules/lib_jquery/jquery-core/jquery-core.min.js' ></script>",
	'migrate'		=> "<script type='text/javascript' src='".LEPTON_URL."/modules/lib_jquery/jquery-core/jquery-migrate.min.js' ></script>",
	'flexslider'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.flexslider-min.js' ></script>",
	'fitvids'	  	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.fitvids.js' ></script>",
	'scroll'		=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.infinitescroll.min.js' ></script>",
	'colorbox'		=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery.colorbox-min.js' ></script>",
	'jquery_settings'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/jquery_settings.js' ></script>",
	
	
	/*    register modfiles  LEPTON 1series only - deprecated, will be replaced with get_page_headers in LEPTON 2series */	
//	'get_page_headers'		=> $twig_util->capture_echo("get_page_headers();"),		
	'register_frontend_css'	=> $twig_util->capture_echo ("register_frontend_modfiles('css');"),
	'register_frontend_js'	=> $twig_util->capture_echo ("register_frontend_modfiles('js');"),	
	
    /*    set some customs  */	
	'lep_header'    => $twig_util->capture_echo("page_header();"),
	'logo'	        => "<a href='".LEPTON_URL."' rel='home'><img src='".TEMPLATE_DIR."/img/logo1.png' width='196' height='48' alt='home' title='Pinboard' /></a>",
	'logo_text'     => "<a class='home' href='".LEPTON_URL."' rel='home'>Pinboard</a>",
	'home'	        => "<a href='".LEPTON_URL."'><img src='".TEMPLATE_DIR."/img/blank.gif' width='900' height='180' alt='home' title='home' /></a>",
	'my_name'	    => "<a href='".LEPTON_URL."' title='Spacious' rel='home'>Spacious</a>",		
	'headimage'	    => "<a href='".LEPTON_URL."'><img class='head_img' src='".TEMPLATE_DIR."/img/1.jpg' width='900' height='180' alt='home' title='home'/></a>",
	'menu_name'	    => "Menu",
	
    /*    social media  */	
	'facebook' 		=> "<a class='social-media-icon facebook' href='http://www.facebook.com/lepton' target='_blank'>Facebook</a>",
	'twitter'   	=> "<a class='social-media-icon twitter' href='http://twitter.com/lepton' target='_blank'>Twitter</a>",
	'pinterest'   	=> "<a class='social-media-icon pinterest' href='http://www.pinterest.com/' target='_blank'>Pinterest</a>",
	'flickr'   		=> "<a class='social-media-icon flickr' href='http://www.flickr.com/' target='_blank'>Flickr</a>",
	'vimeo'   		=> "<a class='social-media-icon youtube' href='http://www.youtube.com/' target='_blank'>Vimeo</a>",
	
	
	//	'slider'   		=> "[[sectionpicker?sid=10]]",	
	'search'   		=> "[[searchbox_pinboard?msg=Search this website]]",
	'edit_page'   	=> "[[editthispage]]",
	

	/*    set standard content  */
    'content1' => $twig_util->capture_echo("page_content(1);"),
    'content2' => $twig_util->capture_echo("page_content(2);"),
    'content3' => $twig_util->capture_echo("page_content(3);"),
    'content4' => $twig_util->capture_echo("page_content(4);"),
    'content5' => $twig_util->capture_echo("page_content(5);"),
    'content6' => $twig_util->capture_echo("page_content(6);"),		
	'anynews'  => $anynews_html,
	
    /*    navigation  */	
	'navi_detail'  	=> show_menu2(SM2_ALLMENU, SM2_ROOT, SM2_ALL, SM2_ALL|SM2_PRETTY|SM2_BUFFER|SM2_ALLINFO, '[li]<span class=\'navclass\'>[a][page_title]<br /><i>[description]</i></a></span>', false, '<ul id=\'menu-navigation\' class=\'menu [class]\'>'),
	'breadcrumb_title'  	=> "Currently browsing",
	'breadcrumb'  	=> show_menu2(1, SM2_ROOT, SM2_MAX+1, SM2_CRUMB|SM2_BUFFER, '<span class="[class]">, [menu_title]', '</span>', '', '', '<span class="[class]" , [a][menu_title]</a>'),

	
	
    /*    set js files in footer */
	'bottom_settings'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/bottom_settings.js' ></script>",
	'mediaelement'		=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/mediaelement-and-player.min.js' ></script>",
	'wp_mediaelement'	=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/wp-mediaelement.js' ></script>",
	'masonry'			=> "<script type='text/javascript' src='".TEMPLATE_DIR."/js/masonry.min.js' ></script>",
	
    /*    foot section  */	
	'footer1'	    => "Custom Menu",
	'footer_menu'  	=> show_menu2( 2, SM2_ROOT, SM2_ALL, SM2_TRIM|SM2_BUFFER|SM2_PRETTY),
	
	'footer2'	    => "Blogroll",
	'footer2_text1'	=> "<a href='http://doc.lepton-cms.org' target='_blank' title='Documentation'>Documentation</a>",
	'footer2_text2'	=> "<a href='http://lepton-cms.com' target='_blank' title='LEPAdoR'>Addons</a>",
	'footer2_text3'	=> "<a href='http://forum.lepton-cms.org' target='_blank' title='Addon Support'>Addon Support</a>",
	'footer2_text4'	=> "<a href='http://jquery.lepton-cms.org' target='_blank' title='Plugins'>Plugins</a>",
	'footer2_text5'	=> "<a href='http://lepton-cms.org' target='_blank' title='LEPTON'>LEPTON CMS</a>",
	
	'footer3'	    => "Meta",
	'footer3_text1'	=> "<a href='".ADMIN_URL."' title='Admin Login'>Log in</a>",
	'footer3_text2'	=> "<a href='".LEPTON_URL."/modules/news/rss.php?page_id=6' title='Syndicate this site using RSS'><abbr title='Really Simple Syndication'>RSS-Feed</abbr></a>",
	'footer3_text3'	=> "<a href='http://forum.lepton-cms.org' target='_blank' title='Forum Support'>Forum Support</a>",
	'footer3_text4'	=> "<a href='http://cms-lab.com' target='_blank' title='CMS-LAB'>CMS-LAB</a>",	
	
	
	
    /*    foot block  */	
    'lep_footer'    => $twig_util->capture_echo("page_footer();"),
	'power'		    => "design by <a href='http://www.onedesigns.com/themes/pinboard' title='Pinboard Theme' target='_blank'>Pinboard Theme</a> | template by <a href='http://cms-lab.com' target='_blank'>CMS-LAB</a>",
);

echo $parser->render( 
	$template_name,	//	template-filename
	$page_content	//	template-data
);
?>