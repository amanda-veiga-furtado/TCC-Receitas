<?php

session_start();
ob_start();
include_once './conexao.php';

$id_usuario = filter_input(INPUT_GET, "id_usuario", FILTER_SANITIZE_NUMBER_INT);
var_dump($id_usuario);

if (empty($id_usuario)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: registro_usuario.php");
    exit();
}

$query_usuario = "SELECT id_usuario, nome_usuario, email_usuario FROM usuario WHERE id_usuario = $id_usuario LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

if (($result_usuario) AND ($result_usuario->rowCount() != 0)) {
    $query_del_usuario = "DELETE FROM usuario WHERE id_usuario=$id_usuario";
    $apagar_usuario = $conn->prepare($query_del_usuario);
    
    if ($apagar_usuario->execute()){
        $_SESSION['msg'] = "<p style='color: #green;'>Usuário apagado com sucesso!</p>";
        header("Location: registro_usuario.php");
    } else { 
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não apagado com sucesso!</p>";
        header("Location: registro_usuario.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: registro_usuario.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar</title>
    </head>
    <body>
    </body>
</html>
