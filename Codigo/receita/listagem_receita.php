<?php
    include_once '..\menu.php'; 
    include_once '..\conexao.php';

    session_start();
    ob_start();
?>

<!DOCTYPE html>
    <html lang="pt-br">
    <head>

        <title>Lista de Receitas</title>

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
        <h1>LISTA DE RECEITAS</h1>
      
        <?php

            //paginação
                // //receber numero da pagina
                // //http://localhost/TCC/listagem_receita.php?page=1

                $pagina_atual = filter_input(INPUT_GET,"page", FILTER_SANITIZE_NUMBER_INT);
                    // var_dump($pagina_atual);

                    //caso não se fornesa o numero pagina=1
                    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
                        //var_dump($pagina);
                    // setar quantidade de registro por pagina
                    $limite_resultado = 2;

                    // calcular o incio visualização
                    $inicio = ($limite_resultado * $pagina) - $limite_resultado;

            $query_receita = "SELECT id_receita, nome_receita, imagem_receita FROM receita LIMIT $inicio, $limite_resultado";

            $result_receita = $conn->prepare($query_receita);

            $result_receita->execute();

            if (($result_receita) AND ($result_receita->rowCount() != 0)) {
                while($row_receita = $result_receita->fetch(PDO::FETCH_ASSOC)) {
                    // Extrai os valores do array associativo
                    extract($row_receita);

                    echo "<div>";
                    // echo "<h3>ID: $id_receita</h3>";
                    echo "<p>$nome_receita<br><br></p>";
                    
                    // Verifica se há uma imagem
                    if (!empty($imagem_receita)) {
                        // Exibe a imagem usando a tag <img> e o atributo src com o conteúdo da imagem
                        echo "<img src='$imagem_receita' alt='Imagem da Receita' class='lista-receita-imagem'><br><br><br>";
                    } else {
                        // Se não houver imagem, exibe uma mensagem
                        echo "<p style='color: #f00;'>Nenhuma imagem disponível</p><br><br>";
                    }

                    echo "<a href='registro_receita.php?id_receita=$id_receita' class=botao>Visualizar<br></a>";

                    // <a href="registro_cadastrar.php" class="botao">Voltar</a>

                    echo "<br><br><hr><br>";
                    echo "</div>";

                }
            } else {
                echo "<p style='color: #f00;'>Erro: Nenhuma receita encontrada!<br><br></p>";
            }
            //contar quantidade de registros
            $query_qnt_registros = "SELECT COUNT(id_receita) AS num_result FROM receita";
            $result_qnt_registros=$conn->prepare($query_qnt_registros);
            $result_qnt_registros->execute();
            $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

            //quantidade de paginas
            $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

            $maximo_link = 2;

            echo "<a href='listagem_receita.php?page=1'>Primeira</a> "; 

           //for(inicio;condição;incremento)
           for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
            if ($pagina_anterior >= 1) {
                echo "<a href='listagem_receita.php?page=$pagina_anterior'>$pagina_anterior</a> ";
                }
            }

            echo "<a href='#'>$pagina</a> ";



                for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
                    if ($proxima_pagina <= $qnt_pagina) {
                        echo "<a href='listagem_receita.php?page=$proxima_pagina'>$proxima_pagina</a> ";
                    }}

                    echo "<a href='listagem_receita.php?page=$qnt_pagina'>Última</a>";
        ?>
        </div>
    </body>
</html>
