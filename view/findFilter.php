<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    // Includes like imports
    include_once '../control/ticketControl.php';

    $priority =  $_GET['priority'];
    $dtInicio =  $_GET['startDt'];
    $dtFim =  $_GET['endDt'];
    $order =  $_GET['order'];
    $page =  $_GET['page'];
    $pageSize =  $_GET['pageSize'];

    $ticketControl = new TicketControl();
    $ticketControl->findByAndOrder($priority, $startDt, $endDt, $order, $page, $pageSize);

?>
