<?php
//validação de se está logado
include("valida_login.php");

//ele valida se houve um envio de arquivo, e valida o arquivo. se for sucesso ele redireciona
//para dashboard.php para que o novo arquivo possa ser visualizado.

//as regras que eu determinei que o arquivo deve seguir estão abaixo.
//claro que elas podem ser modificadas conforme necessidade.

//1. só é aceito 1 arquivo por registro
//2. só é aceito arquivos .pdf
//3. só é aceito arquivos de até 10MB cada.

//inicio da validação:
//se esses valores n estiverem setados n causa erro. ele apenas prossegue para gerar o formulario para
//o usuario responder. quando responder será dado submit direcionando para está mesma pagina
//agr com os parametros setados pelo post ele dará tratamento adequado.
if(isset($_POST['nome'], $_POST['desc'], $_FILES['arquivo'])){
	//obtem o arquivo
	$arquivo = $_FILES['arquivo'];

	//obtem os dados
	$nome = $_POST['nome'];
	$desc = $_POST['desc'];

	//valida a descrição e o nome e o associado (pois n posso confiar no select feito no cliente). o nome deve ter entre 3 e 60 caracteres a descrição pode ser de 0 - 1024
	//caso alguns dele esteja errado refresca a pagina, senão proseguira:

	if(validacao($nome, 60, 3) === false){
		onError();//redireciona se n passar
	}

	if (validacao($desc, 1024, 0) === false){
		onError();//redireciona.
	}

	//para teste apenas. remover depois
	echo "sucesso !!";

	//passada a validação, agr é tentar validar o arquivo.

	//1. validar se houve erro de upload
	if ($arquivo['error'] === UPLOAD_ERR_OK){
		//2. valida se foi recebido apenas 1 arquivo
        if(count($_FILES['arquivo']['name']) !== 1) {
            onError();
        }

        //3. agr testo se ele é PDF apenas - protege servidor de arquivos executaveis e perigosos
        $tipoArquivo = mime_content_type($arquivo['tmp_name']);
        $permitidos = array('application/pdf'); // Lista com tipos de arquivo permitidos

        if (!in_array($tipoArquivo, $permitidos)) {
            onError();//redireciona denovo
        }

        // Verifica o tamanho do arquivo
        $maximo = 10485760; // 10MB em bytes. 10 x 1024 x 1024

        if ($arquivo['size'] > $maximo) {
            onError();
        }

        //aqui ele verificou tudo certinho. ele pode tentar salvar agr.
        //primeiro deve gerar o nome do arquivo, salvar o arquivo que demora mais tempo, depois
        //salvar esse nome no banco de dados junto com demais dados passados. no caso.

        //regra para gerar um nome para o arquivo. será um codigo gerado aleatoriamente de 16 letras maiusculas.
        //eu sei que a extensão do arquivo e .pdf sempre. então basta em loop gerar um nome e testar
        //se esse nome ja existe ou não, se já existir crio outro. existem nesse caso 26^16 possibilidades de
        //nomes possiveis que possam ser geradas.

        $codigo = codigo();
        $nome_file = "documentos/" . $codigo . "." . "pdf";

        //enquanto existir gera novo nome
        while (file_exists($nome_file)) {
        	$codigo = codigo();
        	$nome_file = "documentos/" . $codigo . "." . "pdf";
        }

        //novo nome gerado - agr e salvar o arquivo na pasta.
        if(move_uploaded_file($arquivo['tmp_name'], $nome_file)){
        	echo "sucesso - file !!!<br>";
        }

        else{
        	onError();
        }

        //salvando no banco de dados.
        include("conexao_bd.php");

        //montando a query com tratamento contra mysql injection
        //tem de salvar:
        //--------------
        //nome - varchar(60)
        //desc - varchar(1024)
        //data - timestamp atual do servidor php.
        //diretorio - $nome_file: "documentos/xxxxxxxxxxxxxxxx.pdf" como é gerado pelo php. n precisa se validar
        //contra mysql injection esse em especifico, seria perda de processamento.

        //cria a query
        $query = "INSERT INTO registro (nome, usuario, descricao, dir) VALUES (:nome, :usuario, :descricao, :dir)";

        //cria o objeto responsavel
        $salvar = $conect->prepare($query);

        //executa substituindo os valores
        $salvar->execute([
		    ':nome' => $nome,
		    ':usuario' => $_SESSION['id'],//o associado é quem envia o documento. isso ele determina automaticamente
		    ':descricao' => $desc,
		    ':dir' => $nome_file
		]);

		if ($salvar->rowCount() > 0) {
		    //Registro inserido com sucesso :)
		    $conect = null;//fecha conexão
		    header("Location: dashboard_1.php");
		    exit();
		} else {
		    //Ocorreu um erro ao inserir o registro :(
		    onError();//redireciona
		}
	}

	else{
		onError();
	}
}


//funções auxiliares desse processo
//---------------------------------

//função que gera codigo de 16 letras
function codigo(){
	$codigo = "";

	for ($i=0; $i < 16; $i++) { 
		$letra = chr(random_int(65, 90));
		$codigo .= $letra;
	}

	return $codigo;
}

//função que valida campos
function validacao($palavra, $maximo, $minimo){
	//remover espaços iniciais e finais
	$palavra = trim($palavra);

	//validar o tamanho
	$tamanho = strlen($palavra);
	if($tamanho > $maximo || $tamanho < $minimo){
		return false;
	}

	//verifica se o nome contem apenas letras a-z A-Z 0-9 espaços
	if(!preg_match('/^[a-zA-Z0-9\s]+$/', $palavra)){
		return false;
	}

	//verifica se não há espaços consecutivos
	if(strpos($palavra, '  ') !== false){
		return false;
	}

	return true;
}

//redireciona em caso de erro nas verificações de segurança
function onError(){
    header("Location: novo_registro.php");
    exit();
}

?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>novo registro</title>

	<link rel="stylesheet" type="text/css" href="estilos/padrao.css">
	<link rel="stylesheet" type="text/css" href="estilos/novo_registro.css">
</head>
<body>
	<h1>registro de documento</h1>

	<form action="novo_registro.php" method="POST" enctype="multipart/form-data">
		<!--esse é o campo do nome do registro-->
		<div>
			<label>nome do documento (60 letras)</label>
			<input type="text" name="nome" required>
		</div>

		<!--esse é o campo da descrição-->
		<div>
			<label>descrição (1024 letras)</label>
			<textarea name="desc"></textarea>
		</div>

		<!--esse é o campo do arquivo-->
		<div>
			<label>selecione o arquivo para armazenar</label>
			<input type="file" name="arquivo" id="arquivo" required>
		</div>

		<!--esse é o campo do submit-->
		<div>
			<input type="submit"></div>
		</div>
	</form>
</body>
</html>