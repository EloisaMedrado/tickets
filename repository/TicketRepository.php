<?php

    include_once 'interface/ITicketRepository.php';
    include_once '../utils/TicketUtils.php';
    require_once '../conexao/Config.php';


    class TicketRepository implements ITicketRepository {

        private $mongo;

        public function __construct($db) {
            $this->mongo = $db;
        }

        function findAll() {

            $query = new MongoDB\Driver\Query([], ['sort' => [ 'CustomerName' => 1]]);
            $rows = $this->mongo->executeQuery(DB_COLLECTION, $query);

            return $rows;
        }

        function countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt) {
            
            $filter = TicketUtils::getFilter($filterStartDt, $filterEndDt, $filterPriority);

            $Command = new MongoDB\Driver\Command(['count' => "tickets", 'query' => $filter]);
            $Result = $this->mongo->executeCommand("projeto", $Command);

            return $Result->toArray()[0]->n;
        }

        function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize) {

            $sort = TicketUtils::getSort($pageSize, $page, $order, $ascendingOrder);
            $filter = TicketUtils::getFilter($filterStartDt, $filterEndDt, $filterPriority);

            $query = new MongoDB\Driver\Query($filter, $sort);
            $rows = $this->mongo->executeQuery(DB_COLLECTION, $query);

            return $rows;
        }
        
        function update($ticket) {

            $bulk = new MongoDB\Driver\BulkWrite;

            $id = (string) new MongoDB\BSON\ObjectId($ticket['id']['$oid']);
            $ticket_update = TicketUtils::getNewTicketArrayFromArray($ticket);
            
            $filter = ["_id" => new MongoDB\BSON\ObjectId("$id")];

            $bulk->update($filter, $ticket_update, ['multi' => false, 'upsert' => true]);
            $returnMongo = $this->mongo->executeBulkWrite(DB_COLLECTION, $bulk);

            return ($returnMongo->getModifiedCount());
        }

    }
?>