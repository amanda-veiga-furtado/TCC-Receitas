<?php
    session_start();
    ob_start();
    include_once 'conexao.php';

    if((!isset($_SESSION['id_usuario'])) AND (!isset($_SESSION['nome_usuario']))){
        $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Necessário realizar o login para acessar a página!</p>";
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <!-- <link rel="shortcut icon" href="images/favicon.ico" type="image/x-ico"> -->
    <title>Dashboard</title>
</head>

<body>
    <h1>Bem vindo
        <?php echo $_SESSION['nome_usuario']; ?>!
    </h1>

    <a href="sair.php">Sair</a>

</body>

</html>