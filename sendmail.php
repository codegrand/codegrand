<?php

if(isset($_POST['name'])){
    
    
    $name  = $_POST['name'];
    $email  = $_POST['email'];
    $mobile  = $_POST['mobile'];
    $formmessage  = $_POST['message'];

    // to email
    $to = "hello@codegrand.com";
    
    // subject
    $subject  = "Enquiry from Website - ".$email;
    
    $message = "Name : ".$name." Email : ".$email." Mobile : ".$mobile." Message : ".$formmessage;
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // More headers
    $headers .= 'From: <donotreply@codegrand.com>' . "\r\n";
    
    $mail = mail($to,$subject,$message,$headers);
    
    echo $mail;

}