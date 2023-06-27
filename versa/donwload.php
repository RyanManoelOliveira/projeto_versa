<?php
//verifica login
include("valida_login.php");

//valida objetivo
if(isset($_GET['alvo'])){

    $arquivo = $_GET['alvo'];
    $nome_arquivo = 'donwload.pdf';

    // Verifica se o arquivo existe
    if (file_exists($arquivo)) {
        // Define os cabecalhos para a transferência
        header('Content-Description: Transferência de Arquivo');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($arquivo));
        // Envia o conteúdo do arquivo
        readfile($arquivo);
        exit;
    }

    else{
        //deu erro - vái para index
        onError();
    }

}

else{
    //erro vai para index
    onError();
}

    
?>