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
		define("SITE_URL", "www.mispmis.com");
		
		/*Live*/
		 define("HOST", "localhost");
		 define("DB_USERNAME", "just1st_admin");
		 define("DB_PASSWORD", "KI747LNWMJQL");
		 define('DB_NAME', "just1st_leaves");
	}
	else 
	{
	    /*Local*/
	    define("SITE_URL", "http://localhost/mispmis/");
	    
	    /*Local*/
	    define("HOST", "localhost");
	    define("DB_USERNAME", "root");
	    define("DB_PASSWORD", "");
	    define('DB_NAME', "just1st_leaves");
	}
	
	define("DB_PREFIX", "");

	/**
	 * CUSTOMER NAME
	 */
	define("CUSTOMER_NAME", "Majlis Islam Sarawak");
	
	/**
	 * PROJECT NAME
	 */
	define("PROJECT_NAME", "Employee Management System");
	
	/**
	 * SYSTEM VERSION
	 */
	define("SYS_VERSION", "v1.0");
	
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