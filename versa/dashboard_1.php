<?php
	//validando login :)
	include("valida_login.php");
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>dashboard documentos versa!!!</title>

	<link rel="stylesheet" type="text/css" href="estilos/dashboard_1.css">
</head>
<body>
	<header>
		<ul>
			<li class="ativo"><label>documento</label></li>
			<li><label><a href="novo_registro.php">criar<br>documento</a></label></li>
			<li><label>aba 2</label></li>
			<li><label>aba 3</label></li>
			<li><label><a href="log_out.php">log-out</a></label></li>
		</ul>
	</header>

	<!-- c é abreviação de container, qualquer classe que começe com c significa que é container de algo -->
	<main>
		<div class="c-cabecalho-registro">
			<p class="c-id">ID</p>
			<p class="c-nome">NOME DO REGISTRO</p>
			<p class="c-data">DATA ENVIO</p>
			<p class="c-resp">RESPONSAVEL</p>
		</div>

		<?php
			//iniciando uma pesquisa no banco de dados
			//montar o resultado da pesquisa de dashboard

			//conectando ao bd
			include("conexao_bd.php");

			//montando a query --ordena de forma descrecente pela data--
			$query = "SELECT * FROM registro ORDER BY envio DESC";
			$resultado = $conect->query($query);

			//testa se a conexão foi um sucesso
			if($resultado){

				//obtem todas as linhas da tabela registro
				$linhas = $resultado->fetchAll(PDO::FETCH_ASSOC);

				//itera sobre todas essas linhas
				foreach ($linhas as $linha){
					//obtem nome do responsavel
					//Preparar e executar a consulta
					$query = "SELECT usuario FROM usuario WHERE id = :id";
					$nome_resp = $conect->prepare($query);
					$nome_resp->bindParam(":id", $linha['usuario']);
					$nome_resp->execute();

					// Obter o valor da coluna usuario apenas, cada usuario tem nome unico, logo n preciso iterar
					$nome_resp = $nome_resp->fetch(PDO::FETCH_ASSOC);

					?>

						<!--itens da pesquisa-->
						<div class="c-item">
							<p class="c-id"><?php echo $linha['id'];?></p>
							<p class="c-nome"><?php echo $linha['nome'];?></p>
							<p class="c-data"><?php echo $linha['envio'];?></p>
							<p class="c-resp"><?php echo $nome_resp['usuario'];?></p>
						</div>

						<!--relativo as opções do item da pesquisa-->
						<div class="c-opcao">
							<p><a href="visualizar_registro.php?alvo=<?php echo $linha['id'];?>">ver detalhado</a></p>
							<p><a href="donwload.php?alvo=<?php echo $linha['dir']; ?>">baixar</a></p>
							<p><a href="deletar_registro.php?alvo=<?php echo $linha['id'];?>&arquivo=<?php echo $linha['dir'];?>">deletar</a></p>
						</div>

					<?php
				}
			}

			//deu algum erro
			else{
				echo "deu erro - informe ao suporte tecnico: tel. (33) 99704-2075";
				die();
			}

			//fechando conexão com o bd
			$conect = null;

			//fim do bloco php
			?>
	</main>
</body>
</html>