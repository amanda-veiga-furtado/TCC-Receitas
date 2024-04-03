<?php
    session_start();
    ob_start();

    include_once '..\menu.php'; 
    include_once '..\conexao.php';

    if((!isset($_SESSION['id_usuario'])) AND (!isset($_SESSION['nome_usuario']))){
        $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Necessário realizar o login para acessar a página!<br><br></p>";
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">

    <!-- <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="card">
        <h1>
            Bem vindo(a),
            <?php 
                echo $_SESSION['nome_usuario'];
            ?>
            !
        </h1>

        <a href="sair.php">Sair</a>
    </div>
</body>

</html>