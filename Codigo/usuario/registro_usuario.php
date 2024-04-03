<?php
    session_start();

    include_once '..\menu.php'; 
    include_once '..\conexao.php';

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
    <title>Visualizar Usuário</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

</head>
<body>
    <div class="card">
        <br><br>

        <h1>VISUALIZAR USUÁRIO</h1>

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
    </div>
</body>
</html>

