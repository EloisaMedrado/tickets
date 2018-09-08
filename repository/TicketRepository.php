<?php

include_once 'interface/ITicketRepository.php';
include_once '../utils/TicketUtils.php';


class TicketRepository implements ITicketRepository {

    private $mongo;
    private $bdTableName = "projeto.tickets";

    public function __construct($db) {
        $this->mongo = $db;
    }

    function findAll() {
        $query = new MongoDB\Driver\Query([], ['sort' => [ 'CustomerName' => 1]]);
        
        $rows = $this->mongo->executeQuery($this->bdTableName, $query);

        return $rows;
    }

    function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

        $sort = TicketUtils::getSort($pageSize, $page, $order);
        
        $filter = TicketUtils::getFilter($filterStartDt, $filterEndDt, $filterPriority);

        $query = new MongoDB\Driver\Query($filter, $sort);
        $rows = $this->mongo->executeQuery($this->bdTableName, $query);

        return $rows;
    }
    
    function update($ticket) {

        $bulk = new MongoDB\Driver\BulkWrite;
        $id = $ticket->getId();
        $filter = ["_id" => new MongoDB\BSON\ObjectId("$id")];

        $ticket_update = TicketUtils::getNewTicketArray($ticket);

        $bulk->update($filter, $ticket_update, ['multi' => false, 'upsert' => true]);
        $returnMongo = $this->mongo->executeBulkWrite($this->bdTableName, $bulk);

        // print_r($returnMongo->getModifiedCount());
        return ($returnMongo->getModifiedCount());
    }

}
?>