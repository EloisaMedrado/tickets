<?php

    include_once '../utils/TicketUtils.php';
    include_once '../utils/ClassificationUtils.php';
    
    class Classification {

        public static function classifyTickets($ticket, $dateNow) {

            $priorityArray = array();
            $dateNowFormat = TicketUtils::formaterDate($dateNow);

            $newTicket = self::classifyInteractions($ticket, $dateNowFormat);
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

        private static function classifyInteractions($ticket, $dateNow) {

            $classificationScore = 0;

            //Em caso real receberia a dataTimeNow
            $dateTimeNow = new DateTime($dateNow);
            $interactionWithoutAnswer = count($ticket["interactions"]) % 2;

            if($interactionWithoutAnswer) {
                $lastInteraction = TicketUtils::getLastInteractionCustomer($ticket);
                $dateCreateInteraction = new DateTime($ticket['interactions'][$lastInteraction]["DateCreate"]);
                //Em caso real esse if não seria necessário
                if($dateTimeNow > $dateCreateInteraction) {
                    $interval = $dateCreateInteraction->diff($dateTimeNow);
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