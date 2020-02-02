<?php

// conexao com o banco
$connStr = "host=localhost port=5432 dbname=ESTUDO user=postgres password=postgres";
$conn = pg_connect($connStr);

var_dump($conn);

// verifica se a conexao funcionou
if ($conn) {
    echo 'conectou!';
}
else {
    echo 'não conectou';
}

echo '<pre>';

// abrindo o arquivo csv
$csvFile = fopen('teste.csv', 'r');

// tabela
$table = 'credenciamento.tb_usuario';

// array com o nome dos campos da tabela
$keys = ['nome', 'email', 'idade', 'data_nascimento', 'ativo'];

// verifica se o arquivo csv foi aberto com sucesso
if ($csvFile !== FALSE) {
    echo 'abriu<br>';
    $contador = 1;
    
    // comeca a construir o sql
    $sql = "INSERT INTO {$table} (";
    foreach ($keys as $key) {
        $sql .= "\"{$key}\",";
    }
    $sql[-1] = ')'; // substitui o ultimo caractere por ')'
    $sql .= " VALUES \n";
    
    while (($arrayCsv = fgetcsv($csvFile)) !== FALSE) {
        $sql .= "\t(";
        //var_dump($arrayCsv);break;
        
        foreach ($arrayCsv as $value) {
            $sql .= "'{$value}',";
        }
        $sql = substr($sql, 0, -1); // retira o ultimo caractere
        $sql .= "),\n";
        $contador++;
    }
    
    $sql[-2] = ';'; // substitui o penultimo caractere por ';'
    echo $sql;
    
    // tenta fazer a insercao dos dados na tabela
    try {
        $result = pg_query($conn, $sql);   
        var_dump($result);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    
}
else {
    echo '<p>Erro ao abrir o arquivo!<p>';
}