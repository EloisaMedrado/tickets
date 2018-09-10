<?php
    // Includes like imports
    include_once '../model/Ticket.php';
    include_once '../conexao/Conexao.php';
    include_once '../repository/TicketRepository.php';
    include_once '../utils/TicketUtils.php';
    include_once 'Classification.php';

    class TicketControl {

        private $mongo;

        public function __construct() {
            $this->mongo = Conexao::getInstance();
        }

        function update($ticket) {

            $ticketRepository = new TicketRepository($this->mongo);
            if(!is_array($ticket)){
                $data = json_decode($ticket, true);
                $insere = !$data['_id']; //verificar como vai usar
                $ticket = TicketUtils::getNewTicketObject($data);
            }
            
            return $ticketRepository->update($ticket);
        }

        function findByAndOrderPaginationObject($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $paging = array();
            $pages_arr = array();
            $paging['paging'] = array();
            $pages_arr['pages'] = array();

            $ticketRepository = new TicketRepository($this->mongo);
            $qtTotalRows = $ticketRepository->countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);
            $qtTotalPages = ceil($qtTotalRows/$pageSize);  
            
            for ($i = 1; $i <= $qtTotalPages; $i++) {
                if($i == $page){
                    $paging['tickets'] = $this->findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);
                }
                $urlPage = TicketUtils::getUrlPagination($filterPriority, $filterStartDt, $filterEndDt, $order, $i, $pageSize);

                if($i == $qtTotalPages) {
                    $paging['paging']['last'] = $urlPage;
                    $paging['paging']['lastPage'] = $qtTotalPages;
                } else {
                    array_push($pages_arr['pages'], TicketUtils::getArrayPages($i, $urlPage, $i == $page));
                }
            }
            $paging['paging']['pages'] = $pages_arr['pages'];

            // echo json_encode($paging);
            return json_encode($paging);
        }

        function findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);
            $ticketsArray = TicketUtils::getTicketsArray($rows);

            return $ticketsArray;
        }

        function findAll() {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findAll();
            $ticketsArray = TicketUtils::getTicketsArray($rows);

            // echo json_encode($ticketsArray);
            return json_encode($ticketsArray);
        }

        function classifyDocs($ticketToClassify = null) {

            $filteredTickets = null;
            $successfullyClassified = true;

            if(is_null($ticketToClassify)){
                $ticketsArray = json_decode($this->findAll(), true);
                $filteredTickets = TicketUtils::filterIfLastInteractionClassified($ticketsArray);
            } else {
                $filteredTickets = array(json_decode($ticketToClassify, true));
            }

            foreach($filteredTickets as $ticket) {
                $newTicket = Classification::classifyTickets($ticket);
                $successfullyClassified = $successfullyClassified && $this->update($newTicket);
            }

            // echo $successfullyClassified;
            return $successfullyClassified;
        }
    }
?>
