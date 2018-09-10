<?php
    // Headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    // Includes like imports
    include_once '../control/TicketControl.php';

    $priority =  $_GET['priority'];
    $startDt =  $_GET['startDt'];
    $endDt =  $_GET['endDt'];
    $order =  $_GET['order'];
    $ascendingOrder =  isset($_GET['ascendingOrder']) ? $_GET['ascendingOrder'] : 1;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pageSize =  isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;

    $ticketControl = new TicketControl();
    echo $ticketControl->findByAndOrderPaginationObject($priority, $startDt, $endDt, $order, $ascendingOrder, $page, $pageSize);

?>
