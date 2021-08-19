<?php
function sendMailOLD($to, $subject, $body)
{
    $from = "no-reply@abc.com"; 
    // More headers
    $headers = 'From: ABC ESS Portal <no-reply@abc.com>'. "\r\n";
    //Multiple CC can be added, if we need (comma separated);
    $headers .= 'Cc:' .$from. "\r\n";
    //Multiple BCC, same as CC above;
    $headers .= 'Bcc: hr@abc.com' . "\r\n";
    
    $send = mail($to, $subject, $body, $headers);
    
    if($send){
        
        echo "Succcessful!";
    }
}
?>














