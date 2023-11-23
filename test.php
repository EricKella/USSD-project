<?php

/**  Kella Eric Mwinwule USSD Project at Nalo  
 * I started with their USSD application and session management.  
 */


$request = file_get_contents("php://input");


$data = json_decode($request, true);

 
/**session id and start session for the incoming request.  
  Note!! 
 * The only unique parameter is the msisdn.   
 * Set session id with the msisdn in order to track the session  
 */



session_id(md5($data['MSISDN']));
session_start();


$ussd_id = $data['USERID'];
$msisdn = $data['MSISDN'];
$user_data = $data['USERDATA'];
$msgtype = $data['MSGTYPE'];
$id = session_id();
$totalScreens = 3;
function sendResponse($responsePayload)
{

    if ($responsePayload['MSGTYPE'] == true) {
        echo json_encode($responsePayload);
    } else {
        echo json_encode($responsePayload);
        session_destroy();
    }
}






if (isset($_SESSION[$id]) and $msgtype == false) {

    $_SESSION[$id] = "{$_SESSION[$id]}{$user_data},"; //"scrn1 Option" -> "scrn1Option,scrn2Option"

    // Validate the input

    if (!preg_match("/^[1-3,]+$/", $_SESSION[$id])) {
        $msg = "Invalid Response,oops , redial the code and try again :)";
        $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
        echo json_encode($resp);
        session_destroy();
        die();
    }
    

//Split the string of selected options by ","
    $selectedOptions = preg_split("/\,/", $_SESSION[$id]); //["screen 1 option ","scrn 2 Option"]

    
    $moods = [
        "1" => "not fine",
        "2" => "feeling frisky",
        "3" => "sad",
    ];

    $moodCauses = [
        "1" => "health",
        "2" => "money",
        "3" => "relationship",
    ];

    $moodKey = $selectedOptions[0];
    $moodCauseKey;

    if (count($selectedOptions) >= $totalScreens) {

        $moodCauseKey = $selectedOptions[1];
    }

    if (count($selectedOptions) >= $totalScreens) {

        $msg = "Hmm i think , you are {$moods[$moodKey]} because of {$moodCauses[$moodCauseKey]} issues :(";
        $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => false);
        sendResponse($resp);
    } else {

        $msg = "Why are you {$moods[$moodKey]}?\n 1. Health\n 2. Money\n 3. Relationship";
        $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);
        sendResponse($resp);
    }
}


// Initial dial


else {


    if (isset($_SESSION[$id]) and $msgtype == true) {
        session_unset();
    }


    /**
     *soring user's  inputs using sessions. 
     * You may also store user inputs in a database
     */


    $_SESSION[$id] =  "";


    // Responds to request. MSG variable will be displayed on the user's screen  


    $msg = "Welcome +{$msisdn}. \nThis Kella's  USSD Project\nHow are you feeling today dear \n 1. Not well\n 2. Feeling frisky\n 3. Sad";

    $resp = array("USERID" => $ussd_id, "MSISDN" => $msisdn, "USERDATA" => $user_data, "MSG" => $msg, "MSGTYPE" => true);

    echo json_encode($resp);
}


header('Content-Type: application/json');