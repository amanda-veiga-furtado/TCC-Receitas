<?php
    session_start(); // Iniciar a sessão

    include_once '..\conexao.php';

// Definir fuso horário de São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Acessar o IF quando é selecionado ao menos uma estrela
    if (!empty($_POST['estrela'])) {

    // Receber os dados do formulário
    $estrela = filter_input(INPUT_POST, 'estrela', FILTER_DEFAULT);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_DEFAULT);

    // Criar a QUERY cadastrar no banco de dados
    $query_avaliacao = "INSERT INTO comentario (id_comentario, qtd_estrelas, texto_comentario,data_comentario ) VALUES (:id_comentario, :qtd_estrelas, :texto_comentario, :data_comentario)";

    // Preparar a QUERY
    $cad_avaliacao = $conn->prepare($query_avaliacao);

    // Substituir os links pelo valor
    $cad_avaliacao->bindParam(':qtd_estrelas', $estrela, PDO::PARAM_INT);
    $cad_avaliacao->bindParam(':texto_comentario', $texto_comentario, PDO::PARAM_STR);
    $data_comentario = date("Y-m-d H:i:s");
    $cad_avaliacao->bindParam(':data_comentario', $data_comentario);
    
    // Acessa o IF quando cadastrar corretamente
    if ($cad_avaliacao->execute()) {

        // Criar a mensagem de erro
        $_SESSION['msg'] = "<p style='color: green;'>Avaliação enviada com sucesso.</p>";

        // Redirecionar o usuário para a página inicial
        header("Location: index.php");
    } else {

        // Criar a mensagem de erro
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Avaliação não enviada.</p>";

        // Redirecionar o usuário para a página inicial
        header("Location: index.php");
    }
} else {
    // Criar a mensagem de erro
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário selecionar pelo menos 1 estrela.</p>";

    // Redirecionar o usuário para a página inicial
    header("Location: index.php");

    // header("Location: registro_receita.php?id_receita=$id_receita"); 
    //vai para listagemreceita

}
