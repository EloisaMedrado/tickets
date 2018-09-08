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
    $startDt =  $_GET['startDt'];
    $endDt =  $_GET['endDt'];
    $order =  $_GET['order'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // $_GET['page'];
    $pageSize =  isset($_GET['pageSize']) ? $_GET['pageSize'] : 10; //$_GET['pageSize'];

    $ticketControl = new TicketControl();
    $ticketControl->findByAndOrderPaginationObject($priority, $startDt, $endDt, $order, $page, $pageSize);

?>
