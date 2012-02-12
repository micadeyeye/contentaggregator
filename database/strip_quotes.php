<?php

/**
* @package SPLIB
* @version $Id: strip_quotes.php,v 1.1 2003/08/15 21:37:04 harry Exp $
*/
/**
* Checks for magic_quotes_gpc = On and strips them from incoming requests
* if necessary
*/

if ( get_magic_quotes_gpc() ) {
    $_GET = array_map('stripslashes',$_GET);
    $_POST = array_map('stripslashes',$_POST);
    $_COOKIE = array_map('stripslashes',$_COOKIE);
}

?>