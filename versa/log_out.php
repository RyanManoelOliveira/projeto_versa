<?php
	//caso queira deslogar do site
	session_start();

	// Destruir a sessão
	session_destroy();

	// Redirecionar de volta para o login
	header("Location: index.php");
	exit();
?>