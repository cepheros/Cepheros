<?php
    header('Content-type: text/html; charset=utf-8');
    include '../OB_init.php';

    $ob = new OB('001');

    //*
    $ob->Vendedor

            ->setAgencia('9999')
            ->setConta('00099999')
            ->setCarteira('18-7')
            ->setRazaoSocial('José Claudio Medeiros de Lima')
            ->setCpf('012.345.678-39')
            ->setEndereco('Rua dos Mororós 111 Centro, São Paulo/SP CEP 12345-678')
            ->setEmail('joseclaudiomedeirosdelima@uol.com.br')
			->setCodigoCedente('7777777')
        ;

    $ob->Configuracao
            ->setLocalPagamento('Pagável em qualquer banco até o vencimento')
        ;

    $ob->Template
            ->setTitle('PHP->OB ObjectBoleto')
            ->setTemplate('html5')
        ;

    $ob->Cliente
            ->setNome('Maria Joelma Bezerra de Medeiros')
            ->setCpf('111.999.888-39')
            ->setEmail('mariajoelma85@hotmail.com')
            ->setEndereco('')
            ->setCidade('')
            ->setUf('')
            ->setCep('')
        ;

    $ob->Boleto
            ->setValor(2952.95)
            //->setDiasVencimento(5)
            ->setVencimento(6,9,2011)
            ->setNossoNumero('87654')
            ->setNumDocumento('27.030195.10')
            ->setQuantidade(1)
        ;

    $ob->render(); /**/
