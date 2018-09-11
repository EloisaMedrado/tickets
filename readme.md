# TICKETS

Projeto para exibicação de tickets de atendimento com a opção de classificação de prioridade.

### Pré-requisitos

Os itens a seguir são necessários para rodar essa aplicação

```
PHP ou XAMPP
Driver MongoDB para o PHP
MongoDB

```

### Instalação

Faça um clone desse repositório e execute os seguintes passos/comandos.

```
1) Inicie o servidor do MongoDB
```
Neste caso utilizei a imagem do MongoDB do Docker. Para instalar no Linux basta rodar os comandos:
	sudo apt install docker.io
	sudo docker run --net=host --name elo-mongo -d mongo
O banco subirá localhost na porta 27017.
	
Crie seu banco de dados e sua collection e informe os detalhes no arquivo Config.php

```
define('DB_NAME', 'nomeDoBanco');
define('DB_HOST', 'hostDoBanco');
define('DB_PORT', 'portaDoBanco');
define('DB_PASS', 'senhaDoBanco');
define('DB_USER', 'usuarioDoBanco');
define('DB_COLLECTION', 'collectionDoBanco');
define('URL_PROJECT', 'http://localhost:8000');
```
## Deploy

Agora você pode rodar o seguinte comando para iniciar a aplicação.
```
Se for utilizar o PHP para subir o servidor, acesse a pasta do projeto e execute:
 1.1) php -S endereço/porta, por exemeplo: php -S 0.0.0.0:8000
Se for utilizar o XAMPP inicie o servidor
```

## API
Um exemplo de como utilizar a API.
Nesse exemplo vou utilizar o [Postman](https://www.getpostman.com/) para testar a API utilizando a rota FindFilter.

```
1) Selecione a opção 'GET'
2) Coloque a URL: http://localhost:8000/view/FindFilter.php?startDt=13/12/2017&ascendingOrder=1&pageSize=6
	Estou filtrando todos os tickets que tenham DateCreate >= 13/12/2017 com ordenação crescente e para cada página sera retornado apenas 6 registros.
3) Clique no botão 'Send'
4) Na aba 'Body' certifique-se que ao lado de 'Preview' esteja selecionado JSON.
5) Na aba 'Pretty' você deve receber algo como:
	'{
    "paging": {
        "last": "http://localhost:8000/view/FindFilter.php?priority=&startDt=13/12/2017&endDt=&order=&ascendingOrder=1&pageSize=6&page=3",
        "lastPage": 3,
        "pages": [
            {
                "page": 1,
                "url": "http://localhost:8000/view/FindFilter.php?priority=&startDt=13/12/2017&endDt=&order=&ascendingOrder=1&pageSize=6&page=1",
                "currentPage": true
            },
            {
                "page": 2,
                "url": "http://localhost:8000/view/FindFilter.php?priority=&startDt=13/12/2017&endDt=&order=&ascendingOrder=1&pageSize=6&page=2",
                "currentPage": false
            }
        ]
    },
    "tickets": [
        {
            "id": {
                "$oid": "5b95e6fd039282ceefe9d833"
            },
            "ticketID": "28890",
            "categoryID": "57526",
            ....
     }'

Dentro da key "paging" são retornados:
	1) "last" : A URL da última página após a paginação com os filtros passados;
	2) "lastPage" : O número da última página;
	3) "pages" : a paginação feita contendo as informações de URL para cada page e se é a página atual sendo exibida.

Dentro da key "tickets" são retornados os primeiros 6 tickets encontrados com os filtros passados.

```

## Rotas da API 
As rotas disponíveis para utilização da API são:
```
http://endereço/view/FindAll.php - listagem de todos os tickets cadastrados no banco
http://endereço/view/FindFilter.php - listagem de todos os tickets filtrados cadastrados no banco
	Filtros disponíveis: 
		&startDt=01/01/2017 - Sera feito o filtro por todos os tickets que tenham DateCreate >= 01/01/2017
		&endDt=01/01/2018 - Somente fitrará quando passado startDt também. Trará os tickets que o DateCreate esteja entre &startDt e a data passada em &endDt
		&priority=Alta - Será feito o filtro por todos os tickets que estejam classificados com prioridade 'Alta'. Opções disponíveis: 'Alta' e 'Normal'
		&order=DateCreate - Será feita a ordenação de todos os tickets se baseando no DateCreate. Opções disponíveis: Priority.Status, TicketID, CategoryID, CustomerID, CustomerName, CustomerEmail, DateCreate e DateUpdate. Default: DateCreate
		&ascendingOrder=1 - Será feita a ordenação crescente de todos os tickets de acordo com o order. Opções disponíveis: 1 e -1 (crescente e decrescente respectivamente). Default: 1 (Crescente)
		&pageSize=10 - Será feito a consulta dos tickets e retornará os 10 registros da consulta. Default=10
		&page=1 - Será feita a consulta dos tickets e retornará os tickets na página 1. A quantidade de valores retornados depende do $pageSize e a quantidade de páginas depende do total de registro e do $pageSize.
http://endereço/view/Classify.php - Sera feita a classificação dos tickets existentes no banco. A classificação gerada pode ser vista acessando a rota FindAll.php
```


## Feito com

* [PHP](http://php.net/) - Linguagem utilizada
* [MongoDB](https://www.mongodb.com/) - Banco de dados utilizado

## Autores

* **Eloisa Medrado** -  [EloisaMedrado](https://github.com/EloisaMedrado)
