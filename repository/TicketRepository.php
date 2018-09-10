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

    function countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {
        
        $filter = TicketUtils::getFilter($filterStartDt, $filterEndDt, $filterPriority);

        $Command = new MongoDB\Driver\Command(['count' => "tickets", 'query' => $filter]);
        $Result = $this->mongo->executeCommand("projeto", $Command);

        return $Result->toArray()[0]->n;
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
        if(!is_array($ticket)) {
            $id = $ticket->getId();
            var_dump($ticket); die();
            $ticket_update = TicketUtils::getNewTicketArray($ticket);
        } else {
            $id = (string) new MongoDB\BSON\ObjectId($ticket['id']['$oid']);
            $ticket_update = TicketUtils::getNewTicketArrayFromArray($ticket);
        }

        $filter = ["_id" => new MongoDB\BSON\ObjectId("$id")];

        $bulk->update($filter, $ticket_update, ['multi' => false, 'upsert' => true]);
        $returnMongo = $this->mongo->executeBulkWrite($this->bdTableName, $bulk);

        return ($returnMongo->getModifiedCount());
    }

}
?>