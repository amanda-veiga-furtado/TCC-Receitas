<?php
session_start();
include_once './conexao.php';

$id_usuario = filter_input(INPUT_GET, "id_usuario", FILTER_SANITIZE_NUMBER_INT);

if (empty($id_usuario)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
    header("Location: registro_cadastrar.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visualizar Usuário</title>
</head>
<body>
    <a href="cadastrar.php">Cadastrar</a><br>
    <a href="registro_cadastrar.php">Listar</a><br>

    <h1>Visualizar Usuário</h1>

    <?php
    $query_usuario = "SELECT id_usuario, nome_usuario, email_usuario FROM usuario WHERE id_usuario = :id_usuario LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $result_usuario->execute();

    if (($result_usuario) && ($result_usuario->rowCount() != 0)) {
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        extract($row_usuario);
        echo "ID: $id_usuario <br>";
        echo "Nome: $nome_usuario <br>";
        echo "E-mail: $email_usuario <br>";
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
        header("Location: registro_cadastrar.php");
        exit();
    }
    ?>
</body>
</html>

