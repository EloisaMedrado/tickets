<?php

    interface ITicketRepository {

        function findAll();

        function countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt);

        function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize);

        function update($ticket);

    }
?>