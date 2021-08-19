<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"url_target" => "_self",
	"icon" => "fa-home",
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/
$page_nav = array(
	"Dashboard" => array(
		"title" => "Dashboard",
		"icon" => "fa-home",
		"url" => APP_URL."/dashboard.php"
	),
	"My Leave" => array(
		"title" => "E-Leave",
		"icon" => "fa-coffee",
		"sub" => array(
			"Apply Leave" => array(
				"title" => "Apply Leave",
				"url" => APP_URL."/leave-applications.php"
			),
			"My Applications" => array(
				"title" => "My Applications",
				"url" => APP_URL."/my-applications.php"
			),
			"Leave Summary" => array(
				"title" => "Leave Summary",
				"url" => APP_URL."/leave-allocation-history.php"
			),
		)
	),
	"My Noticeboard"=>array(
		"title" => 'E-Notice',
		"icon" => "fa-bullhorn ",
		"url" => APP_URL."/noticeboard.php"
	),
	"Leave Applications"=>array(
			"title" => 'Leave Applications',
			"icon" => "fa-paper-plane",
			"url" => APP_URL."/employee-applications.php"
	),
	"Employees" => array(
		"title" => "Employees",
		"icon" => "fa-users",
		"url" => APP_URL."/employee.php"
	),
	/*"Leave Calendar"=>array(
	"title" => 'Leave Calendar',
	"icon" => "fa-calendar",
	"url" => APP_URL."/leavecalendar.php"
	),*/
	"Report" => array(
		"title" => "Report",
		"icon" => "fa-bar-chart",
		"sub" => array(
			"Leave Summary Report" => array(
				"title" => "Leave Summary Report",
				"url" => APP_URL."/leave_summary_report.php"
			),
    			"Employee List" => array(
    			"title" => "Employee List",
    			"url" => APP_URL."/employeeList.php"
    		)
		)
	),
    	"Configuration" => array(
		"title" => "Configuration",
		"icon" => "fa-gear",
		"sub" => array(
			"Department" => array(
				"title" => "Department",
				"url" => APP_URL."/department.php"
			),
			"Designation" => array(
				"title" => "Designation",
				"url" => APP_URL."/designation.php"
			),
			"Leave Type" => array(
				"title" => "Leave Type",
				"url" => APP_URL."/leave_type.php"
			),
			"Holiday" => array(
				"title" => "Holiday",
				"url" => APP_URL."/holiday.php"
			),
			"Document" => array(
					"title" => "Document",
					"url" => APP_URL."/document.php"
			),
			"Location" => array(
					"title" => "Location",
					"url" => APP_URL."/location.php"
			),
			"Days" => array(
					"title" => "Days",
					"url" => APP_URL."/days.php"
			),
			"Leave Allocation" => array(
				"title" => "Leave Allocation",
				"url" => APP_URL."/leave_allocation.php"
			),
			"Expire Old Leaves" => array(
				"title" => "Expire Old Leaves",
				"url" => APP_URL."/ExpireOldLeaves.php"
			)
		)
	),
	"Backup"=>array(
		"title" => 'Backup',
		"icon" => "fa-hdd-o",
		"url" => APP_URL."/backup.php"
		),
	
	"Logout"=>array(
		"title" => 'Logout',
		"icon" => "fa-sign-out",
		"url" => APP_URL."/logout.php"
	),
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>