<?php

    session_start(); // Inicia a sessão
    ob_start(); // Inicia o buffer de saída
    include_once 'C:\wamp64\www\TCC\tcc_receitas\conexao.php'; // Inclui o arquivo de conexão com o banco de dados.

    $id_usuario = filter_input(INPUT_GET, "id_usuario", FILTER_SANITIZE_NUMBER_INT); // Obtém o ID do usuário da URL e filtra como um número inteiro
    var_dump($id_usuario);

    if (empty($id_usuario)) { //// Verifica se o ID do usuário está vazio
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
        <title>Deletar</title>
    </head>
    <body>
    </body>
</html>
