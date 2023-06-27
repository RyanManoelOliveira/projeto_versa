<?php
session_start();

// Verificar se a sessão do usuário criada no login não existe
if(!isset($_SESSION['usuario'], $_SESSION['id'])){
    // Redirecionar de volta para o login
    header("Location: index.php");
    exit();
}
?>