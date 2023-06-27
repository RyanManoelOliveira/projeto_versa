<?php
	session_start();//crio a minha sessão no servidor

	//agora eu testo para saber se recebi algo pelo POST
	if(isset($_POST['usuario'], $_POST['senha'])){
		$nome = $_POST['usuario'];
		$senha = $_POST['senha'];

		//neste caso eu valido a senha e o usuario no banco de dados.
		//se sucesso. vai para dashboard.php
		//se falhar ele gera a tela de login novamente

		//conetando com o bd
		include("conexao_bd.php");

		$query = "SELECT * FROM usuario WHERE usuario = :usuario";
		$resultado = $conect->prepare($query);

		if($resultado){
			$resultado->bindParam(":usuario", $nome);
			$resultado->execute();

			//obtem resultado da consulta
			$resultado = $resultado->fetch(PDO::FETCH_ASSOC);

			//apos usar o bd eu fecho a conexão
			$conect = null;
		}

		if ($resultado && $resultado['senha'] === $senha) {
            // Autenticação bem-sucedida
            $_SESSION['usuario'] = $resultado['usuario'];
            $_SESSION['id'] = $resultado['id'];//salva o id do usuario. isso é importante. com ele. posso associar qualquer documento que a pessoa enviar com quem enviou para fins de controle futuro.

            // Redirecionar para a página de dashboard_1.
            header("Location: dashboard_1.php");
            exit();
        }
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>bem vindo ao documentos</title>

	<link rel="stylesheet" type="text/css" href="estilos/index.css">
</head>
<body>
	<div>
		<form action="index.php" method="POST">
			<h2>Versa Saúde</h2>
			<h3>Soluções tecnologicas: E-SUS</h3>
			<input type="text" name="usuario" placeholder="nome de usuario" autofocus>
			<input type="password" name="senha" placeholder="sua senha">
			<input type="submit" value="entrar">
		</form>
	</div>
</body>
</html>