<?php
    include_once '..\menu.php'; 
    include_once '..\conexao.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastrar</title>

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
                <!-- <br><br>         -->
                <h1>CADASTRAR</h1>

                <?php
                    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); //Este trecho PHP recebe os dados do formulário enviado via método POST e os armazena em um array chamado $dados. Os dados são filtrados usando o filtro padrão FILTER_DEFAULT.

                    if (!empty($dados['CadUsuario'])) { //Esta condição verifica se o botão de submissão do formulário foi acionado. Se o botão chamado 'CadUsuario' não estiver vazio, significa que foi acionado.
                        //var_dump($dados);
                        $empty_input = false; // Inicializa uma variável para verificar se há campos vazios.
                        $dados = array_map('trim', $dados); //Remove espaços em branco no início e no final de cada valor do array.

                        if (in_array("", $dados)) { // Verifica se há algum campo vazio no array.
                            $empty_input = true; // Define a flag para indicar que há campos vazios.
                            echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!<br><br></p>";
                        } elseif (!filter_var($dados['email_usuario'], FILTER_VALIDATE_EMAIL)) { // Verifica se o e-mail fornecido é válido.
                            $empty_input = true;  // Define a flag para indicar que há campos vazios.
                                echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!<br><br></p>";
                        }

                        if (!$empty_input) { // Se não houver campos vazios ou e-mails inválidos.
                            $query_usuario = "INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario) VALUES (:nome_usuario, :email_usuario, :senha_usuario) "; // Query SQL para inserir os dados do usuário no banco de dados.

                            $senha_hash = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);// Criptografa a senha usando password_hash()

                            $cad_usuario = $conn->prepare($query_usuario); // Prepara a query para execução.
                            $cad_usuario->bindParam(':nome_usuario', $dados['nome_usuario'], PDO::PARAM_STR); // Associa os parâmetros da query com os valores do array.
                            $cad_usuario->bindParam(':email_usuario', $dados['email_usuario'], PDO::PARAM_STR);// Associa os parâmetros da query com os valores do array.
                            $cad_usuario->bindParam(':senha_usuario', $senha_hash, PDO::PARAM_STR);// Associa os parâmetros da query com os valores do array.
                            $cad_usuario->execute(); // Executa a query.
                            if ($cad_usuario->rowCount()) { // Verifica se a inserção foi bem-sucedida
                                echo "<p style='color: green;'>Usuário cadastrado com sucesso!<br><br></p>";
                                unset($dados); // Remove os dados do array após o cadastro.
                            } else {
                                echo "<p style='color: #f00;'>Erro: Usuário não cadastrado com sucesso!<br><br></p>";
                            }
                        }
                    }
                    ?>
                    <form name="cad-usuario" method="POST" action="">  <!-- Formulário para cadastrar um novo usuário, com método POST e ação vazia (mesma página). -->
                        <label>Nome: </label>
                        <input type="text" name="nome_usuario" id="nome_usuario" placeholder=" Nome completo" value="<?php
                        if (isset($dados['nome_usuario'])) { // Verifica se o campo 'nome_usuario' está definido no array.
                            echo $dados['nome_usuario']; // Exibe o valor do campo 'nome_usuario'.
                        }
                    ?>"><br><br>

                    <label>E-mail: </label>
                    <input type="email" name="email_usuario" id="email_usuario" placeholder=" Seu e-mail" value="<?php
                    if (isset($dados['email_usuario'])) { // Verifica se o campo 'email_usuario' está definido no array.
                        echo $dados['email_usuario']; // Exibe o valor do campo 'email_usuario'
                    }
                    ?>"><br><br>

                    <label>Senha: </label>
                    <input type="password" name="senha_usuario" id="senha_usuario" placeholder=" Senha" value="<?php
                    if (isset($dados['senha_usuario'])) { // Verifica se o campo 'senha_usuario' está definido no array.
                        echo $dados['senha_usuario']; // Exibe o valor do campo 'senha_usuario'
                    }
                    ?>">
                    <input type="submit" value="Cadastrar" name="CadUsuario" class="botao"> <!-- Botão de submissão do formulário. -->
                </form>
            </div>
    </body>
</html>
