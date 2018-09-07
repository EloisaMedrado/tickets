<?php
    class Ticket {
    
        // Conexão com banco
        private $mongo;
        //Nome da tabela
        private $bd_table_name = "projeto.tickets";
    
        // Propriedades
        public $id;
        public $ticketID;
        public $categoryID;
        public $customerID;
        public $customerName;
        public $customerEmail;
        public $dateCreate;
        public $dateUpdate;
        public $priority;
        public $interactions;
    
        // Construtor com a conexão
        public function __construct($db) {
            $this->mongo = $db;
        }
		
		// Retornando todos produtos
        function findAll() {
            $query = new MongoDB\Driver\Query([], ['sort' => [ 'CustomerName' => 1]]);
            
            $rows = $this->mongo->executeQuery("projeto.tickets", $query);
        
            return $rows;
        }
?>
