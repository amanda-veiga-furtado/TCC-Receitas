<?php
    include_once '..\menu.php'; 
    include_once '..\conexao.php';

    session_start();
    ob_start();

    $id_receita = filter_input(INPUT_GET, "id_receita", FILTER_SANITIZE_NUMBER_INT);

    if (empty($id_receita)) {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Receita não encontrada!<br><br></p>";
        header("Location: listagem_receita.php");
        exit();
    }

    $query_receita = "SELECT nome_receita, numeroPorcoes_receita, modoPreparo_receita, tempoPreparo_receita, imagem_receita FROM receita WHERE id_receita=$id_receita LIMIT 1";

    $result_receita = $conn->prepare($query_receita);
    $result_receita->execute();

    $row_receita = [];

    if ($result_receita && $result_receita->rowCount() != 0) {
        $row_receita = $result_receita->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Receita não encontrada!<br><br></p>";
        header("Location: listagem_receita.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Receita</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
        // Define o caminho da imagem padrão
        $imagem_padrao = "../css/img/receita/imagem.png";

        // Verifica se há uma imagem no banco de dados
        if (!empty($row_receita['imagem_receita'])) {
            // Se houver, exibe a imagem do banco de dados
            echo "<div class='banner' style='background-image: url(\"{$row_receita['imagem_receita']}\");'></div>";
        } else {
            // Se não houver, exibe a imagem padrão
            echo "<div class='banner' style='background-image: url(\"$imagem_padrao\");'></div>";
        }
    ?>

    <div class="conteudo">
        <?php
            if (!empty($row_receita)) {
                extract($row_receita);
                echo "<h1 class='titulo-receita'>$nome_receita</h1>";         

                echo "<div class='card'>";
                echo "<h1>Porção</h1>$numeroPorcoes_receita<br>";
                echo "</div>";

                echo "<div class='card'>";
                echo "<h1>Tempo de Preparo</h1>$tempoPreparo_receita<br>";
                echo "</div>";

                echo "<div class='card'>";
                echo "<h1>Ingredientes</h1><br><br><br><br<br><br>";
                echo "</div>";

                echo "<div class='card'>";
                echo "<h1>Modo de Preparo</h1>$modoPreparo_receita<br><br>";
                echo "</div>";

                // echo "<div class='card'>";
                    echo "<div class='container'>";

                        echo "<div class='box'>";
                            echo "<a href='editar_receita.php?id_receita=$id_receita' class='botao'>Editar</a>";
                        echo "</div>";

                        echo "<div class='box'>";
                            echo "<a href='deletar_receita.php?id_receita=$id_receita' class='botao'>Deletar</a>";
                        echo "</div>";

                        echo "<div class='box'>";
                            echo "<a href='listagem_receita.php' class='botao'>Voltar</a>";
                        echo "</div><br><br>";
                    
                        
                    echo "</div>"; // fecha container
                // echo "</div>"; // fecha card

            }
        ?>
    </div>
    <?php
        // Termina a sessão após definir a mensagem de erro
        session_unset();
        session_destroy();
    ?>

<!----------------------------------------------------------------------------------------------------------------------------------->

<div class="card">

    <h1>AVALIE A RECEITA</h1>

    <?php
        // Imprimir a mensagem de erro ou sucesso salvo na sessão
        if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>

    <!-- Inicio do formulário -->
    <form method="POST" action="processa_avaliacao.php">

        <div class="estrelas">

            <!-- Carrega o formulário definindo nenhuma estrela selecionada -->
            <input type="radio" name="estrela" id="vazio" value="" checked>

            <!-- Opção para selecionar 1 estrela -->
            <label for="estrela_um"><i class="opcao fa"></i></label>
            <input type="radio" name="estrela" id="estrela_um" id="vazio" value="1">

            <!-- Opção para selecionar 2 estrela -->
            <label for="estrela_dois"><i class="opcao fa"></i></label>
            <input type="radio" name="estrela" id="estrela_dois" id="vazio" value="2">

            <!-- Opção para selecionar 3 estrela -->
            <label for="estrela_tres"><i class="opcao fa"></i></label>
            <input type="radio" name="estrela" id="estrela_tres" id="vazio" value="3">

            <!-- Opção para selecionar 4 estrela -->
            <label for="estrela_quatro"><i class="opcao fa"></i></label>
            <input type="radio" name="estrela" id="estrela_quatro" id="vazio" value="4">

            <!-- Opção para selecionar 5 estrela -->
            <label for="estrela_cinco"><i class="opcao fa"></i></label>
            <input type="radio" name="estrela" id="estrela_cinco" id="vazio" value="5"><br><br>

            <!-- Campo para enviar a mensagem -->
            <textarea name="mensagem" rows="6" cols="65" placeholder=" Digite o seu comentário..."></textarea><br><br>

            <!-- Botão para enviar os dados do formulário -->
            <input type="submit" id="" value="Enviar" class="botao"><br><br>

        </div>

    </form>
    <!-- Fim do formulário -->
    </div>


    </body>
    </html>
