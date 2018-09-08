<?php

class TicketUtils {

    private static function pagination($sort, $pageSize, $page) {

        //Trata com valores padrões
        $page = is_null($page) ? 1 : $page;
        $pageSize = is_null($pageSize) ? 10 : $pageSize;

        $sort['skip'] = ($pageSize * $page) - $pageSize;
        $sort['limit'] = $pageSize;

        return $sort;       
    }

    private static function formaterDate($filtroDate) {

        $date = DateTime::createFromFormat('d/m/Y', $filtroDate);
        $formatDate = $date->format('Y-m-d');

        return $formatDate;
    }

    private static function filterByDateCreateBetween($filtroDtInicio, $filtroDtFim) {

        $filter = ["DateCreate" => array('$gte' => self::formaterDate($filtroDtInicio))];
        if($filtroDtFim) {            
            $filter = ["DateCreate" => array('$gte' => self::formaterDate($filtroDtInicio), '$lte' => self::formaterDate($filtroDtFim) . " 23:59:59" )];
        }    

        return $filter;      
    }

    public static function getSort($pageSize, $page, $order) {
        $sort = ['sort' => [ "DateCreate" => 1]];
        if($order) {
            $sort = ['sort' => [ "$order" => 1]];
        }

        $sort = self::pagination($sort, $pageSize, $page);
        return $sort;
    }

    public static function getFilter($filterStartDt, $filterEndDt, $filterPriority) {
        $filter = array();
        if($filterStartDt) {
            $filter = self::filterByDateCreateBetween($filterStartDt, $filterEndDt);
        }
        if($filterPriority){
            $filter['Priority'] = $filterPriority;
        }
        return $filter;
    }

    public static function getTicketsArray($rows) {
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

    public static function getNewTicketObject($data) {
        $ticket = new Ticket();
        $ticket->setId($data['_id']);
        $ticket->setTicketID($data['TicketID']);
        $ticket->setCategoryID($data['CategoryID']);
        $ticket->setCustomerID($data['CustomerID']);
        $ticket->setCustomerName($data['CustomerName']);
        $ticket->setCustomerEmail($data['CustomerEmail']);
        $ticket->setDateCreate($data['DateCreate']);
        $ticket->setDateUpdate($data['DateUpdate']);
        $ticket->setPriority($data['Priority']);
        $ticket->setInteractions($data['Interactions']);
        return $ticket;
    }

    public static function getNewTicketArray($ticket) {
        $ticket_update = array(
            "TicketID" => htmlspecialchars(strip_tags($ticket->getTicketID())),
            "CategoryID" => htmlspecialchars(strip_tags($ticket->getCategoryID())),
            "CustomerID" => htmlspecialchars(strip_tags($ticket->getCustomerID())),
            "CustomerName" => htmlspecialchars(strip_tags($ticket->getCustomerName())),
            "CustomerEmail" => htmlspecialchars(strip_tags($ticket->getCustomerEmail())),
            "DateCreate" => $ticket->getDateCreate(),
            "DateUpdate" => $ticket->getDateUpdate(),
            "Priority" => $ticket->getPriority(),
            "Interactions" => $ticket->getInteractions()
        );
        return $ticket_update;
    }

}