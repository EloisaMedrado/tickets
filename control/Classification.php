<?php

    include_once '../utils/TicketUtils.php';
    include_once '../utils/ClassificationUtils.php';
    
    class Classification {

        public static function classifyTickets($ticket) {

            $priorityArray = array();

            $newTicket = self::classifyInteractions($ticket);
            $lastInteraction = TicketUtils::getLastInteractionCustomer($newTicket);      

            if(ClassificationUtils::isHighPriority($newTicket)) {
                $priorityArray['Status'] = ALTA;
            } else {
                $priorityArray['Status'] = NORMAL;
            }

            $priorityArray['Score'] = $newTicket['interactions'][$lastInteraction]['ClassificationScore'];
            $newTicket['priority'] = $priorityArray;

            return $newTicket;
        }

        private static function classifyInteractions($ticket) {

            foreach ($ticket["interactions"] as &$interaction) {
                if($interaction['Sender'] == "Customer" && !$interaction["ClassificationScore"]){
                    $classificationScore = self::classifyInteraction($interaction['Subject'] . $interaction['Message']);
                    $interaction["ClassificationScore"] = $classificationScore;
                }
            }

            return $ticket;
        }

        private static function classifyInteraction($text) {

            $classificationScore = 0;
            foreach(ClassificationUtils::getExpressionAndWeight() as $key => $value) {
                preg_match($key, $text, $matches);
                if($matches) {
                    $classificationScore = $classificationScore + $value;
                }
            }

            return $classificationScore;
        }
    }
?>