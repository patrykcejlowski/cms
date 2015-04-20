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


What it does:
Displays a nearly complete <head> section, except stylesheet and script links
for all pages but particularly for news, bakery, gallery, topics and gocart module pages.
If possible it replaces the title and meta description by the module item title and item (short-)description. 

Displays a tag to hide the image-toolbar and a link to the favicon in WB_URL

NOTE: The snippet is a replacement for most head tags.
You must comment the old head section using <!-- ... --> */


*****************************************************************************************
 SHORT INSTALLATION GUIDE:
*****************************************************************************************
 o Download the simplepagehead zip file from http://lepton-cms.com
 o Log into the backend and install the module as usual


*****************************************************************************************
 USING THE SNIPPET FUNCTION:
*****************************************************************************************
Once the module is installed, it can be invoked from the index.php file of your template.

From template index.php
...
<head>
<?php simplepagehead(); ?>
<link rel="stylesheet" type="text/css" href=".....
...


*****************************************************************************************
 ADDITIONAL PARAMETERS
*****************************************************************************************
For a more customised output, you can pass over serveral parameters to the function explained below.

<?php simplepagehead(endtag, norobotstag, notoolbartag, favicon, generator); ?>

Optional Parameters:
  endtag...          the closing tag: default: "/" for xhtml, "" for html4
  norobotstag...     default 1 = yes, 0 = no: shows on some pages the noindex,nofollow tag (e.g.: search)
  notoolbartag...    default 1 = yes, 0 = no: shows a tag to supress the IE-ImageToolbar
  favicon...         default 1 = yes, 0 = no: shows a link to the favicon in the TEMPLATE_DIR/img (where it should be)
  generator...		 default 1 = yes, 0 = no: shows: meta name="generator" content="LEPTON; http://www.lepton-cms.org"
 

Example for customised call for html4 with no additional tags:
  <?php simplepagehead('', 0, 0, 0); ?>

Example for customised call for xhtml with no robots-tags, but favicon and notoolbar-tag
  <?php simplepagehead('/', 0, 1, 1); ?>


*****************************************************************************************
 TROUBLE SHOOTING
*****************************************************************************************
 - Pass over either no argument, or all arguments in expected order
 - Remind the ; at the end of the code line


*****************************************************************************************