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

// OBLIGATORY VARIABLES
$template_directory     = 'pinboard';
$template_name          = 'Pinboard';
$template_function      = 'template';
$template_version       = '1.0.0';
$template_platform      = '1.3.2';
$template_author        = 'CMS-LAB';
$template_license       = 'GNU General Public License v2 or later';
$template_license_terms = 'you have to keep the frontend-backlink to cms-lab untouched';
$template_description   = 'Design by http://www.onedesigns.com, template by http://cms-lab.com';
$template_guid          = '5d169e7f-61d1-4035-871c-96bb787f619e';

// OPTIONAL VARIABLES FOR ADDITIONAL MENUES AND BLOCKS
$menu[ 1 ]  = 'Main';
$menu[ 2 ]  = 'Foot';
$menu[ 3 ]  = 'Pseudomenu';

$block[ 1 ] = 'Content1';
$block[ 2 ] = 'Content2';
$block[ 3 ] = 'Content3';
$block[ 4 ] = 'Content4';
$block[ 5 ] = 'Content5';
$block[ 6 ] = 'Content6';
$block[ 7 ] = 'News';

?>