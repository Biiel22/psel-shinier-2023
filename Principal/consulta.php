<?php
    ini_set('memory_limit', '1048M');
    ini_set('max_execution_time', 1000);
    require_once '../conexao/conexao.php';
    require_once '../vendor/autoload.php';
    require_once '../chaves_requisicao.php';

    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\IOFactory;

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

    $excel = IOFactory::load("financeiro.xlsx");
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
    $writer->save('financeiro.xlsx');

    $file_path = 'financeiro.xlsx';

    $spreadsheet = IOFactory::load($file_path);

    $worksheet = $spreadsheet->getActiveSheet();

    $data = $worksheet->toArray();

    $csv_path = 'financeiro.csv';

    $csv_file = fopen($csv_path, 'w');

    foreach ($data as $row) {
        fputcsv($csv_file, $row);
    }

    fclose($csv_file);

    echo "Arquivo CSV gerado com sucesso!";

    $url = 'https://psel.apoena.shinier.com.br/api/login';
    $data = array(
        'email' => $email,
        'group_key' => 'Client',
        'password' => $senha
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    $data = json_decode($result);
    $token = $data->token;
    $user_id = $data->user->id;

    $filepath = 'financeiro.csv';
    // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5OGM4ZGZhOS0zZWMwLTQ4MmUtYTA4ZC1lOGJlYzhhYmEwNDYiLCJqdGkiOiJjYTk0YzliN2I0OGQwMWVmMDc2OGQ5MDFhY2NkZDA5NWIxODRkZWIzMjE0YmRhMzBiZTg5YzlkMTgwZTkyZjA1YjA4ZTYwODljNmVhN2NjOCIsImlhdCI6MTY4MDMxNDc5OS4xOTY0OTksIm5iZiI6MTY4MDMxNDc5OS4xOTY1MDQsImV4cCI6MTcxMTkzNzE5OS4xODgxNjMsInN1YiI6IjBlNGZjYTQyLTllYTktNGUxMS05NGIxLWZiZTE1OTQzYTA2MiIsInNjb3BlcyI6W119.tDiAIGZft7gNOd3mpfZn0PugtsF6d8-orjJIXuQQoPgBT6J23wA2Q_QcQb9B1YwYprP-ybMTe1jIm1-c1gVNie2QLixSBZkepLnVP0hPRbccbztwcBPJ0aWIqhtFGahaC9CxS-g6lmYubHFAWJQTnnuZLPuqktbR5BFIiaje1_rWc3S76GkfluFMlGBwfZCV7aYKJo-7iD9EhlpH1oZV8UwA2JUiRy0hfL3cdruak-UkLFVVy8l_3wyFOsk5iVgHET0SbAdIoaQPjx3zfrmxD62UmIKYYXrcxH9N7Fvss1zBatf-boyL9a8m77-5jRSWPbxeS8V5dOmeDheYnkNFS9T3Bhrm_228YMjBpE8Fgua7kgwRuYLi2EBD1XNdY6i7tsIb0sdnGMXg2tZRGbeS1zw_CzycLy-KC2K3idzmY-EIi9Xq1TEZ67OFrNl9evAiNwEBoCY6mHnlsNTAQQtRMoDc798J3ZchI-E71kUC67eh3ebknk2Wp4OOk4puzRHlHtKJO8GhK85L9sv2G4vkQ_AVvdjH2KLmrIjgYA3fKnNss5dDBVwjxE_Qi9MQpNjlL_Nlkzllxnm4EgmZPNSMLwQQaTtJF3Ov_qrrqeXk3p0rPTK3YmoTCPfe8RMfQpp22PtkoqhRRAlx0oUQd5Nha7NVWMCLt7O6JHrnENmbW14";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://psel.apoena.shinier.com.br/api/import/create');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    $postFields = array(
        'file' => new CurlFile($filepath),
        'type' => 'psel-shinier-2023',
        'erp' => 'Psel',
        'user_id' => $user_id
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    $headers = array(
        'Authorization: Bearer ' . $token,
        'Content-Type: multipart/form-data',
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        echo 'cURL Error #:' . $err;
    } else {
        echo $result;
    }

?>