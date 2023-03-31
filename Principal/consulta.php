<?php

    require_once '../conexao/conexao.php';
    require_once '../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $codigos_excel = array(
        "nome" => "B",
        "documento" => "C",
        "dinheiro" => "D",
        "valor_a_pagar" => "E",
        "valor_pago" => "F",
        "dt_lancto" => "G",
        "vencto" => "H",
        "dt_conf_pag" => "I"
    );  

    $stmt = $conexao->prepare("SELECT DISTINCT EMD101.NOME, CRD111.DOCUMENTO, CRD111.VALOR AS VALOR_A_PAGAR, BXD111.VALOR AS VALOR_PAGO, CRD111.EMISSAO AS LANCTO, CRD111.VENCTO, BXD111.BAIXA AS DT_CONF_PAG
        FROM EMD101
        JOIN CRD111 ON EMD101.CGC_CPF=CRD111.CGC_CPF
        LEFT JOIN BXD111 ON CRD111.DOCUMENTO=BXD111.DOCUMENTO
        ORDER BY EMD101.NOME ASC;");
    $stmt->execute();
    $resultado = $stmt->fetchAll();

    $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load("financeiro.xlsx");
    $planilha = $excel->getActiveSheet();

    $linha_excel = 3;

    for($i = 0; $i < count($resultado); $i++) {
        // print_r($resultado[$i]["NOME"]);
        $nome = $resultado[$i]["NOME"];
        $documento = $resultado[$i]["DOCUMENTO"];
        $valor_a_pagar = $resultado[$i]["VALOR_A_PAGAR"];
        $valor_pago = $resultado[$i]["VALOR_PAGO"];
        $dt_lancto = $resultado[$i]["LANCTO"];
        $vencimento = $resultado[$i]["VENCTO"];
        $dt_conf_pag = $resultado[$i]["DT_CONF_PAG"];

        $planilha->setCellValue('B3', $resultado[2]['NOME']);
    }

    $writer = new Xlsx($excel);
    $writer->save('financeiro.xlsx');

    

?>