<?php
    //Headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // Includes like imports
    include_once '../control/TicketControl.php';

    //Pega data do POST
    $data = file_get_contents("php://input");

    $ticketControl = new TicketControl();
    echo $ticketControl->update($data);
    
?>
