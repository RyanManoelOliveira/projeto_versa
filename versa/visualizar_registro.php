<?php
	//validando o login
	include("valida_login.php");//qualquer erro que der sempre por padrão se redireciona ao login

	//ver se foi setado o alvo
	if(isset($_GET['alvo'])){
		//obtem os dados do documento alvo de uma vez. alvo no caso é seu ID

		//conectando ao bd
		include("conexao_bd.php");

		// Preparando e executando a consulta anti-mysql injectiom
		$query = "SELECT * FROM registro WHERE id = :alvo";
		$resultado = $conect->prepare($query);
		$resultado->bindParam(":alvo", $_GET['alvo']);
		$resultado->execute();

		// Obtendo o resultado da consulta
		$resultado = $resultado->fetch(PDO::FETCH_ASSOC);

		// Verificando se encontrou algum registro
		if ($resultado !== false && $resultado !== null){
		    ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>visualizar registro</title>

	<link rel="stylesheet" type="text/css" href="estilos/visualizar_registro.css">
</head>
<body>
	<main>
		<p>id documento: <?php echo $resultado['id']; ?></p>
		<p>nome documento: <?php echo $resultado['nome']; ?></p>
		<p>responsavel: <?php echo $resultado['usuario']; ?></p>
		<p>data envio: <?php echo $resultado['envio']; ?></p>
		<p>descrição:</p>

		<hr>

		<p><?php echo $resultado['descricao']; ?></p>

		<br>

		<a href="deletar_registro.php?alvo=<?php echo $resultado['id'];?>&arquivo=<?php echo $resultado['dir'];?>">deletar</a>
		<a href="donwload.php?alvo=<?php echo $linha['dir']; ?>">baixar</a>
		<a href="dashboard_1.php">voltar a dashboard</a>
	</main>

	<?php
	}

		else {
		    // Se não encontrou nenhum registro
		    header("Location: index.php");
		    exit();
		}
	}

	else{
		header("Location: index.php");
		exit();
	}
?>
</body>
</html>