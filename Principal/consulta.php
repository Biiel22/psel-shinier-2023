<?php

    require_once 'conexao.php';


    $stmt = $conexao->prepare("SELECT * FROM EMD101");
    $stmt->execute();
    $resultado = $stmt->fetchAll();

    $codigo = ($resultado[0]["NOME"]);
    $CGC = ($resultado[0]["CGC_CPF"]);
    $DTCAD = ($resultado[0]["DT_CADASTRO"]);
    print_r($codigo . "<br>");
    print_r($CGC . "<br>");
    print_r($DTCAD . "<br>");
    
?>