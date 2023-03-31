<?php

    require_once '../conexao/conexao.php';


    $stmt = $conexao->prepare("SELECT DISTINCT EMD101.NOME, CRD111.DOCUMENTO, CRD111.VALOR AS VALOR_A_PAGAR, BXD111.VALOR AS VALOR_PAGO, CRD111.EMISSAO AS LANCTO, CRD111.VENCTO, BXD111.BAIXA AS DT_CONF_PAG
        FROM EMD101
        JOIN CRD111 ON EMD101.CGC_CPF=CRD111.CGC_CPF
        LEFT JOIN BXD111 ON CRD111.DOCUMENTO=BXD111.DOCUMENTO
        ORDER BY EMD101.NOME ASC;");
    $stmt->execute();
    $resultado = $stmt->fetchAll();

    for($i = 0; $i < count($resultado); $i++) {
        // print_r($resultado[$i]["NOME"]);
        $nome = $resultado[$i]["NOME"];
        $documento = $resultado[$i]["DOCUMENTO"];
        $valor = $resultado[$i]["VALOR_A_PAGAR"];
        print_r($nome."->");
        print_r($documento."->");
        print_r($valor."-> <br>");
    }
?>