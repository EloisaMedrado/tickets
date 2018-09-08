<?php
    // Includes like imports
    include_once '../model/ticket.php';
    include_once '../conexao/conexao.php';
    include_once '../repository/TicketRepository.php';
    include_once '../utils/TicketUtils.php';

    class TicketControl {

        private $mongo;

        public function __construct() {
            $this->mongo = Conexao::getInstance();
        }

        function update($obj) {
            
            $data = json_decode($obj, true);

            $insere = !$data['_id']; //verificar como vai usar

            $ticketRepository = new TicketRepository($this->mongo);

            $ticket = TicketUtils::getNewTicketObject($data);
            
            $retorno = $ticketRepository->update($ticket);
            return $retorno;
        }

        function findByAndOrderPaginationObject($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $paging = array();
            $pages_arr = array();
            $paging['paging'] = array();

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
                } else {
                    $pages_arr = TicketUtils::getArrayPages($i, $urlPage, $i == $page);
                }
            }
            $paging['paging']['pages'] = $pages_arr['pages'];

            echo json_encode($paging);
            return json_encode($paging);
        }

        function findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);
            $tickets_arr = TicketUtils::getTicketsArray($rows);

            // echo json_encode($tickets_arr);
            return $tickets_arr;
        }

        function findAll() {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findAll();


            $tickets_arr = TicketUtils::getTicketsArray($rows);

            echo json_encode($tickets_arr);
        }
    }
?>
