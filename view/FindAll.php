<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    // Includes like imports
    include_once '../control/TicketControl.php';

    $ticketControl = new TicketControl();
    echo $ticketControl->findAll();

?>
