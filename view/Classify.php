<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    // Includes like imports
    include_once '../control/TicketControl.php';

    $dateNow = isset($_GET['dateNow']) ? $_GET['dateNow'] : "29/12/2017";

    $ticketControl = new TicketControl();
    echo $ticketControl->classifyDocs($dateNow);

?>
