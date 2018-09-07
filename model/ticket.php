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
		
		//Adiona ao sort a paginação
		function pagination($sort, $pageSize, $page) {

            //Trata com valores padrões
            $page = is_null($page) ? 1 : $page;
            $pageSize = is_null($pageSize) ? 10 : $pageSize;

            $sort['skip'] = ($pageSize * $page) - $pageSize;
            $sort['limit'] = $pageSize;

            return $sort;       
        }
		
		//Formata data
		function formaterDate($filtroDate) {

            $date = DateTime::createFromFormat('d/m/Y', $filtroDate);
            $formatDate = $date->format('Y-m-d');

            return $formatDate;
        }

		//Cria o filtro de data
        function filterByDateCreateBetween($filtroDtInicio, $filtroDtFim) {

            $filter = ["DateCreate" => array('$gte' => $this->formaterDate($filtroDtInicio))];
            if($filtroDtFim) {            
                $filter = ["DateCreate" => array('$gte' => $this->formaterDate($filtroDtInicio), '$lte' => $this->formaterDate($filtroDtFim) . " 23:59:59" )];
            }    

            return $filter;      
        }
		
		//Realiza consulta com filtros, ordem e paginação
		function findByDateCreateBetweenAndPriorityAndOrder($filterPriority, $filterStartDt, $filterEndDt, $order, $page, $pageSize) {

            $sort = ['sort' => [ "DateCreate" => 1]];
            if($order) {
                $sort = ['sort' => [ "$order" => 1]];
            }

            $sort = $this->pagination($sort, $pageSize, $page);
            
            $filter = array();
            if($filterStartDt) {
                $filter = $this->filterByDateCreateBetween($filterStartDt, $filterEndDt);
            }
            if($filterPriority){
                $filter['Priority'] = $filterPriority;
            }          

            $query = new MongoDB\Driver\Query($filter, $sort);
            $rows = $this->mongo->executeQuery("projeto.tickets", $query);

            return $rows;
        }
?>
