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

            return $ticketRepository->update($ticket);
        }

        function findByAndOrderPaginationObject($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize) {

            $paging = array();
            $pagesArray = array();
            $paging['paging'] = array();
            $pagesArray['pages'] = array();

            $ticketRepository = new TicketRepository($this->mongo);
            $qtTotalRows = $ticketRepository->countByDateCreateBetweenAndPriority($filterPriority, $filterStartDt, $filterEndDt);
            $qtTotalPages = ceil($qtTotalRows/$pageSize);  
            
            for ($i = 1; $i <= $qtTotalPages; $i++) {
                if($i == $page){
                    $paging['tickets'] = $this->findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize);
                }
                $urlPage = TicketUtils::getUrlPagination($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $i, $pageSize);

                if($i == $qtTotalPages) {
                    $paging['paging']['last'] = $urlPage;
                    $paging['paging']['lastPage'] = $qtTotalPages;
                } else {
                    array_push($pagesArray['pages'], TicketUtils::getArrayPages($i, $urlPage, $i == $page));
                }
            }
            $paging['paging']['pages'] = $pagesArray['pages'];

            return json_encode($paging);
        }

        function findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize) {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize);
            $ticketsArray = TicketUtils::getTicketsArray($rows);

            return $ticketsArray;
        }

        function findAll() {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findAll();
            $ticketsArray = TicketUtils::getTicketsArray($rows);

            return json_encode($ticketsArray);
        }

        function classifyDocs() {

            $filteredTickets = null;
            $successfullyClassified = true;

            $ticketsArray = json_decode($this->findAll(), true);
            $filteredTickets = TicketUtils::filterIfLastInteractionClassified($ticketsArray);

            foreach($filteredTickets as $ticket) {
                $newTicket = Classification::classifyTickets($ticket);
                $successfullyClassified = $successfullyClassified && $this->update($newTicket);
            }

            if($successfullyClassified){
                return "Tickets classificados com sucesso!";
            }
            return "Não foi possível classificar todos os tickets!";
        }
    }
?>
