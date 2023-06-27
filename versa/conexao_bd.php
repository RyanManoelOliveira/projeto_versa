<?php
	$host = 'localhost'; // Host do banco de dados
	$user = 'root'; // Usuário do banco de dados
	$password = ''; // Senha do banco de dados
	$database = 'bd_versa'; // Nome do banco de dados

	//realizando uma conexão com PDO
	try {
    	$conect = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    	$conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
    	die('erro nesta tentativa de conexão: ' . $e->getMessage());
	}
?>