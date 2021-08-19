DROP TABLE IF EXISTS adm_dbbackup;

CREATE TABLE `adm_dbbackup` (
  `db_id` int(11) NOT NULL AUTO_INCREMENT,
  `db_Name` varchar(100) DEFAULT NULL,
  `db_FilePath` varchar(200) DEFAULT NULL,
  `db_Date` date DEFAULT NULL,
  `db_DateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`db_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

INSERT INTO adm_dbbackup VALUES("32","LMS DB Backup (16 May, 2017)","backupDatabse/db_backup_16_05_2017.zip","2017-05-16","2017-05-16 14:29:04");


DROP TABLE IF EXISTS cnf_city;

CREATE TABLE `cnf_city` (
  `city_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_Name` int(11) NOT NULL,
  `city_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS cnf_cronjobhistory;

CREATE TABLE `cnf_cronjobhistory` (
  `cron_id` int(11) NOT NULL,
  `cron_title` varchar(255) DEFAULT NULL,
  `cron_status` text,
  `cron_dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS cnf_day;

CREATE TABLE `cnf_day` (
  `day_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_Name` varchar(20) DEFAULT NULL,
  `day_ShortName` varchar(10) DEFAULT NULL,
  `day_Order` int(11) DEFAULT NULL,
  PRIMARY KEY (`day_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO cnf_day VALUES("1","Saturday","Sat","1");
INSERT INTO cnf_day VALUES("2","Sunday","Sun","2");
INSERT INTO cnf_day VALUES("3","Monday","Mon","3");
INSERT INTO cnf_day VALUES("4","Tuesday","Tue","4");
INSERT INTO cnf_day VALUES("5","Wednesday","Wed","5");
INSERT INTO cnf_day VALUES("6","Thursday","Thu","6");
INSERT INTO cnf_day VALUES("7","Friday","Fri","7");


DROP TABLE IF EXISTS cnf_dept;

CREATE TABLE `cnf_dept` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_Name` varchar(100) DEFAULT NULL,
  `dept_ShortName` varchar(50) DEFAULT NULL,
  `dept_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO cnf_dept VALUES("3","Jabatan Islam Sarawak","JIS","A");
INSERT INTO cnf_dept VALUES("4","Biro Perkhidmatan Pendidikan ","BPP","A");
INSERT INTO cnf_dept VALUES("5","Majlis Islam Sarawak","MIS","A");
INSERT INTO cnf_dept VALUES("6","Islamic Information Centre","IIC","A");


DROP TABLE IF EXISTS cnf_desigleave;

CREATE TABLE `cnf_desigleave` (
  `dl_id` int(11) NOT NULL AUTO_INCREMENT,
  `desig_id` int(11) DEFAULT NULL,
  `lt_id` int(11) DEFAULT NULL,
  `dl_Number` int(11) DEFAULT NULL,
  `dl_Status` enum('A','I') DEFAULT NULL,
  `dl_Year` year(4) DEFAULT NULL,
  `dl_DateTime` datetime DEFAULT NULL,
  `dl_ExpiryDate` date DEFAULT NULL,
  PRIMARY KEY (`dl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

INSERT INTO cnf_desigleave VALUES("1","1","1","14","","2017","2017-03-23 09:08:36","2019-12-31");
INSERT INTO cnf_desigleave VALUES("2","2","1","10","","2017","2017-03-23 09:08:53","2019-12-31");
INSERT INTO cnf_desigleave VALUES("3","3","1","12","","2017","2017-03-23 09:09:05","2019-12-31");
INSERT INTO cnf_desigleave VALUES("4","4","1","14","","2017","2017-03-23 09:09:32","2019-12-31");
INSERT INTO cnf_desigleave VALUES("5","4","1","14","","2016","2017-03-23 09:13:13","");
INSERT INTO cnf_desigleave VALUES("6","3","1","12","","2016","2017-03-23 09:13:13","");
INSERT INTO cnf_desigleave VALUES("7","2","1","10","","2016","2017-03-23 09:13:13","");
INSERT INTO cnf_desigleave VALUES("8","1","1","14","","2016","2017-03-23 09:13:13","");
INSERT INTO cnf_desigleave VALUES("9","4","1","14","","2015","2017-03-23 09:14:00","");
INSERT INTO cnf_desigleave VALUES("10","3","1","12","","2015","2017-03-23 09:14:00","");
INSERT INTO cnf_desigleave VALUES("11","2","1","10","","2015","2017-03-23 09:14:00","");
INSERT INTO cnf_desigleave VALUES("12","1","1","14","","2015","2017-03-23 09:14:00","");
INSERT INTO cnf_desigleave VALUES("13","4","1","14","","2014","2017-03-23 09:14:39","");
INSERT INTO cnf_desigleave VALUES("14","3","1","12","","2014","2017-03-23 09:14:39","");
INSERT INTO cnf_desigleave VALUES("15","2","1","10","","2014","2017-03-23 09:14:39","");
INSERT INTO cnf_desigleave VALUES("16","1","1","14","","2014","2017-03-23 09:14:39","");


DROP TABLE IF EXISTS cnf_designation;

CREATE TABLE `cnf_designation` (
  `desig_id` int(11) NOT NULL AUTO_INCREMENT,
  `desig_Name` varchar(100) DEFAULT NULL,
  `desig_ShortName` varchar(50) DEFAULT NULL,
  `desig_Scale` varchar(10) DEFAULT NULL,
  `desig_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`desig_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO cnf_designation VALUES("1","HR Manager","HRM","D40","A");
INSERT INTO cnf_designation VALUES("2","Junior clerk","JCK","D20","A");
INSERT INTO cnf_designation VALUES("3","Senior Clerk","SCK","D22","A");
INSERT INTO cnf_designation VALUES("4","IT Manager","ITM","D40","A");


DROP TABLE IF EXISTS cnf_document;

CREATE TABLE `cnf_document` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_Name` varchar(100) DEFAULT NULL,
  `doc_Type` enum('F','I') DEFAULT NULL COMMENT 'File, Image',
  `doc_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO cnf_document VALUES("2","Resume","F","A");
INSERT INTO cnf_document VALUES("7","Joining Letter","F","A");
INSERT INTO cnf_document VALUES("9","Contract & Agreement","F","A");


DROP TABLE IF EXISTS cnf_emailnotification;

CREATE TABLE `cnf_emailnotification` (
  `en_id` int(11) NOT NULL AUTO_INCREMENT,
  `en_Subject` varchar(100) NOT NULL,
  `en_Message` varchar(500) NOT NULL,
  `en_To` int(11) NOT NULL,
  `en_By` int(11) NOT NULL,
  `en_Type` varchar(50) NOT NULL,
  `en_DateTime` datetime NOT NULL,
  PRIMARY KEY (`en_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

INSERT INTO cnf_emailnotification VALUES("1","New contract has been created.","Dear Khan,\n\n\nYour new contract details as follows: \n\nEffective Date: 2017-05-20: \nExpired Date: : \nContract Type: Permanent: \nContract Period:  Years: \nDesignation: IT Manager: \nDepartment: : \nGroup: : \nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("2","New leave request #reference no. 52 from Khan lala","","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("3","Leave request #reference no. 52 has been Approved","Dear Khan,\n\nReference No: 52\nLeave Type: Maternity Leave\nStart Date: 2017-05-13\nEnd Date: 2017-05-27\nNo. of Leaves Taken: 10\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("4","New leave request #reference no. 53 from justin tys","Dear Sir/Madam,\n\nReference No: 53\nApplicant: justin tys\nGroup: MIS\nDepartment: Engineering Department \nDesignation: IT Manager\nLeave Type: Maternity Leave\nLeave Start On: 2017-05-13\nLeave End On: 2017-05-20\nNo. of Leaves Taken: 8\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("5","New leave request #reference no. 54 from justin tys","Dear Sir/Madam,\n\nReference No: 54\nApplicant: justin tys\nGroup: MIS\nDepartment: Engineering Department \nDesignation: IT Manager\nLeave Type: Maternity Leave\nLeave Start On: 2017-05-13\nLeave End On: 2017-05-20\nNo. of Leaves Taken: 8\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("6","New leave request #reference no. 56 from jasaon tyg","Dear Sir/Madam,\n\nReference No: 56\nApplicant: jasaon tyg\nGroup: MIS\nDepartment: Engineering Department \nDesignation: Junior clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-12\nLeave End On: 2017-05-16\nNo. of Leaves Taken: 5\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("7","Leave request #reference no. 56 has been Approved","Dear justin,\n\nReference No: 56\nLeave Type: Annual Leaves\nStart Date: 2017-05-12\nEnd Date: 2017-05-16\nNo. of Leaves Taken: 3\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("8","New leave request #reference no. 53 from  ","Dear Sir/Madam,\n\nReference No: 53\nApplicant:  \nGroup: MIS\nDepartment: Engineering Department \nDesignation: IT Manager\nLeave Type: Maternity Leave\nLeave Start On: 2017-05-13\nLeave End On: 2017-05-20\nNo. of Leaves Taken: 5\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("9","Your account has been created.","Dear rrr,\n\nYour E-Leave account details as follows:\nEmail: rr@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("10","New contract has been created.","Dear jasaon,\n\n\nYour new contract details as follows: \n\nEffective Date: 2017-05-15\nExpired Date: \nContract Type: Permanent\nDesignation: IT Manager\nDepartment: Engineering Department \nGroup: MIS\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("11","Leave request #reference no. 51 has been Rejected","Dear Khan,\n\nReference No: 51\nLeave Type: Maternity Leave\nStart Date: 2017-05-12\nEnd Date: 2017-05-27\nNo. of Leaves Taken: 11\nRequest Status: Rejected\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("12","Leave request #reference no. 54 has been Rejected","Dear Khan,\n\nReference No: 54\nLeave Type: Maternity Leave\nStart Date: 2017-05-13\nEnd Date: 2017-05-20\nNo. of Leaves Taken: 5\nRequest Status: Rejected\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("13","Leave request #reference no. 55 has been Rejected","Dear Khan,\n\nReference No: 55\nLeave Type: Annual Leaves\nStart Date: 2017-05-12\nEnd Date: 2017-05-13\nNo. of Leaves Taken: 2\nRequest Status: Rejected\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("14","Your account has been created.","Dear admin,\n\nYour E-Leave account details as follows:\nEmail: admin@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("15","Your account has been created.","Dear Awang Azid B. ,\n\nYour E-Leave account details as follows:\nEmail: awang@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("16","New leave request #reference no. 57 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 57\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-16\nLeave End On: 2017-05-16\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("17","New leave request #reference no. 58 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 58\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-17\nLeave End On: 2017-05-17\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("18","New leave request #reference no. 59 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 59\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-18\nLeave End On: 2017-05-18\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("19","New leave request #reference no. 60 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 60\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-19\nLeave End On: 2017-05-19\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("20","New leave request #reference no. 61 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 61\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-22\nLeave End On: 2017-05-23\nNo. of Leaves Taken: 2\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("21","New leave request #reference no. 62 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 62\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-24\nLeave End On: 2017-05-24\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("22","New leave request #reference no. 63 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 63\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Sick Leave\nLeave Start On: 2017-05-15\nLeave End On: 2017-05-17\nNo. of Leaves Taken: 3\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("23","New leave request #reference no. 64 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 64\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-22\nLeave End On: 2017-05-25\nNo. of Leaves Taken: 4\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("24","New leave request #reference no. 65 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 65\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Paternity Leave\nLeave Start On: 2017-05-29\nLeave End On: 2017-05-31\nNo. of Leaves Taken: 3\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("25","New leave request #reference no. 66 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 66\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Maternity Leave\nLeave Start On: 2017-05-19\nLeave End On: 2017-05-29\nNo. of Leaves Taken: 11\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("26","New leave request #reference no. 67 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 67\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Emergency Leave\nLeave Start On: 2017-06-01\nLeave End On: 2017-05-31\nNo. of Leaves Taken: 0\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("27","New leave request #reference no. 68 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 68\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Emergency Leave\nLeave Start On: 2017-05-23\nLeave End On: 2017-05-30\nNo. of Leaves Taken: 8\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("28","Leave request #reference no. 57 has been Approved","Dear admin,\n\nReference No: 57\nLeave Type: Annual Leaves\nStart Date: 2017-05-16\nEnd Date: 2017-05-16\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("29","Leave request #reference no. 58 has been Approved","Dear admin,\n\nReference No: 58\nLeave Type: Annual Leaves\nStart Date: 2017-05-17\nEnd Date: 2017-05-17\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("30","Leave request #reference no. 59 has been Approved","Dear admin,\n\nReference No: 59\nLeave Type: Annual Leaves\nStart Date: 2017-05-18\nEnd Date: 2017-05-18\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("31","Leave request #reference no. 60 has been Approved","Dear admin,\n\nReference No: 60\nLeave Type: Annual Leaves\nStart Date: 2017-05-19\nEnd Date: 2017-05-19\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("32","Leave request #reference no. 61 has been Approved","Dear admin,\n\nReference No: 61\nLeave Type: Annual Leaves\nStart Date: 2017-05-22\nEnd Date: 2017-05-23\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("33","Leave request #reference no. 62 has been Approved","Dear admin,\n\nReference No: 62\nLeave Type: Annual Leaves\nStart Date: 2017-05-24\nEnd Date: 2017-05-24\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("34","Leave request #reference no. 63 has been Approved","Dear admin,\n\nReference No: 63\nLeave Type: Sick Leave\nStart Date: 2017-05-15\nEnd Date: 2017-05-17\nNo. of Leaves Taken: 3\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("35","Leave request #reference no. 64 has been Approved","Dear admin,\n\nReference No: 64\nLeave Type: Annual Leaves\nStart Date: 2017-05-22\nEnd Date: 2017-05-25\nNo. of Leaves Taken: 4\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("36","Leave request #reference no. 65 has been Approved","Dear admin,\n\nReference No: 65\nLeave Type: Paternity Leave\nStart Date: 2017-05-29\nEnd Date: 2017-05-31\nNo. of Leaves Taken: 3\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("37","Leave request #reference no. 66 has been Approved","Dear admin,\n\nReference No: 66\nLeave Type: Maternity Leave\nStart Date: 2017-05-19\nEnd Date: 2017-05-29\nNo. of Leaves Taken: 7\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("38","Leave request #reference no. 68 has been Approved","Dear admin,\n\nReference No: 68\nLeave Type: Emergency Leave\nStart Date: 2017-05-23\nEnd Date: 2017-05-30\nNo. of Leaves Taken: 6\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("39","New leave request #reference no. 69 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 69\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Sick Leave\nLeave Start On: 2017-06-05\nLeave End On: 2017-06-06\nNo. of Leaves Taken: 2\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("40","New leave request #reference no. 70 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 70\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Special Leave\nLeave Start On: 2017-05-23\nLeave End On: 2017-05-26\nNo. of Leaves Taken: 4\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("41","New leave request #reference no. 71 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 71\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: MIS\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Maternity Leave\nLeave Start On: 2017-05-23\nLeave End On: 2017-08-23\nNo. of Leaves Taken: 93\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("42","Leave request #reference no. 69 has been Approved","Dear admin,\n\nReference No: 69\nLeave Type: Sick Leave\nStart Date: 2017-06-05\nEnd Date: 2017-06-06\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("43","Leave request #reference no. 70 has been Approved","Dear admin,\n\nReference No: 70\nLeave Type: Special Leave\nStart Date: 2017-05-23\nEnd Date: 2017-05-26\nNo. of Leaves Taken: 4\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("44","Leave request #reference no. 71 has been Approved","Dear admin,\n\nReference No: 71\nLeave Type: Maternity Leave\nStart Date: 2017-05-23\nEnd Date: 2017-08-23\nNo. of Leaves Taken: 64\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("45","Your account has been created.","Dear abc,\n\nYour E-Leave account details as follows:\nEmail: abc@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("46","Your account has been created.","Dear def,\n\nYour E-Leave account details as follows:\nEmail: def@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("47","New leave request #reference no. 72 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 72\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-06-13\nLeave End On: 2017-06-13\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("48","New leave request #reference no. 73 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 73\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-24\nLeave End On: 2017-05-25\nNo. of Leaves Taken: 2\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("49","New leave request #reference no. 74 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 74\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-30\nLeave End On: 2017-05-30\nNo. of Leaves Taken: 1\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("50","New leave request #reference no. 75 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 75\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-05-26\nLeave End On: 2017-05-29\nNo. of Leaves Taken: 4\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("51","New leave request #reference no. 76 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 76\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-06-01\nLeave End On: 2017-06-05\nNo. of Leaves Taken: 5\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("52","New leave request #reference no. 77 from Awang Azid B.  Awang Yusuf","Dear Sir/Madam,\n\nReference No: 77\nApplicant: Awang Azid B.  Awang Yusuf\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: Senior Clerk\nLeave Type: Annual Leaves\nLeave Start On: 2017-06-13\nLeave End On: 2017-06-14\nNo. of Leaves Taken: 2\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("53","Leave request #reference no. 77 has been Approved","Dear admin,\n\nReference No: 77\nLeave Type: Annual Leaves\nStart Date: 2017-06-13\nEnd Date: 2017-06-14\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("54","Leave request #reference no. 76 has been Approved","Dear admin,\n\nReference No: 76\nLeave Type: Annual Leaves\nStart Date: 2017-06-01\nEnd Date: 2017-06-05\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("55","Leave request #reference no. 75 has been Approved","Dear admin,\n\nReference No: 75\nLeave Type: Annual Leaves\nStart Date: 2017-05-26\nEnd Date: 2017-05-29\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("56","Leave request #reference no. 73 has been Approved","Dear admin,\n\nReference No: 73\nLeave Type: Annual Leaves\nStart Date: 2017-05-24\nEnd Date: 2017-05-25\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("57","New contract has been created.","Dear abc,\n\n\nYour new contract details as follows: \n\nEffective Date: 2017-05-16\nExpired Date: \nContract Type: Permanent\nDesignation: IT Manager\nDepartment: Majlis Islam Sarawak\nGroup: Majlis Islam Sarawak\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("58","Leave request #reference no. 74 has been Approved","Dear admin,\n\nReference No: 74\nLeave Type: Annual Leaves\nStart Date: 2017-05-30\nEnd Date: 2017-05-30\nNo. of Leaves Taken: 1\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("59","Your account has been created.","Dear Justin,\n\nYour E-Leave account details as follows:\nEmail: justin@leave.com\nPassword:1234\nURL: www.innotter.com/project/leaveManagementSystem/login.php\n\nPlease remember to change your password  and don\'t share them to anyone.\n\nPlease contact HR manager for further assistance if you fail to login. \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Account Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("60","New leave request #reference no. 78 from admin admin","Dear Sir/Madam,\n\nReference No: 78\nApplicant: admin admin\nGroup: Majlis Islam Sarawak\nDepartment: Majlis Islam Sarawak\nDesignation: HR Manager\nLeave Type: Annual Leaves\nLeave Start On: 2017-07-27\nLeave End On: 2017-07-28\nNo. of Leaves Taken: 2\nRequest Status: Pending\n\n \nPlease APPROVE OR REJECT via system and don\'t reply to this email.\nThank you.\nYour Faithful,\nHR Manager","0","0","New Job Creation","0000-00-00 00:00:00");
INSERT INTO cnf_emailnotification VALUES("61","Leave request #reference no. 78 has been Approved","Dear admin,\n\nReference No: 78\nLeave Type: Annual Leaves\nStart Date: 2017-07-27\nEnd Date: 2017-07-28\nNo. of Leaves Taken: 2\nRequest Status: Approved\n\nPlease dont\'t Reply to this Email \n\nThank you.\nYour Faithful,\nHR Manager","0","0","Leave Request Status Change","0000-00-00 00:00:00");


DROP TABLE IF EXISTS cnf_group;

CREATE TABLE `cnf_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_Name` varchar(100) DEFAULT NULL,
  `group_ShortName` varchar(50) DEFAULT NULL,
  `group_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO cnf_group VALUES("1","Majlis Islam Sarawak","MIS","A");
INSERT INTO cnf_group VALUES("2","Jabatan Islam Sarwak","JIS","A");
INSERT INTO cnf_group VALUES("3","Biro Perkhidmatan Pendidikan","BPP","A");
INSERT INTO cnf_group VALUES("4","Islamic Information Centre","IIC","A");


DROP TABLE IF EXISTS cnf_holiday;

CREATE TABLE `cnf_holiday` (
  `holiday_id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_Title` varchar(100) DEFAULT NULL,
  `holiday_Description` varchar(300) DEFAULT NULL,
  `holiday_Date` date DEFAULT NULL,
  `month_id` int(11) DEFAULT NULL,
  `holiday_DateTime` datetime DEFAULT NULL,
  `holiday_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`holiday_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO cnf_holiday VALUES("2","Hari Merdeka","","2017-08-31","8","2017-03-23 08:39:03","A");
INSERT INTO cnf_holiday VALUES("4","Hari Gawai Day 1","","2017-06-01","6","2017-05-04 09:49:58","A");
INSERT INTO cnf_holiday VALUES("5","Hari Gawai Day 2","","2017-06-02","6","2017-05-04 09:50:22","A");
INSERT INTO cnf_holiday VALUES("6","Hari Raya Aidilfitri Day 1","Eid Al-Fitr","2017-06-25","6","2017-05-04 09:52:26","A");
INSERT INTO cnf_holiday VALUES("7","Hari Raya Aidilfitri Day 2","Eid Al-Fitr","2017-06-26","6","2017-05-04 09:52:53","A");


DROP TABLE IF EXISTS cnf_leave;

CREATE TABLE `cnf_leave` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_id` int(11) DEFAULT NULL,
  `leave_Number` int(11) DEFAULT NULL,
  `leave_Year` year(4) DEFAULT NULL,
  `leave_DateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`leave_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS cnf_leavereason;

CREATE TABLE `cnf_leavereason` (
  `lr_id` int(11) NOT NULL AUTO_INCREMENT,
  `lr_Title` varchar(1000) DEFAULT NULL,
  `lr_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`lr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO cnf_leavereason VALUES("1","Urgent Work","A");
INSERT INTO cnf_leavereason VALUES("2","Work At Home","A");
INSERT INTO cnf_leavereason VALUES("3","Others","A");


DROP TABLE IF EXISTS cnf_leavetype;

CREATE TABLE `cnf_leavetype` (
  `lt_id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_Name` varchar(200) DEFAULT NULL,
  `lt_Status` enum('A','I') DEFAULT NULL,
  `lt_DateTime` datetime DEFAULT NULL,
  `lt_Annual` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`lt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

INSERT INTO cnf_leavetype VALUES("1","Annual Leaves","A","","Y");
INSERT INTO cnf_leavetype VALUES("8","Sick Leave","A","","N");
INSERT INTO cnf_leavetype VALUES("9","Special Leave","A","","N");
INSERT INTO cnf_leavetype VALUES("10","Maternity Leave","A","","N");
INSERT INTO cnf_leavetype VALUES("11","Paternity Leave","A","","N");
INSERT INTO cnf_leavetype VALUES("12","Emergency Leave","A","","N");


DROP TABLE IF EXISTS cnf_notice;

CREATE TABLE `cnf_notice` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_Title` varchar(200) DEFAULT NULL,
  `notice_Description` varchar(10000) DEFAULT NULL,
  `notice_FromDate` date DEFAULT NULL,
  `notice_ToDate` date DEFAULT NULL,
  `notice_DateTime` datetime DEFAULT NULL,
  `notice_Status` enum('A','I') DEFAULT NULL,
  `notice_Remarks` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO cnf_notice VALUES("2","Personnel Information System + ELeave Module Training ","All management level staffs are compulsory to attend this training.\nTime: 11am - 2pmDate: 2017-04-10 - 2017-04-11\nVenue: Rafflesia Meeting Room\nok\nok\nok\n","2017-05-03","2017-05-25","2017-04-10 09:58:59","I","Dress Code: Formal\nLight refreshment will be served.","26");
INSERT INTO cnf_notice VALUES("4","E-Leave System","Dear All Staffs,\n\nPlease use system to apply your leave effective from 2017-05-10. Manual leave will no longer be entertain thereafter. \n\nThanks.\n\n\nYour Faithfully,\nHR Manager","2017-05-04","2017-05-18","2017-05-04 09:59:33","A","","26");


DROP TABLE IF EXISTS cnf_otherleave;

CREATE TABLE `cnf_otherleave` (
  `ol_id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_id` int(11) DEFAULT NULL,
  `ol_Number` int(11) DEFAULT NULL,
  `ol_DateTime` datetime DEFAULT NULL,
  `ol_Year` int(11) DEFAULT NULL,
  PRIMARY KEY (`ol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

INSERT INTO cnf_otherleave VALUES("1","12","10","2017-03-23 09:12:21","2017");
INSERT INTO cnf_otherleave VALUES("2","11","3","2017-03-23 09:12:21","2017");
INSERT INTO cnf_otherleave VALUES("3","10","90","2017-03-23 09:12:21","2017");
INSERT INTO cnf_otherleave VALUES("4","9","10","2017-03-23 09:12:21","2017");
INSERT INTO cnf_otherleave VALUES("5","8","14","2017-03-23 09:12:21","2017");
INSERT INTO cnf_otherleave VALUES("6","12","10","2017-03-23 09:13:13","2016");
INSERT INTO cnf_otherleave VALUES("7","11","3","2017-03-23 09:13:13","2016");
INSERT INTO cnf_otherleave VALUES("8","10","90","2017-03-23 09:13:13","2016");
INSERT INTO cnf_otherleave VALUES("9","9","10","2017-03-23 09:13:13","2016");
INSERT INTO cnf_otherleave VALUES("10","8","14","2017-03-23 09:13:13","2016");
INSERT INTO cnf_otherleave VALUES("11","12","10","2017-03-23 09:13:59","2015");
INSERT INTO cnf_otherleave VALUES("12","11","3","2017-03-23 09:14:00","2015");
INSERT INTO cnf_otherleave VALUES("13","10","90","2017-03-23 09:14:00","2015");
INSERT INTO cnf_otherleave VALUES("14","9","10","2017-03-23 09:14:00","2015");
INSERT INTO cnf_otherleave VALUES("15","8","14","2017-03-23 09:14:00","2015");
INSERT INTO cnf_otherleave VALUES("16","12","10","2017-03-23 09:14:39","2014");
INSERT INTO cnf_otherleave VALUES("17","11","3","2017-03-23 09:14:39","2014");
INSERT INTO cnf_otherleave VALUES("18","10","90","2017-03-23 09:14:39","2014");
INSERT INTO cnf_otherleave VALUES("19","9","10","2017-03-23 09:14:39","2014");
INSERT INTO cnf_otherleave VALUES("20","8","14","2017-03-23 09:14:39","2014");


DROP TABLE IF EXISTS cnf_user_role;

CREATE TABLE `cnf_user_role` (
  `ur_id` int(11) NOT NULL AUTO_INCREMENT,
  `ur_name` varchar(255) DEFAULT NULL,
  `ur_description` text,
  `ur_access` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO cnf_user_role VALUES("1","Admin","Full Access","");
INSERT INTO cnf_user_role VALUES("2","Manager","Limited Admin Access - Able to manage staffs leave application","");
INSERT INTO cnf_user_role VALUES("3","Employee","Only Limited To Leave Application and Noticeboard View","");
INSERT INTO cnf_user_role VALUES("4","Top Management","Only access dashboard and leave module","");


DROP TABLE IF EXISTS cnf_workingday;

CREATE TABLE `cnf_workingday` (
  `wd_id` int(11) NOT NULL AUTO_INCREMENT,
  `day_id` int(11) DEFAULT NULL,
  `wd_SameTime` enum('Y','N') DEFAULT NULL,
  `wd_On` enum('Y','N') DEFAULT NULL,
  `wd_StartTime` time DEFAULT NULL,
  `wd_EndTime` time DEFAULT NULL,
  `wd_FromDate` date DEFAULT NULL,
  `wd_ToDate` date DEFAULT NULL,
  `wd_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`wd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO cnf_workingday VALUES("1","1","Y","N","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("2","2","Y","N","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("3","3","Y","Y","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("4","4","Y","Y","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("5","5","Y","Y","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("6","6","Y","Y","08:00:00","17:00:00","2017-01-01","2017-01-31","A");
INSERT INTO cnf_workingday VALUES("7","7","Y","Y","08:00:00","17:00:00","2017-01-01","2017-01-31","A");


DROP TABLE IF EXISTS u_doclist;

CREATE TABLE `u_doclist` (
  `udc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `udc_Path` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`udc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS u_leaveapplication;

CREATE TABLE `u_leaveapplication` (
  `la_id` int(11) NOT NULL AUTO_INCREMENT,
  `lb_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lt_id` int(11) DEFAULT NULL,
  `la_Annual` enum('Y','N') DEFAULT NULL,
  `lr_id` int(11) DEFAULT NULL,
  `la_FromDate` date DEFAULT NULL,
  `la_ToDate` date DEFAULT NULL,
  `la_Date` date DEFAULT NULL,
  `la_Days` int(11) DEFAULT NULL,
  `la_Comment` varchar(1000) DEFAULT NULL,
  `la_Status` enum('P','A','R','C') DEFAULT NULL,
  `approved_By` int(11) DEFAULT NULL,
  `approved_DateTime` datetime DEFAULT NULL,
  `rejected_By` int(11) DEFAULT NULL,
  `rejected_DateTime` datetime DEFAULT NULL,
  `la_Remarks` varchar(3000) DEFAULT NULL,
  `la_DateTime` datetime DEFAULT NULL,
  `ul_id` int(11) DEFAULT NULL,
  `cancelledBy` int(11) DEFAULT NULL,
  `cancelled_DateTime` datetime DEFAULT NULL,
  `la_Reason` varchar(500) DEFAULT NULL,
  `la_ReasonDoc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`la_id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=latin1;

INSERT INTO u_leaveapplication VALUES("233","78","150","1","","1","2017-07-27","2017-07-28","2017-07-27","1","","A","150","2017-07-26 21:54:02","","","","2017-07-26 21:53:24","843","","","","");
INSERT INTO u_leaveapplication VALUES("234","78","150","1","","1","2017-07-27","2017-07-28","2017-07-28","1","","A","150","2017-07-26 21:54:02","","","","2017-07-26 21:53:24","843","","","","");


DROP TABLE IF EXISTS u_leavebatch;

CREATE TABLE `u_leavebatch` (
  `lb_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `lt_id` int(11) DEFAULT NULL,
  `lb_Annual` enum('Y','N') DEFAULT NULL,
  `lr_id` int(11) DEFAULT NULL,
  `lb_FromDate` date DEFAULT NULL,
  `lb_ToDate` date DEFAULT NULL,
  `lb_Date` date DEFAULT NULL,
  `lb_Days` int(11) DEFAULT NULL,
  `lb_Comment` varchar(1000) DEFAULT NULL,
  `lb_Status` enum('P','A','R','C') DEFAULT NULL,
  `lb_approved_By` int(11) DEFAULT NULL,
  `lb_approved_DateTime` datetime DEFAULT NULL,
  `lb_rejected_By` int(11) DEFAULT NULL,
  `lb_rejected_DateTime` datetime DEFAULT NULL,
  `lb_Remarks` varchar(3000) DEFAULT NULL,
  `lb_DateTime` datetime DEFAULT NULL,
  `ul_id` int(11) DEFAULT NULL,
  `lb_Doc` varchar(500) DEFAULT NULL,
  `lb_cancelledBy` int(11) DEFAULT NULL,
  `lb_cancelled_DateTime` datetime DEFAULT NULL,
  `lb_Reason` varchar(500) DEFAULT NULL,
  `lb_ReasonDoc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`lb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;

INSERT INTO u_leavebatch VALUES("78","150","1","","1","2017-07-27","2017-07-28","","2","","A","150","2017-07-26 21:54:02","","","","2017-07-26 21:53:23","0","","","","","20170727105402");


DROP TABLE IF EXISTS u_user;

CREATE TABLE `u_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_UserCode` varchar(20) DEFAULT NULL,
  `user_UserName` varchar(100) DEFAULT NULL,
  `user_Email` varchar(100) DEFAULT NULL,
  `user_PhoneNo` varchar(30) DEFAULT NULL,
  `user_Password` varchar(50) DEFAULT NULL,
  `user_FirstName` varchar(100) DEFAULT NULL,
  `user_LastName` varchar(100) DEFAULT NULL,
  `user_FatherName` varchar(100) DEFAULT NULL,
  `user_Status` enum('A','I','E') DEFAULT NULL,
  `user_PicPath` varchar(300) DEFAULT NULL,
  `user_DOB` varchar(20) DEFAULT NULL,
  `user_Gender` enum('M','F') DEFAULT NULL,
  `user_TCity` int(11) DEFAULT NULL,
  `user_TAddress` varchar(200) DEFAULT NULL,
  `user_PCity` int(11) DEFAULT NULL,
  `user_PAddress` varchar(200) DEFAULT NULL,
  `approved_By` int(11) DEFAULT NULL,
  `user_DateTime` datetime DEFAULT NULL,
  `user_JoiningDateTime` datetime DEFAULT NULL,
  `user_ExitDateTime` datetime DEFAULT NULL,
  `user_Type` enum('A','M','E','T') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;

INSERT INTO u_user VALUES("150","001","","admin@leave.com","","1234","admin","admin","","A","../uploads/employee_images/1.49490987768E+12c0ded861585e2d3714601cc028f05813.jpg","1980-01-01","F","","","","","","2017-05-16 09:54:15","2015-01-01 00:00:00","","A");
INSERT INTO u_user VALUES("154","EP001","","justin@leave.com","0128575377","1234","Justin","Tys","","A","","1985-05-22","M","","215, lorong seladah 4\nJalan seladah","","215, lorong seladah 4\nJalan seladah","","2017-07-26 21:46:00","2017-09-01 00:00:00","","M");


DROP TABLE IF EXISTS u_userannualleave;

CREATE TABLE `u_userannualleave` (
  `ual_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ual_FromDate` date DEFAULT NULL,
  `ual_ToDate` date DEFAULT NULL,
  `ual_Year` year(4) DEFAULT NULL,
  `ul_ExpiryDate` date DEFAULT NULL,
  `ual_Number` int(11) DEFAULT NULL,
  `ual_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`ual_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS u_usercompany;

CREATE TABLE `u_usercompany` (
  `uc_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `desig_id` int(11) DEFAULT NULL,
  `uc_ContractType` enum('P','C') NOT NULL DEFAULT 'P',
  `uc_JoiningDate` datetime DEFAULT NULL,
  `uc_ExitDate` datetime DEFAULT NULL,
  `uc_Status` enum('A','I') DEFAULT NULL,
  PRIMARY KEY (`uc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

INSERT INTO u_usercompany VALUES("23","","1","4","4","P","","","A");
INSERT INTO u_usercompany VALUES("126","150","1","5","1","P","2015-01-01 00:00:00","","A");
INSERT INTO u_usercompany VALUES("131","154","1","5","4","P","2017-09-01 00:00:00","","A");


DROP TABLE IF EXISTS u_userjobhistory;

CREATE TABLE `u_userjobhistory` (
  `uh_id` int(11) NOT NULL AUTO_INCREMENT,
  `uh_user_id` int(11) DEFAULT NULL,
  `uh_group` int(11) DEFAULT NULL,
  `uh_dept` int(11) DEFAULT NULL,
  `uh_desig` int(11) DEFAULT NULL,
  `uh_contract_type` enum('P','C') DEFAULT NULL,
  `uh_contract_duration` int(11) DEFAULT NULL,
  `uh_effective_date` date DEFAULT NULL,
  `uh_expiry_date` date DEFAULT NULL,
  `uh_status` enum('A','I') NOT NULL,
  PRIMARY KEY (`uh_id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

INSERT INTO u_userjobhistory VALUES("32","","1","4","4","P","","","","A");
INSERT INTO u_userjobhistory VALUES("35","","1","4","4","P","","2017-04-26","","I");
INSERT INTO u_userjobhistory VALUES("140","150","1","5","1","P","","2015-01-01","","A");
INSERT INTO u_userjobhistory VALUES("145","154","1","5","4","P","","2017-09-01","","A");


DROP TABLE IF EXISTS u_userleave;

CREATE TABLE `u_userleave` (
  `ul_id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_id` int(11) DEFAULT NULL,
  `ul_Number` int(11) DEFAULT NULL,
  `ul_RemainingNumber` int(11) DEFAULT NULL,
  `ul_Year` year(4) DEFAULT NULL,
  `ul_DateTime` datetime DEFAULT NULL,
  `ul_Status` enum('A','I','E') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ul_Annual` enum('Y','N') DEFAULT NULL,
  `ul_FromDate` date DEFAULT NULL,
  `ul_ToDate` date DEFAULT NULL,
  `ul_Remarks` varchar(1000) DEFAULT NULL,
  `ul_ExpiryDate` date DEFAULT NULL,
  `uh_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ul_id`)
) ENGINE=InnoDB AUTO_INCREMENT=927 DEFAULT CHARSET=latin1;

INSERT INTO u_userleave VALUES("153","1","9","9","2017","2017-04-26 03:24:19","A","","Y","2017-12-31","2017-01-01","","2019-12-31","");
INSERT INTO u_userleave VALUES("159","1","9","9","2017","2017-04-26 03:25:07","A","","Y","2017-12-31","2017-01-01","","2019-12-31","");
INSERT INTO u_userleave VALUES("825","1","0","0","2015","2017-05-16 09:52:37","A","","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("828","1","0","0","2016","2017-05-16 09:52:54","A","","Y","2016-12-31","2016-01-01","","2018-12-31","");
INSERT INTO u_userleave VALUES("830","1","0","0","2017","2017-05-16 09:53:12","A","","Y","2017-12-31","2017-01-01","","2019-12-31","");
INSERT INTO u_userleave VALUES("831","12","10","10","2017","2017-05-16 09:54:17","A","150","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("832","11","3","3","2017","2017-05-16 09:54:17","A","150","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("833","10","90","90","2017","2017-05-16 09:54:17","A","150","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("834","9","10","10","2017","2017-05-16 09:54:17","A","150","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("835","8","14","14","2017","2017-05-16 09:54:17","A","150","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("836","1","9","9","2017","2017-05-16 09:54:17","A","150","Y","2017-01-01","2017-12-31","","2019-12-31","");
INSERT INTO u_userleave VALUES("837","12","12","12","2015","2017-05-16 09:55:46","I","150","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("838","11","3","3","2015","2017-05-16 09:55:46","I","150","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("839","10","90","90","2015","2017-05-16 09:55:46","I","150","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("840","9","12","12","2015","2017-05-16 09:55:46","I","150","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("841","8","14","14","2015","2017-05-16 09:55:46","I","150","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("842","1","0","0","2015","2017-05-16 09:55:47","A","","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("843","1","4","2","2015","2017-05-16 09:55:47","A","150","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("877","1","14","14","2015","2017-05-16 12:14:56","A","","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("881","12","0","0","2016","2017-05-16 12:16:58","I","150","N","2016-12-31","2016-01-01","","2016-12-31","");
INSERT INTO u_userleave VALUES("885","11","0","0","2016","2017-05-16 12:16:58","I","150","N","2016-12-31","2016-01-01","","2016-12-31","");
INSERT INTO u_userleave VALUES("889","10","0","0","2016","2017-05-16 12:16:58","I","150","N","2016-12-31","2016-01-01","","2016-12-31","");
INSERT INTO u_userleave VALUES("893","9","0","0","2016","2017-05-16 12:16:58","I","150","N","2016-12-31","2016-01-01","","2016-12-31","");
INSERT INTO u_userleave VALUES("897","8","0","0","2016","2017-05-16 12:16:58","I","150","N","2016-12-31","2016-01-01","","2016-12-31","");
INSERT INTO u_userleave VALUES("901","1","14","14","2016","2017-05-16 12:16:58","A","","Y","2016-12-31","2016-01-01","","2018-12-31","");
INSERT INTO u_userleave VALUES("904","1","14","14","2016","2017-05-16 12:16:58","A","150","Y","2016-12-31","2016-01-01","","2018-12-31","");
INSERT INTO u_userleave VALUES("912","1","0","0","2015","2017-07-26 02:52:30","A","","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("913","1","0","0","2016","2017-07-26 02:52:38","A","","Y","2016-12-31","2016-01-01","","2018-12-31","");
INSERT INTO u_userleave VALUES("914","12","10","10","2017","2017-07-26 21:46:00","A","154","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("915","11","3","3","2017","2017-07-26 21:46:00","A","154","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("916","10","90","90","2017","2017-07-26 21:46:00","A","154","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("917","9","10","10","2017","2017-07-26 21:46:00","A","154","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("918","8","14","14","2017","2017-07-26 21:46:00","A","154","N","2017-01-01","2017-12-31","","2017-12-31","");
INSERT INTO u_userleave VALUES("919","1","6","6","2017","2017-07-26 21:46:00","A","154","Y","2017-01-01","2017-12-31","","2019-12-31","");
INSERT INTO u_userleave VALUES("920","12","10","10","2015","2017-07-26 21:48:40","A","154","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("921","11","10","10","2015","2017-07-26 21:48:40","A","154","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("922","10","10","10","2015","2017-07-26 21:48:40","A","154","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("923","9","10","10","2015","2017-07-26 21:48:40","A","154","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("924","8","10","10","2015","2017-07-26 21:48:40","A","154","N","2015-12-31","2015-01-01","","2015-12-31","");
INSERT INTO u_userleave VALUES("925","1","5","5","2015","2017-07-26 21:48:40","A","","Y","2015-12-31","2015-01-01","","2017-12-31","");
INSERT INTO u_userleave VALUES("926","1","4","4","2015","2017-07-26 21:48:40","A","154","Y","2015-12-31","2015-01-01","","2017-12-31","");


DROP TABLE IF EXISTS u_userlogindetail;

CREATE TABLE `u_userlogindetail` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `login_IP` varchar(30) DEFAULT NULL,
  `login_DateTime` datetime DEFAULT NULL,
  `logout_DateTime` datetime DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `login_Agent` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



