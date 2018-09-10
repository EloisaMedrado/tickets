<?php

interface ITicketRepository {

    function findAll();
	
	function countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize)

    function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);

    function update($ticket);

}
?>