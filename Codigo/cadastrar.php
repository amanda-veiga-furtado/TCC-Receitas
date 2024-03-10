<?php
include_once './conexao.php'; // Inclui o arquivo de conexão com o banco de dados.
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastrar</title>
    </head>
    <body>
        <a href="registro_cadastrar.php">Listar</a><br>
        <a href="cadastrar.php">Cadastrar</a><br>
        <h1>Cadastrar</h1>
        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); //// Filtra e armazena os dados recebidos via POST em um array.

        //Verificar se o usuário clicou no botão
        if (!empty($dados['CadUsuario'])) { //Verifica se o botão de cadastro foi acionado.
            //var_dump($dados);

            $empty_input = false; // Inicializa uma variável para verificar se há campos vazios.

            $dados = array_map('trim', $dados); //Remove espaços em branco no início e no final de cada valor do array.

            if (in_array("", $dados)) { // Verifica se há algum campo vazio no array.
                $empty_input = true; // Define a flag para indicar que há campos vazios.
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } elseif (!filter_var($dados['email_usuario'], FILTER_VALIDATE_EMAIL)) { // Verifica se o e-mail fornecido é válido.
                $empty_input = true;  // Define a flag para indicar que há campos vazios.
                    echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";
            }

            if (!$empty_input) { // Se não houver campos vazios ou e-mails inválidos.
                $query_usuario = "INSERT INTO usuario (nome_usuario, email_usuario) VALUES (:nome_usuario, :email_usuario) "; // Query SQL para inserir os dados do usuário no banco de dados.

                $cad_usuario = $conn->prepare($query_usuario); // Prepara a query para execução.
                $cad_usuario->bindParam(':nome_usuario', $dados['nome_usuario'], PDO::PARAM_STR); // Associa os parâmetros da query com os valores do array.
                $cad_usuario->bindParam(':email_usuario', $dados['email_usuario'], PDO::PARAM_STR);// Associa os parâmetros da query com os valores do array.
                $cad_usuario->execute(); // Executa a query.
                if ($cad_usuario->rowCount()) { // Verifica se a inserção foi bem-sucedida
                    echo "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
                    unset($dados); // Remove os dados do array após o cadastro.
                } else {
                    echo "<p style='color: #f00;'>Erro: Usuário não cadastrado com sucesso!</p>";
                }
            }
        }
        ?>
        <form name="cad-usuario" method="POST" action="">  <!-- Formulário para cadastrar um novo usuário, com método POST e ação vazia (mesma página). -->
            <label>Nome: </label>
            <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Nome completo" value="<?php
            if (isset($dados['nome_usuario'])) { // Verifica se o campo 'nome_usuario' está definido no array.
                echo $dados['nome_usuario']; // Exibe o valor do campo 'nome_usuario'.
            }
            ?>"><br><br>

            <label>E-mail: </label>
            <input type="email" name="email_usuario" id="email_usuario" placeholder="Seu melhor e-mail" value="<?php
            if (isset($dados['email_usuario'])) { // Verifica se o campo 'email_usuario' está definido no array.
                echo $dados['email_usuario']; // Exibe o valor do campo 'email_usuario'
            }
            ?>"><br><br>

            <input type="submit" value="Cadastrar" name="CadUsuario"> <!-- Botão de submissão do formulário. -->
        </form>
    </body>
</html>
