<?php 
include '../inc/PHP/constants.php';
include '../inc/PHP/configs.php';

global $DB;

//$_POST['startDate'] = "2017-03-08";
//$_POST['endDate'] = "2017-03-11";

$startDate = date("Y-m-d", strtotime($_GET['startDate']));
$endDate = date("Y-m-d", strtotime($_GET['endDate']));

// best stored as array, so you can add more than one
$getNumberOfHolidays = "SELECT * FROM cnf_holiday
						WHERE holiday_Status = 'A' AND holiday_Date BETWEEN '$startDate' AND '$endDate'";
$stmt = $DB->prepare($getNumberOfHolidays);
$stmt->execute();

$holidays = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$holidays[] = $row['holiday_Date'];
}

$start = new DateTime($startDate);
$end = new DateTime($endDate);

// otherwise the  end date is excluded
$end->modify('+1 day');

$interval = $end->diff($start);

// total days
$days = $interval->days;

// create an iterateable period of date (P1D equates to 1 day)
$period = new DatePeriod($start, new DateInterval('P1D'), $end);


//$holidays = array('2017-08-31','2017-12-25');

foreach($period as $dt) 
{
    $curr = $dt->format('D');

    // substract if Saturday or Sunday
    if ($curr == 'Sat' || $curr == 'Sun') 
    {
        $days--;
    }

    // (optional) for the updated question
    elseif (in_array($dt->format('Y-m-d'), $holidays)) 
    {
        $days--;
    }
}


echo $days; // 4
?>