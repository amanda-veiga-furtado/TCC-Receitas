<?php
    include_once '..\menu.php'; 
    include_once '..\conexao.php';

    session_start(); // Iniciar a sessão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Avaliação com estrela</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="../css/style.css">
        
</head>
<body>
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
        <form method="POST" action="processa.php">

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
                <textarea name="mensagem" rows="4" cols="65" placeholder=" Digite o seu comentário..."></textarea><br><br>

                <!-- Botão para enviar os dados do formulário -->
                <input type="submit" value="Enviar" class="botao"><br><br>

            </div>

        </form>
        <!-- Fim do formulário -->
    </div>

</body>
</html>