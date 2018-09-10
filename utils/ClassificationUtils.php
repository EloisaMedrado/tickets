<?php

    define('LIMIAR',70);
    define('ALTA',"Alta");
    define('NORMAL',"Normal");

    class ClassificationUtils {

        public static function isHighPriority($ticket) {
            return $ticket['interactions'][$qtInteractions]['ClassificationScore'] >= LIMIAR;
        }

        public static function getExpressionAndWeight() {
            return Array(
                "/\?/iU" => 2,
                "/reclama../iU" => 30,
                "/manchan?do/iU" => 15,
				"/(\sestrag|danifica|\sdano\s|problema)/iU" => 15,
                "/diferente/iU" => 20,
				"/(queria|gostaria).{0,8}saber/iU" => 10,
                "/(informa..|solu..|not..?.?vel|aguardo)/iU" => 5,
                "/reenvi|demora/iU" => 10,
                "/o\s*qu?e?\s*e?u?(devo)?\s*(fazer|fa..?.?o)/iU" => 10,
                "/((compra|produto|pedido|entreg).{0,20}\sn..?.?o\s*(foi)?\s*(entreg|realizada|cheg))/iU" => 25,
                "/((\s|^)n..?.?o\s*.{0,10}(receb|entreg).{0,15}(compra|produto|pedido))/iU" => 25,
                "/debit.{0,15}fatura/iU" => 15,
                "/\sprovid..?.?ncia/iU" => 10,
                "/\sprocon/iU" => 40,
                "/\sreclame\s*aqui/iU" => 40,
                "/confirm.{0,10}pagamento/iU" => 12,
                "/forma.{0,10}pagamento/iU" => 15,
                "/(data|prazo|andamento).{0,8}(entrega|pedido)/iU" => 10,
                "/preciso/iU" => 5,
				"/cupom.{0,8}desconto/iU" => 5,
                "/(((pagamento|pagto).{0,10}confirmado)|(confirma...{0,8}(pagamento|pagto)))/iU" => 10,
                "/resolver/iU" => 5,
                "/tentativa.{0,8}contato/iU" => 20,
                "/(\s|^)n..?.?o\s*consigo\s*acess/iU" => 20,
                "/troc.{0,10}produto/iU" => 30,
                "/nome.{0,8}outra\s*pessoa/iU" => 30,
                "/produto.{0,15}errado/iU" => 30,
                "/cancela/iU" => 20,
                "/(\s|^)n..?.?o.{0,20}fun?ciona/iU" => 35,
                "/cadastr.{0,15}errado/iU" => 20,
                "/(corrigir|corre..|aconteceu|procurando|sistema|\saten..|sugest..|\sd..?.?vida|dispon..?.?vel|\srastre)/iU" => 5,
				"/(\s|^)n..?.?o.{0,10}enviado/iU" => 20,
				"/(\s|^)mud[eao].{0,15}endere.?.?.o.{0,15}entreg/iU" => 20,
				"/endere..?.?o.{0,10}antigo/iU" => 10,
				"/(compr.{0,15}produto|((fiz|fazer|realizei|efetuei).{0,10}(pedido|compra)))/iU" => 10,
				"/rastreio.{0,12}(produto|compra|pedido)/iU" => 15
            );
        }
        
    }
?>