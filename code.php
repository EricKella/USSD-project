<?php  
/**  
* This is a sample USSD code with session management.   
* It has only two screens.The purpose is to help developers get started with their USSD application and session management.  
*/


$request = file_get_contents("php://input");



$data = json_decode($request, true);

/**  
* set your custom session id and start session for the incoming request.  
* Note!! 
* The only unique parameter is the msisdn.   
* Set session id with the msisdn in order to track the session  
*/  
session_id(md5($data['MSISDN']));
session_start();
$ussd_id = $data['USERID'];
$msisdn = $data['MSISDN'];
$user_data = $data['USERDATA'];
$msgtype = $data['MSGTYPE'];
$id = session_id("SESSIONID");
     

if (isset($_SESSION[$id]) and $msgtype==false) {  
   $_SESSION[$id] = $_SESSION[$id].$user_data;  
   $user_dials = preg_split("/\#\*\#/",  
   $_SESSION[$id]);
   $msg = "Hello ".$user_dials[1].", Your initial dial was ".$user_dials[0].""Welcome 233543425046."\nThis is NALOTest USSD\nHow are you today\n1. Not fine\n2. Feeling fisky\n3. Sad",:)";  
   $resp = array("USERID"=>$ussd_id, "MSISDN"=>$msisdn, "USERDATA"=>$user_data, "MSG"=>$msg, "MSGTYPE"=>false);  
   echo json_encode($resp);
   session_destroy();  
 }

// Initial dial

else { 
   
 
    if(isset($_SESSION[$id]) and $msgtype==true){
        session_unset();
    }

    /**
    * Stores user inputs using sessions. 
    * You may also store user inputs in a database
    */  

    $_SESSION[$id] = $user_data."#*#";

// Responds to request. MSG variable will be displayed on the user's screen  

    $msg = "Welcome to NALO test demo\nThis is to help you get started with session/data management\nEnter your name please";
    $resp = array("USERID"=>$ussd_id, "MSISDN"=>$msisdn, "USERDATA"=>$user_data, "MSG"=>$msg, "MSGTYPE"=>true);
    echo json_encode($resp);  
 }

header('Content-Type: application/json'); 

?>