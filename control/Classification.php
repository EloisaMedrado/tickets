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

            $classificationScore = 0;
            //Em caso real receberia a dataTimeNow
            $datetimeNow = new DateTime("2017-12-28");
            $interactionWithoutAnswer = count($ticket["interactions"]) % 2;

            if($interactionWithoutAnswer) {
                $lastInteraction = TicketUtils::getLastInteractionCustomer($ticket);
                $dateCreateInteraction = new DateTime($ticket['interactions'][$lastInteraction]["DateCreate"]);
                //Em caso real esse if não seria necessário
                if($datetimeNow > $dateCreateInteraction) {
                    $interval = $dateCreateInteraction->diff($datetimeNow);
                    $classificationScore = ($interval->format('%a')) * WEIGHT_SCORE_DAY;
                }
            }

            foreach ($ticket["interactions"] as &$interaction) {
                if($interaction['Sender'] == "Customer" && !$interaction["ClassificationScore"]){
                    $classificationScore = self::classifyInteraction($interaction['Subject'] . $interaction['Message'], $classificationScore);
                    $interaction["ClassificationScore"] = $classificationScore;
                }
            }

            return $ticket;
        }

        private static function classifyInteraction($text, $classificationScore) {

            foreach(ClassificationUtils::getExpressionAndWeight() as $key => $value) {
                preg_match($key, $text, $matches);
                if($matches) {
                    $classificationScore = $classificationScore + $value;
                }
            }

            return ($classificationScore <= MAX) ? $classificationScore : MAX;
        }
    }
?>