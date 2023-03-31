<?php

    require_once '../conexao/conexao.php';
    require_once '../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    function converter_data($data)
    {
        $timestamp = strtotime($data);
        $data_formatada = date("d/m/Y", $timestamp);
        return $data_formatada;
    }

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

    for ($i = 0; $i < count($resultado); $i++) {
        // print_r($resultado[$i]["NOME"]);
        $nome = $resultado[$i]["NOME"];
        $documento = $resultado[$i]["DOCUMENTO"];
        $valor_a_pagar = $resultado[$i]["VALOR_A_PAGAR"];
        $dinheiro = "Dinheiro";
        $valor_pago = $resultado[$i]["VALOR_PAGO"];
        $dt_lancto = converter_data($resultado[$i]["LANCTO"]);
        $vencto = converter_data($resultado[$i]["VENCTO"]);
        $dt_conf_pag = converter_data($resultado[$i]["DT_CONF_PAG"]);

        $linha_col = $codigos_excel['nome'] . $linha_excel;
        // print_r($linha_col);
        $planilha->setCellValue($codigos_excel['nome'] . $linha_excel, $nome);
        $planilha->setCellValue($codigos_excel['documento'] . $linha_excel, "Documento " . $documento);
        $planilha->setCellValue($codigos_excel['dinheiro'] . $linha_excel, $dinheiro);
        $planilha->setCellValue($codigos_excel['valor_a_pagar'] . $linha_excel, $valor_a_pagar);
        $planilha->setCellValue($codigos_excel['valor_pago'] . $linha_excel, $valor_pago);
        $planilha->setCellValue($codigos_excel['dt_lancto'] . $linha_excel, $dt_lancto);
        $planilha->setCellValue($codigos_excel['vencto'] . $linha_excel, $vencto);
        $planilha->setCellValue($codigos_excel['dt_conf_pag'] . $linha_excel, $dt_conf_pag);

        $linha_excel++;
    }
    $writer = new Xlsx($excel);
    $writer->save('financeiro.csv');

?>