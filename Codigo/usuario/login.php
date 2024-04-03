<?php
    session_start();
    ob_start();

    include_once '..\menu.php'; 
    include_once '..\conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login</title>

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
        <!-- //Exemplo criptografar a senha -->
        <!-- //echo password_hash(123456, PASSWORD_DEFAULT); -->

        <h1>LOGAR</h1>

        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                //var_dump($dados);

            if (!empty($dados['SendLogin'])) {
                    //var_dump($dados);
                $query_usuario = "SELECT id_usuario, nome_usuario, email_usuario, senha_usuario 
                                    FROM usuario
                                    WHERE email_usuario = :email_usuario
                                    LIMIT 1";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':email_usuario', $dados['email_usuario'], PDO::PARAM_STR);
                $result_usuario->execute();
                    
                if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
                $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                    //var_dump($row_usuario);
                if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                    echo "usuario logado";
                        $_SESSION['id_usuario'] = $row_usuario['id_usuario'];
                        $_SESSION['nome_usuario'] = $row_usuario['nome_usuario'];
                        header("Location: dashboard.php");
                    }else{
                        $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Senha inválida!</p>";
                    }
                }else{
                    $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Usuário inválido!</p>";
                }
            }

            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>
        <form method="POST" action="">
        <label>Email: </label>
        <input  type="email" name="email_usuario" placeholder="Em@il" value="<?php if(isset($dados['email_usuario'])){ echo $dados['email_usuario']; } ?>"><br><br>
        
        <label>Senha: </label>
        <input  type="password" name="senha_usuario" placeholder="*****" value="<?php if(isset($dados['senha_usuario'])){ echo $dados['senha_usuario']; } ?>"><br><br>
        
        <input type="submit" value="Acessar" name="SendLogin" class="botao">

        </form>
        <a href="recuperar_senha/recuperar_senha.php"><br>Esqueceu a senha?</a>

    </div>

</body>
</html>