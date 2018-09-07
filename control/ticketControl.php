<?php
    // Includes like imports
    include_once '../model/ticket.php';
    include_once '../conexao/conexao.php';

    class TicketControl {

        function findAll() {

            $mongo = Conexao::getInstance();
            $ticket = new Ticket($mongo);

            //Executa query
            $rows = $ticket->findAll();

            //Array tickets
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
                array_push($tickets_arr, $row);
            }

            echo json_encode($tickets_arr);
        }
    }
?>
