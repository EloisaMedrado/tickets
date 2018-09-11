<?php

    require_once '../conexao/Config.php';

    class TicketUtils {

        private static function pagination($sort, $pageSize, $page) {

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

        public static function getSort($pageSize, $page, $order, $ascendingOrder) {
            
            $sort = ['sort' => [ "DateCreate" => (int)$ascendingOrder]];
            if($order) {
                $sort = ['sort' => [ "$order" => (int)$ascendingOrder]];
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
                $filter['Priority.Status'] = $filterPriority;
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

        public static function getNewTicketArrayFromArray($ticket) {
            
            $ticket_update = array(
                "TicketID" => htmlspecialchars(strip_tags($ticket['ticketID'])),
                "CategoryID" => htmlspecialchars(strip_tags($ticket['categoryID'])),
                "CustomerID" => htmlspecialchars(strip_tags($ticket['customerID'])),
                "CustomerName" => htmlspecialchars(strip_tags($ticket['customerName'])),
                "CustomerEmail" => htmlspecialchars(strip_tags($ticket['customerEmail'])),
                "DateCreate" => $ticket['dateCreate'],
                "DateUpdate" => $ticket['dateUpdate'],
                "Priority" => $ticket['priority'],
                "Interactions" => $ticket['interactions']
            );
            return $ticket_update;
        }

        public static function getUrlPagination($filterPriority, $filterStartDt, $filterEndDt, $order, $ascendingOrder, $page, $pageSize) {
            
            return URL_PROJECT . "/view/FindFilter.php?" .
            "priority=" . $filterPriority .
            "&startDt=" . $filterStartDt .
            "&endDt=" . $filterEndDt .
            "&order=" . $order .
            "&ascendingOrder=" . $ascendingOrder .
            "&pageSize=" . $pageSize .
            "&page=" . $page;
        }

        public static function getArrayPages($page, $urlPage, $isCurrentPage) {
            
            $pages_arr = array(
                "page" => $page,
                "url" => $urlPage,
                "currentPage" => $isCurrentPage
            );
            
            return $pages_arr;
        }

        public static function getLastInteractionCustomer($ticket) {

            $lastInteraction = count($ticket['interactions']) - 1;
            if($ticket['interactions'][$lastInteraction]['Sender'] == "Expert" && $lastInteraction > 0) {
                $lastInteraction--;
            }

            return $lastInteraction;
        }

        public static function filterIfLastInteractionClassified($tickets_arr) {

            $filteredTickets = array_filter($tickets_arr, function($ticket) {
                $lastInteraction = self::getLastInteractionCustomer($ticket);
                return !$ticket['interactions'][$lastInteraction]['ClassificationScore'];
            });
            
            return $filteredTickets;
        }
    }
?>