<?php
include_once '../conexao.php';

echo "<div class='card'>";
    echo "<h3>Ingredientes Selecionados</h3><br>";
    $selectedIngredients = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cartItems'])) {
        $cartItems = json_decode($_POST['cartItems'], true);
        foreach ($cartItems as $item) {
            $itemName = htmlspecialchars($item['name']);
            $itemId = htmlspecialchars($item['id']);
            echo "- $itemName (ID: $itemId) <br><br>";
            $selectedIngredients[] = $itemId;
        }
    }
echo "</div>";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receitas Compatíveis</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .lista-receita-imagem {
            height: 100px;
        }
    </style>
</head>
<body>
    <?php

    $query_ingrediente = "SELECT fk_id_receita, fk_id_ingrediente FROM lista_de_ingredientes";

    $result_ingrediente = $conn->prepare($query_ingrediente);
    $result_ingrediente->execute();

    $query_receita = "SELECT id_receita, nome_receita, imagem_receita FROM receita";

    $result_receita = $conn->prepare($query_receita);
    $result_receita->execute();

    // echo "<hr>";
    // echo "<h3>lista_de_ingrediente em array</h3>";

    $query_lista_ingrediente = "SELECT fk_id_receita, fk_id_ingrediente FROM lista_de_ingredientes";
    $result_lista_ingrediente = $conn->prepare($query_lista_ingrediente);
    $result_lista_ingrediente->execute();

    $receitasIngredientes = [];

    if (($result_lista_ingrediente) && ($result_lista_ingrediente->rowCount() != 0)) {
        while ($row_lista_ingrediente = $result_lista_ingrediente->fetch(PDO::FETCH_ASSOC)) {
            $idReceita = $row_lista_ingrediente['fk_id_receita'];
            $idIngrediente = $row_lista_ingrediente['fk_id_ingrediente'];

            if (!isset($receitasIngredientes[$idReceita])) {
                $receitasIngredientes[$idReceita] = [];
            }
            $receitasIngredientes[$idReceita][] = $idIngrediente;
        }

        // echo "<pre>";
        // print_r($receitasIngredientes);
        // echo "</pre>";

        echo "<div class='card'>";
            echo "<h3>Receitas que correspondem ao carrinho</h3><br>";

            $matchingReceitas = [];

            foreach ($receitasIngredientes as $idReceita => $ingredientes) {
                $difference = array_diff($ingredientes, $selectedIngredients);
                if (empty($difference)) {
                    $matchingReceitas[] = $idReceita;
                }
            }

            if (!empty($matchingReceitas)) {
                $query_matching_receitas = "SELECT id_receita, nome_receita, imagem_receita FROM receita WHERE id_receita IN (" . implode(',', $matchingReceitas) . ")";
                $result_matching_receitas = $conn->prepare($query_matching_receitas);
                $result_matching_receitas->execute();

                if (($result_matching_receitas) && ($result_matching_receitas->rowCount() != 0)) {
                    while ($row_matching_receita = $result_matching_receitas->fetch(PDO::FETCH_ASSOC)) {
                        $idReceita = $row_matching_receita['id_receita'];
                        $nomeReceita = htmlspecialchars($row_matching_receita['nome_receita']);
                        $imagemReceita = htmlspecialchars($row_matching_receita['imagem_receita']);
                        $linkReceita = "registro_receita.php?id_receita=" . $idReceita;

                        echo "<div>";
                        echo "<h3>$nomeReceita</h3><br>";

                        // Exibir os ingredientes da receita correspondente
                        echo "<h4>Ingredientes:</h4><br>";
                        $query_ingredientes_receita = "SELECT ingrediente.nome_ingrediente 
                                                       FROM lista_de_ingredientes 
                                                       JOIN ingrediente ON lista_de_ingredientes.fk_id_ingrediente = ingrediente.id_ingrediente 
                                                       WHERE lista_de_ingredientes.fk_id_receita = :id_receita";
                        $result_ingredientes_receita = $conn->prepare($query_ingredientes_receita);
                        $result_ingredientes_receita->bindParam(':id_receita', $idReceita, PDO::PARAM_INT);
                        $result_ingredientes_receita->execute();

                        if (($result_ingredientes_receita) && ($result_ingredientes_receita->rowCount() != 0)) {
                            while ($row_ingrediente_receita = $result_ingredientes_receita->fetch(PDO::FETCH_ASSOC)) {
                                echo "- " . htmlspecialchars($row_ingrediente_receita['nome_ingrediente']) . "<br><br>";
                            }
                        } else {
                            echo "<p style='color: #f00;'>Nenhum ingrediente encontrado para esta receita</p>";
                        }

                        if (!empty($imagemReceita)) {
                            echo "<br><img src='$imagemReceita' alt='Imagem da Receita' class='lista-receita-imagem'><br>";
                        } else {
                            echo "<p style='color: #f00;'>Nenhuma imagem disponível</p>";
                        }

                        echo "<a href='$linkReceita' class='botao'>Ver Receita</a>";
                        echo "</div><br>";
                    }
                } else {
                    echo "<p style='color: #f00;'>Erro: Nenhuma receita encontrada!<br><br></p>";
                }
            } else {
                echo "<p style='color: #f00;'>Nenhuma receita corresponde aos ingredientes selecionados!<br><br></p>";
            }
        } else {
            echo "<p style='color: #f00;'>Erro: Nada encontrado!<br><br></p>";
        }
    echo "</div>";

    ?>
</body>
</html>
