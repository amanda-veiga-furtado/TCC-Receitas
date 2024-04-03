<?php
    session_start();
    ob_start();
    include_once '../../menu.php';
    include_once '../../conexao.php';
    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\SMTP;
    // use PHPMailer\PHPMailer\Exception;

    // require '../../lib/vendor/autoload.php';
    // $mail = new PHPMailer(true);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Recuperar Senha</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="card">
        <h1>RECUPERAR SENHA</h1>

        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                //var_dump($dados);

            if (!empty($dados['SendRecupSenha'])) {
                // var_dump($dados);
            
                $query_usuario = "SELECT id_usuario, nome_usuario, email_usuario 
                                  FROM usuario 
                                  WHERE email_usuario = :email_usuario  
                                  LIMIT 1";
                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':email_usuario', $dados['email_usuario'], PDO::PARAM_STR);
                $result_usuario->execute();
                
                if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
                    //echo "Enviar Email";
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                    $chave_recuperar_senha = password_hash($row_usuario['id_usuario'], PASSWORD_DEFAULT);
                    // echo "Chave: $chave_recuperar_senha <br><br>";

                    $query_up_usuario = "UPDATE usuario 
                                         SET recuperar_senha = :recuperar_senha 
                                         WHERE id_usuario = :id_usuario 
                                         LIMIT 1";
                    $result_up_usuario = $conn->prepare($query_up_usuario);
                    $result_up_usuario->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
                    $result_up_usuario->bindParam(':id_usuario', $row_usuario['id_usuario'], PDO::PARAM_INT);

                    if ($result_up_usuario->execute()) {
                        // $link = "http://localhost/TCC/tcc_receitas/usuario/recuperar_senha/atualizar_senha.php?chave=$chave_recuperar_senha<br><br>";
                        // echo "http://localhost/TCC/tcc_receitas/usuario/recuperar_senha/atualizar_senha.php?chave=$chave_recuperar_senha<br><br>";
                        header("Location: atualizar_senha.php?chave=$chave_recuperar_senha");


                        
                        // try {
                        //     $mail->CharSet    = 'UTF-8';
                        //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        //     $mail->isSMTP();
                        //     $mail->Host       = 'sandbox.smtp.mailtrap.io';
                        //     $mail->SMTPAuth   = true;
                        //     $mail->Username   = 'a45348d791702a';
                        //     $mail->Password   = '********a186';
                        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        //     $mail->Port       = 2525;

                        //     $mail->setFrom('atendimento@teste.com', 'Atendimento');
                        //     $mail->addAddress($row_usuario['email_usuario'], $row_usuario['nome_usuario']);

                        //     $mail->isHTML(true);
                        //     $mail->Subject = 'Recuperar senha';
                        //     $mail->Body    = 'Prezado(a) ' . $row_usuario['nome_usuario'] .".<br><br>Você solicitou alteração de senha.<br><br>Para continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br><a href='" . $link . "'>" . $link . "</a><br><br>Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
                        //     $mail->AltBody = 'Prezado(a) ' . $row_usuario['nome_usuario'] ."\n\nVocê solicitou alteração de senha.\n\nPara continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.\n\n";

                        //     $mail->send();

                        //     $_SESSION['msg'] = "<p style='color: green'>Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuperar a senha!</p>";
                        //         header("Location: ../login.php");

                        // } catch (Exception $e) {
                        //     echo "Erro: E-mail não enviado. Mailer Error: {$mail->ErrorInfo}";
                        // }

                    } else {
                        echo  "<p style='color: #ff0000'>Erro: Tente novamente!</p>";
                    }
                } else {
                    echo "<p style='color: #ff0000'>Erro: Usuário não encontrado!<br><br></p>";
                }

            }

            if (isset($_SESSION['msg'])) {  // if (isset($_SESSION['msg_rec'])) {
                echo $_SESSION['msg'];          // echo $_SESSION['msg_rec'];
                unset($_SESSION['msg']);        // unset($_SESSION['msg_rec']);
            }
        ?>

        <form method="POST" action="">
            <?php
                $email_usuario = "";
                if (isset($dados['email_usuario'])) {
                    $email_usuario = $dados['email_usuario'];
                } 
            ?>

            <label>E-mail: </label>

            <input type="email" name="email_usuario" placeholder="Em@il: " value="
                <?php
                    echo $email_usuario;
                ?>
            "><br><br>

            <input type="submit" value="Recuperar" name="SendRecupSenha" class="botao">

        </form>

        <br>Lembrou? <a href="../login.php">clique aqui</a> para logar

    </div>
</body>

</html>
