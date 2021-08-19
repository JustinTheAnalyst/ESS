<?php
/*function send_mail($to="", $subject="", $body=""){
    $to = "just1st_85@hotmail.com.com"; 
    $from = "info@oxpro.com"; 
    $headers = "From: $from"; 
    $subject = "New Owner Created"; 
    $fields = array(); 
    $fields{"sv2_firstname"} = "sv2_firstname"; 
    $fields{"sv2_lastname"} = "sv2_lastname"; 
    $fields{"sv2_phone"} = "sv2_phone"; 
    $body = "Here is what was sent:\n\n"; 
    
    foreach($fields as $a => $b)
    {   
    	$body .= sprintf("%20s: %s\n",$b,$_REQUEST[$a]); 
    }
    
    $send = mail($to, $subject, $body, $headers);
}*/
?>

<?php
   // $to = "subhan.khalid11@yahoo.com, subhan.khalid402@gmail.com"; 
   
    //$headers = "From: $from"; 
   // $headers = "MIME-Version: 1.0" . "\r\n";
//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

function sendMailOLD($to, $subject, $body){

 $from = "info@oxpro.com"; 
// More headers
$headers = 'From: Leave Management System <admin@leave.com>'. "\r\n";
//Multiple CC can be added, if we need (comma separated);
$headers .= 'Cc:' .$from. "\r\n";
//Multiple BCC, same as CC above;
$headers .= 'Bcc: just1st_85@hotmail.com' . "\r\n";

    
    $send = mail($to, $subject, $body, $headers);
    
    if($send){
        
        echo "congo";
    }
}
?>














