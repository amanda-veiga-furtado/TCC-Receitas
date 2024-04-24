<?php
    include_once '../menu.php'; 
    include_once '../conexao.php'; 

    //Função Referente a quantidadePorcao --------------------------------------------------------------------------------
    
    function converteFracao($numero) {
        // Verifica se o número é quebrado (terminado em .5)
        if ($numero != floor($numero)) {
            $partes = explode('.', $numero); // Divide o número em partes
            $inteiro = $partes[0]; // Parte inteira
            $decimal = $partes[1]; // Parte decimal
            // Verifica se a parte decimal é 5
            if ($decimal == 5) {
                return $inteiro . ' 1/2'; // Retorna como fração 1/2
            }
        }
        return $numero; // Retorna o número normal se não for quebrado
    }

    //--------------------------------------------------------------------------------------------------------------------

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); // Recebe os dados do formulário

        $tempo_preparo = "";

        if (!empty($dados['CadReceita'])) {
            // Concatenação da quantidade de porções com o tipo de porção
            $numero_porcoes = converteFracao($dados['quantidadePorcao']) . ' ' . $dados['tipoPorcao'];

            if (!empty($dados['horas']) || !empty($dados['minutos'])) {
                // Combinar os valores dos inputs de horas e minutos em uma única string formatada                    
                $horas_texto = ($dados['horas'] == 1) ? 'Hora' : 'Horas';
                $minutos_texto = ($dados['minutos'] == 1) ? 'Minuto' : 'Minutos';
                $tempo_preparo = ($dados['horas'] == 0 ? '' : $dados['horas'] . " $horas_texto") . ($dados['horas'] == 0 || $dados['minutos'] == 0 ? '' : ' e ') . ($dados['minutos'] == 0 ? '' : $dados['minutos'] . " $minutos_texto");
                $tempo_preparo = str_replace(':', ' e ', $tempo_preparo); // Substituir ":" por "e"
            }

            // Verifica se foi feito upload de uma imagem
            if (isset($_FILES['imagem_receita']) && $_FILES['imagem_receita']['error'] === UPLOAD_ERR_OK) {
                $imagem_temp = $_FILES['imagem_receita']['tmp_name'];
                $nome_imagem = $_FILES['imagem_receita']['name'];
                $caminho_imagem = '../css/img/receita/' . $nome_imagem;

                // Move o arquivo temporário para o diretório desejado
                move_uploaded_file($imagem_temp, $caminho_imagem);
            } else {
                // Se nenhum upload foi feito, defina o caminho da imagem como vazio
                $caminho_imagem = '';
            }

            // Insere os dados no banco de dados
            $query_receita = "INSERT INTO receita (nome_receita, numeroPorcoes_receita, modoPreparo_receita, tempoPreparo_receita, imagem_receita) VALUES ('" . $dados['nome_receita'] . "','" . $numero_porcoes . "','" . $dados['modoPreparo_receita'] . "','" . $tempo_preparo . "', '" . $caminho_imagem . "')";

            $cad_receita = $conn->prepare($query_receita);
            $cad_receita->execute();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receita</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="card">
        <h1>COMPARTILHE SUA RECEITA</h1>

        <form name="cad-receita" method="POST" action="" enctype="multipart/form-data">

            <label>Nome da Receita: </label>
            <input type="text" name="nome_receita" id="nome_receita" placeholder="Bolo de Cenoura com Cobertura de Chocolate Amargo"><br><br>

            <label>Porção:</label>  
            <input type="number" name="quantidadePorcao" id="quantidadePorcao" min="1" step="0.5" value="1">

            <select name="tipoPorcao" id="tipoPorcao">
                <option value="porção(ões)">porção(ões)</option>
                <option value="pedaço(s)">pedaço(s)</option>
                <option value="prato(s)">prato(s)</option>
                <option value="fatia(s)">fatia(s)</option>
                <option value="pessoa(s)">pessoa(s)</option>
            </select><br><br>
            
            <label>Tempo Total de Preparo:<br><br></label>
            <input type="number" name="horas" id="horas" min="0" value="0"> Hora(s)  :  
            <input type="number" name="minutos" id="minutos" min="0" value="0"> Minuto(s)<br><br>

            <label>Imagem da Receita:<br><br></label>
            <input type="file" name="imagem_receita" id="imagem_receita"><br><br>
            
            <label>Ingrediente:<br><br></label>
            <br><br>
        
            <!-- Modo Preparo -->
            <?php
                // Carrega o conteúdo do arquivo de texto
                $placeholder_text = file_get_contents('receita.txt');
            ?>
            <label>Modo de Preparo:<br><br></label>
            <textarea name="modoPreparo_receita" id="modoPreparo_receita" placeholder="<?php echo $placeholder_text; ?> "rows="20" cols="65"></textarea>
            <br><br>
            
            <input type="submit" class="botao" value="Enviar" name="CadReceita">

        </form>
    </div>
</body>
</html>

