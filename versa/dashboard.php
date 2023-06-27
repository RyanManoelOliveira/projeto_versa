<!--1° versão do dashboard, desconsiderar ela.-->

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>dashboard documentos</title>

	<link rel="stylesheet" type="text/css" href="estilos/padrao.css">
	<link rel="stylesheet" type="text/css" href="estilos/dashboard.css">
</head>
<body>

	<!--armazena toda a conteudo dos documentos-->
	<main>
		<!--lista todos os documentos disponiveis na tabela registro-->
		<?php
			//realiza a conexão para trazer os dados do documento (id / nome / data de publicação)
			include("conexao_bd.php");

			//seleciono todos os registro da tabela de registro e ordeno eles de forma que o mais recente apareca primeiro.
			//n ha necessidade de tratar sql injection pois a query abaixo e estatica, não imporpora dados externos, no meu ponto de vista é claro.
			$query = "SELECT * FROM registro ORDER BY envio DESC";
			$resultado = $conect->query($query);

			//testa se a conexão foi um sucesso
			if($resultado){

				$linhas = $resultado->fetchAll(PDO::FETCH_ASSOC);

				foreach ($linhas as $linha){
					?>
						<div class="doc-item">
							<div class="doc-container">
								<p class="doc-id"><?php echo "$linha[id]"; ?></p>
								<p class="doc-nome"><?php echo "$linha[nome]"; ?></p>
								<p class="doc-data"><?php echo "$linha[envio]"; ?></p>
							</div>

							<p class="title-desc">descrição:</p>

							<div class="doc-container">
								<p class="doc-descricao"><?php echo "$linha[descricao]"; ?></p>
							</div>

							<p class="title-desc">opções:</p>

							<div class="doc-container">
								<p class="doc-donwload"><a href="#">baixar</a></p>
								<p class="doc-delete"><a href="deletar_registro.php?alvo=<?php echo "$linha[id]"; ?>">deletar</a></p>
								<p class="doc-view"><a href="#">visualizar</a></p>
							</div>
						</div>
							
					<?php
				}
			}

			else{
				echo "deu erro";
			}

			//fechando conexão com o bd
			$conect = null;?>
	</main>

	<div class="doc-painel-controler">
		<p class="doc-create" id="doc-new">criar registro</p>
		<input type="text" class="doc-seach" id="doc-seach-text" placeholder="nome do documento para pesquisar">
		<p class="doc-seach-action" id="doc-seach-btn">pesquisar</p>
	</div>

	<!--javascript para capturar o evento de clique e executar de acordo-->
	<script type="text/javascript" src="js/dashboard.js"></script>
</body>
</html>