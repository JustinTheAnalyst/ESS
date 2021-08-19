<?php
    /*
     * CONSTANTS
     */

    /*******************************
     *  SITE URL & DB CREDENTIALS
     *****************************/

	$whitelist = array('127.0.0.1', '::1');
	
	if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist))
	{
		/*Live*/
		define("SITE_URL", "www.waikatowebdesign.com/projects/ess/");
		
		/*Live*/
		 define("HOST", "localhost");
		 define("DB_USERNAME", "mgr_admin");
		 define("DB_PASSWORD", "");
		 define('DB_NAME', "essdb");
	}
	else 
	{
	    /*Local*/
	    define("SITE_URL", "http://localhost/ess/");
	    
	    /*Local*/
	    define("HOST", "localhost");
	    define("DB_USERNAME", "root");
	    define("DB_PASSWORD", "");
	    define('DB_NAME', "ess");
	}
	
	define("DB_PREFIX", "");

    /*******************************
     *  SITE CONSTANTS
     *****************************/
    define('USER_AVATAR_DIRECTORY', 'images/avatar/');
    
    /*******************************
     *  SET DEFAULT TIME ZONE
     *****************************/
    date_default_timezone_set('Asia/Kuala_Lumpur');

    /*******************************
     *  PAGE TITLE
     *****************************/
    $page_title     =   basename($_SERVER["PHP_SELF"]);
    
    /**
     * PAGE NAME
     */
    define("PAGE_NAME", $page_title);
?>
