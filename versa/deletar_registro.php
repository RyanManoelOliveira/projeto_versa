<?php
	//testa login
	include("valida_login.php");

	// Verificar se o parâmetro alvo consta na url
	if (isset($_GET['alvo'], $_GET['arquivo'])) {
	    $alvo = $_GET['alvo'];
	}

	else{
	    // Caso não exista, redireciona para index.php
	    header("Location: index.php");
	    //echo "não deu 1"; //para teste
		exit();
	}
		
	//operação no bd a prova de sql-injection
	//---------------------------------------
	//realizando a conexão com o banco de dados
	include("conexao_bd.php");

	//realizando a exclusão - usando prepared em PDO
	$query = "DELETE FROM registro WHERE id = :alvo";
	$exclusao = $conect->prepare($query);
	$exclusao->bindParam(':alvo', $alvo);
	$exclusao->execute();

	$arquivo = $_GET['arquivo'];

	//deletando o file agr
	if (file_exists($arquivo)) {
    	if (unlink($arquivo)) {
    		//para teste - apos isso testa o bd e redireciona para dashboard_1.php
        	echo 'Arquivo deletado com sucesso<br>';
    	}

    	else {
    		//neste caso deu erro.
        	echo 'Erro ao deletar o arquivo.';
        	onError();
    	}
	}

	else {
    	onError();
	}

	//verifica se as operações foram bem sucedida
	if($exclusao->rowCount() > 0){
		//redirecionando de volta para dashboard.php
		header("Location: dashboard_1.php");
		exit();
	}

	//redirecionando de volta para index.php - deu erro na exclusão
	header("Location: index.php");
	exit();
?>