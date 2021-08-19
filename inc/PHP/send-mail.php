<?php
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
?>