<?php

    session_start(); // Inicia a sessão
    ob_start(); // Inicia o buffer de saída

    include_once '..\conexao.php';

    $id_receita = filter_input(INPUT_GET, "id_receita", FILTER_SANITIZE_NUMBER_INT); // Obtém o ID do receita da URL e filtra como um número inteiro
    var_dump($id_receita);

    if (empty($id_receita)) { // Verifica se o ID do receita está vazio
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Receita não encontrada!</p>";
        header("Location: listagem_receita.php");
        exit();
    }

    $query_receita = "SELECT id_receita FROM receita WHERE id_receita = $id_receita LIMIT 1";
    $result_receita = $conn->prepare($query_receita);
    $result_receita->execute();

    if (($result_receita) AND ($result_receita->rowCount() != 0)) {
        $query_del_receita = "DELETE FROM receita WHERE id_receita=$id_receita";
        $apagar_receita = $conn->prepare($query_del_receita);
        
        if ($apagar_receita->execute()){
            $_SESSION['msg'] = "<p style='color: #green;'>Receita apagado com sucesso!</p>";
            header("Location: listagem_receita.php");
         } else { 
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Receita não apagada com sucesso!</p>";
            header("Location: listagem_receita.php");
        }
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Receita não encontrada!</p>";
         header("Location: listagem_receita.php");
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
