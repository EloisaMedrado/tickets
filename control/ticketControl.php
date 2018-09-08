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

        function findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);
            
            
            
            $tickets_arr = TicketUtils::getTicketsArray($rows);

            $quantidadeRegistros = count($tickets_arr);
            $pageSize =  is_null($pageSize) ? 3 : $pageSize;
            $divisao = $quantidadeRegistros / $pageSize;
            echo $quantidadeRegistros . ' - ' . $pageSize . ' - ' . $divisao;
            $qtPages = ceil($divisao);
            echo $qtPages; die();


            //Retorna resultados da pag 1
            if(!$tickets_arr) {
                $rows = $ticketRepository->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, 1, 10);
                $tickets_arr = TicketUtils::getTicketsArray($rows);
            }

            echo json_encode($tickets_arr);
        }

        function findAll() {

            $ticketRepository = new TicketRepository($this->mongo);

            $rows = $ticketRepository->findAll();


            $tickets_arr = TicketUtils::getTicketsArray($rows);

            echo json_encode($tickets_arr);
        }
    }
?>
