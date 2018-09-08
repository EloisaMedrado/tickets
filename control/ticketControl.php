<?php
    // Includes like imports
    include_once '../model/ticket.php';
    include_once '../conexao/conexao.php';

    class TicketControl {
		
		private function getTicketsArray($rows) {
            $tickets_arr = array();
            foreach ($rows as $row) {
                $ticket_item = array(
                    "id" => $row->_id,
                    "ticketID" => $row->TicketID,
                    "categoryID" => $row->CategoryID,
                    "customerID" => $row->CustomerID,
                    "customerName" => html_entity_decode($row->CustomerName),
                    "customerEmail" => html_entity_decode($row->CustomerEmail),
                    "dateCreate" => $row->DateCreate,
                    "dateUpdate" => $row->DateUpdate,
                    "priority" => $row->Priority,
                    "interactions" => $row->Interactions
                );
                array_push($tickets_arr, $ticket_item);
            }
            return $tickets_arr;
        }
		
		function findByAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $mongo = Conexao::getInstance();
            $ticket = new Ticket($mongo);

            $rows = $ticket->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize);

            //Array tickets
            $tickets_arr = $this->getTicketsArray($rows);

            //Retorna resultados da pag 1
            if(!$tickets_arr) {
                $rows = $ticket->findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, 1, 10);
                $tickets_arr = $this->getTicketsArray($rows);
            }

            echo json_encode($tickets_arr);
        }

        function findAll() {

            $mongo = Conexao::getInstance();
            $ticket = new Ticket($mongo);

            //Executa query
            $rows = $ticket->findAll();

            //Array tickets
            $tickets_arr = $this->getTicketsArray($rows);

            echo json_encode($tickets_arr);
        }
		
		function update($obj) {
            
            $data = json_decode($obj, true);

            $mongo = Conexao::getInstance();
            
            $insere = !$data['_id'];

            //Inicializando objeto com a conexão | setando propriedades
            $ticket = new Ticket($mongo);
            $ticket->id = $data['_id'];
            $ticket->ticketID = $data['TicketID'];
            $ticket->categoryID = $data['CategoryID'];
            $ticket->customerID = $data['CustomerID'];
            $ticket->customerName = $data['CustomerName'];
            $ticket->customerEmail = $data['CustomerEmail'];
            $ticket->dateCreate = $data['DateCreate'];
            $ticket->dateUpdate = $data['DateUpdate'];
            $ticket->priority = $data['Priority'];
            $ticket->interactions = $data['Interactions'];

            $retorno = $ticket->update();
            return $retorno;
            // return ($ticket->update($obj));
        }
    }
?>
