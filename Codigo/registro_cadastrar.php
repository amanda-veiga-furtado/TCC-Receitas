<?php
include_once './conexao.php'; // Inclui o arquivo de conexão com o banco de dados.
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de Cadastrados</title>
    </head>
    <body>
        <a href="cadastrar.php">Cadastrar</a><br>
        <a href="registro_cadastrar.php">Listar</a><br>

        <h1>Lista de Cadastrados</h1>

        <?php

            //paginação
            //receber numero da pagina
            //http://localhost/TCC/registro_cadastrar.php?page=1

            $pagina_atual = filter_input(INPUT_GET,"page", FILTER_SANITIZE_NUMBER_INT);
            //var_dump($pagina_atual);

            //caso não se fornesa o numero pagina=1
            $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
            //var_dump($pagina);

            //setar quantidade de registro por pagina
            //$limite_resultado = 40;
            $limite_resultado = 2;

            //calcular o incio visualização
            $inicio = ($limite_resultado * $pagina) - $limite_resultado;

            $query_usuario = "SELECT id_usuario, nome_usuario, email_usuario FROM usuario LIMIT $inicio, $limite_resultado";
            $result_usuario = $conn->prepare($query_usuario);
            $result_usuario->execute();

            if (($result_usuario) AND ($result_usuario->rowCount() != 0)) {
                
                while($row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC)) {
                    //var_dump($row_usuario);
                    extract($row_usuario);
                        echo "ID: $id_usuario <br>";
                        //sem extract()
                        //echo "ID: " . $row_usuario ['id_usuario'] . "<br>";
                        echo "Nome: $nome_usuario <br>";
                        echo "Email: $email_usuario <br><br>";
                        echo "<a href='registro_usuario.php?id_usuario=$id_usuario'>Visualizar</a><br> ";
                        echo "<a href='editar_usuario.php?id_usuario=$id_usuario'>Editar</a><br> ";
                        echo "<a href='deletar_usuario.php?id_usuario=$id_usuario'>Deletar</a><br> ";

                        echo "<hr>"; //linha divisoria
                }

                //Contar a quantidade de registros no BD
                $query_qnt_registros = "SELECT COUNT(id_usuario) AS num_result FROM usuario";
                $result_qnt_registros = $conn ->prepare($query_qnt_registros);
                $result_qnt_registros->execute();
                $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

                //quantidade de paginas
                $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

                $maximo_link = 2;

                echo "<a href='registro_cadastrar.php?page=1'>Primeira</a> ";

                //for(inicio;condição;incremento)
                for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
                   if ($pagina_anterior >= 1) {
                        echo "<a href='registro_cadastrar.php?page=$pagina_anterior'>$pagina_anterior</a> ";
                    }
                }

                echo "<a href='#'>$pagina</a> ";

                for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
                    if ($proxima_pagina <= $qnt_pagina) {
                        echo "<a href='registro_cadastrar.php?page=$proxima_pagina'>$proxima_pagina</a> ";
                    }
                }

                echo "<a href='registro_cadastrar.php?page=$qnt_pagina'>Ultima</a> ";

            } else {
                echo "<p style='color: #f00;'>Erro: Nenhum usuario encontrado!</p>";
            }
        ?>
    </body>
</html>
