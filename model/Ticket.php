<?php
    class Ticket {
    
        private $id;
        private $ticketID;
        private $categoryID;
        private $customerID;
        private $customerName;
        private $customerEmail;
        private $dateCreate;
        private $dateUpdate;
        private $priority;
        private $interactions;
        
        public function getId() {
                return $this->id;
        }

        public function setId($id) {
                $this->id = $id;

                return $this;
        }

        public function getTicketID() {
                return $this->ticketID;
        }

        public function setTicketID($ticketID) {
                $this->ticketID = $ticketID;

                return $this;
        }

        public function getCategoryID() {
                return $this->categoryID;
        }

        public function setCategoryID($categoryID) {
                $this->categoryID = $categoryID;

                return $this;
        }

        public function getCustomerID() {
                return $this->customerID;
        }

        public function setCustomerID($customerID) {
                $this->customerID = $customerID;

                return $this;
        }

        public function getCustomerName() {
                return $this->customerName;
        }

        public function setCustomerName($customerName) {
                $this->customerName = $customerName;

                return $this;
        }

        public function getCustomerEmail() {
                return $this->customerEmail;
        }

        public function setCustomerEmail($customerEmail) {
                $this->customerEmail = $customerEmail;

                return $this;
        }

        public function getDateCreate() {
                return $this->dateCreate;
        }

        public function setDateCreate($dateCreate) {
                $this->dateCreate = $dateCreate;

                return $this;
        }

        public function getDateUpdate() {
                return $this->dateUpdate;
        }

        public function setDateUpdate($dateUpdate) {
                $this->dateUpdate = $dateUpdate;

                return $this;
        }

        public function getPriority() {
                return $this->priority;
        }

        public function setPriority($priority) {
                $this->priority = $priority;

                return $this;
        }

        public function getInteractions() {
                return $this->interactions;
        }

        public function setInteractions($interactions) {
                $this->interactions = $interactions;

                return $this;
        }
    }
?>
