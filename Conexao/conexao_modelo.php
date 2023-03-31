<?php
    $host = '';
    $database = '';
    $username = '';
    $password = '';

    $dsn = "firebird:host=$host;dbname=$database;charset=UTF8";

    try {
        $conexao = new PDO($dsn, $username, $password);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'Erro ao tentar se conectar com o banco: ' . $e->getMessage();
        exit();
    }
?>