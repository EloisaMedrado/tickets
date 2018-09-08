<?php

interface ITicketRepository {

    function findAll();

    function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);

    function update($ticket);

}
?>