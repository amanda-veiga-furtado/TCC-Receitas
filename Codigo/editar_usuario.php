<?php
session_start();
ob_start();
include_once './conexao.php';

$id_usuario = filter_input(INPUT_GET, "id_usuario", FILTER_SANITIZE_NUMBER_INT);

if (empty($id_usuario)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: registro_usuario.php");
    exit();
}

$query_usuario = "SELECT id_usuario, nome_usuario, email_usuario FROM usuario WHERE id_usuario = $id_usuario LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->execute();

if (($result_usuario) AND ($result_usuario->rowCount() != 0)) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_usuario);
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: registro_usuario.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar</title>
    </head>
    <body>
        <a href="registro_usuario.php">Listar</a><br>
        <a href="cadastrar.php">Cadastrar</a><br>

        <h1>Editar</h1>

        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //Verificar se o usuário clicou no botão
        if (!empty($dados['EditUsuario'])) {
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } elseif (!filter_var($dados['email_usuario'], FILTER_VALIDATE_EMAIL)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";
            }

            if (!$empty_input) {
                $query_up_usuario= "UPDATE usuario SET nome_usuario=:nome_usuario, email_usuario=:email_usuario WHERE id_usuario=:id_usuario";
                $edit_usuario = $conn->prepare($query_up_usuario);
                $edit_usuario->bindParam(':nome_usuario', $dados['nome_usuario'], PDO::PARAM_STR);
                $edit_usuario->bindParam(':email_usuario', $dados['email_usuario'], PDO::PARAM_STR);
                $edit_usuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                if($edit_usuario->execute()){
                    $_SESSION['msg'] = "<p style='color: green;'>Usuário editado com sucesso!</p>";
                    header("Location: registro_usuario.php");
                }else{
                    echo "<p style='color: #f00;'>Erro: Usuário não editado com sucesso!</p>";
                }
            }
        }
        ?>

        <form id="edit-usuario" method="POST" action="">
            <label>Nome: </label>
            <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Nome completo" value="<?php
            if (isset($dados['nome_usuario'])) {
                echo $dados['nome_usuario'];
            } elseif (isset($row_usuario['nome_usuario'])) {
                echo $row_usuario['nome_usuario'];
            }
            ?>" ><br><br>

            <label>E-mail: </label>
            <input type="email" name="email_usuario" id="email_usuario" placeholder="Melhor e-mail" value="<?php
                   if (isset($dados['email_usuario'])) {
                       echo $dados['email_usuario'];
                   } elseif (isset($row_usuario['email_usuario'])) {
                       echo $row_usuario['email_usuario'];
                   }
                   ?>" ><br><br>

            <input type="submit" value="Salvar" name="EditUsuario">
        </form>
    </body>
</html>
