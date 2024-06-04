<?php
    session_start();
    ob_start();

    include_once '../menu.php';
    include_once '../conexao.php';

    // Função para converter frações
    function converteFracao($numero) {
        if ($numero != floor($numero)) {
            $partes = explode('.', $numero);
            $inteiro = $partes[0];
            $decimal = $partes[1];
            if ($decimal == 5) {
                return $inteiro . ' 1/2';
            }
        }
        return $numero;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT); // Recebe os dados do formulário

        //Concatenação

        $tempo_preparo = "";

        if (!empty($dados['CadReceita'])) {
            $numero_porcoes = converteFracao($dados['quantidadePorcao']) . ' ' . $dados['tipoPorcao'];

            if (!empty($dados['horas']) || !empty($dados['minutos'])) {
                $horas_texto = ($dados['horas'] == 1) ? 'Hora' : 'Horas';
                $minutos_texto = ($dados['minutos'] == 1) ? 'Minuto' : 'Minutos';
                $tempo_preparo = ($dados['horas'] == 0 ? '' : $dados['horas'] . " $horas_texto") . ($dados['horas'] == 0 || $dados['minutos'] == 0 ? '' : ' e ') . ($dados['minutos'] == 0 ? '' : $dados['minutos'] . " $minutos_texto");
                $tempo_preparo = str_replace(':', ' e ', $tempo_preparo);
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

            $query_receita = "INSERT INTO receita (nome_receita, numeroPorcoes_receita, modoPreparo_receita, tempoPreparo_receita, imagem_receita) VALUES (:nome_receita, :numeroPorcoes_receita, :modoPreparo_receita, :tempoPreparo_receita, :imagem_receita)";
            $cad_receita = $conn->prepare($query_receita);
            $cad_receita->bindParam(':nome_receita', $dados['nome_receita']);
            $cad_receita->bindParam(':numeroPorcoes_receita', $numero_porcoes);
            $cad_receita->bindParam(':modoPreparo_receita', $dados['modoPreparo_receita']);
            $cad_receita->bindParam(':tempoPreparo_receita', $tempo_preparo);
            $cad_receita->bindParam(':imagem_receita', $caminho_imagem);
            $cad_receita->execute();

            $id_receita = $conn->lastInsertId();

            // Inserção dos ingredientes na tabela lista_de_ingredientes

            for ($i = 0; $i < 5; $i++) {
                $nome_ingrediente = $dados['nome_ingrediente_' . $i];
                $qtdIngrediente_lista = converteFracao($dados['quantidadeIngrediente_' . $i]) . ' ' . $dados['tipoIngrediente_' . $i];

                // Obtém o ID do ingrediente selecionado
                $query_id_ingrediente = "SELECT id_ingrediente FROM ingrediente WHERE id_ingrediente = :nome_ingrediente";
                $stmt = $conn->prepare($query_id_ingrediente);
                $stmt->bindParam(':nome_ingrediente', $nome_ingrediente);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_ingrediente = $resultado['id_ingrediente'];

                // Insere os dados na tabela lista_de_ingredientes
                $query_ingredientes = "INSERT INTO lista_de_ingredientes (fk_id_receita, fk_id_ingrediente, qtdIngrediente_lista) VALUES (:fk_id_receita, :fk_id_ingrediente, :qtdIngrediente_lista)";
                $cad_ingredientes = $conn->prepare($query_ingredientes);
                $cad_ingredientes->bindParam(':fk_id_receita', $id_receita);
                $cad_ingredientes->bindParam(':fk_id_ingrediente', $id_ingrediente);
                $cad_ingredientes->bindParam(':qtdIngrediente_lista', $qtdIngrediente_lista);
                $cad_ingredientes->execute();
            }

            echo "<br><br>Receita e ingredientes salvos com sucesso!";
        }
    }
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Criar Receita</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <!-- Inclua Select2 CSS -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <!-- Inclua Select2 JS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <style>

        /* Estilos para os campos de entrada e select */
        .input-field, .select-field {
            width: 200px;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>COMPARTILHE SUA RECEITA</h1>
        <form name="cad-receita" method="POST" action="" enctype="multipart/form-data">

            <label>Nome da Receita: </label>
            <input type="text" name="nome_receita" id="nome_receita" placeholder="Bolo de Cenoura com Cobertura de Chocolate Amargo" class="input-field"><br><br>
            <label>Porção:</label>
            <input type="number" name="quantidadePorcao" id="quantidadePorcao" min="0.5" step="0.5" value="1" class="input-field" style="width: 50px;">
            <select name="tipoPorcao" id="tipoPorcao" class="select-field">
                <option value="porção(ões)">porção(ões)</option>
                <option value="pedaço(s)">pedaço(s)</option>
                <option value="prato(s)">prato(s)</option>
                <option value="fatia(s)">fatia(s)</option>
                <option value="pessoa(s)">pessoa(s)</option>
            </select><br><br>

            <label>Tempo Total de Preparo:<br><br></label>
            <input type="number" name="horas" id="horas" min="0" value="0" class="input-field" style="width: 50px;"> Hora(s) :
            <input type="number" name="minutos" id="minutos" min="0" value="0" class="input-field" style="width: 50px;"> Minuto(s)<br><br>

            <label>Imagem da Receita:<br><br></label>
            <input type="file" name="imagem_receita" id="imagem_receita"><br><br>

            <!-- Ingrediente: -->

            <!-- Nome do Ingrediente: -->

            <label>Ingredientes:</label><br><br>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <select name="nome_ingrediente_<?php echo $i; ?>" id="nome_ingrediente_<?php echo $i; ?>" class="select-field">
                    <option value="">Selecione um Ingrediente</option>
                    <?php
                    $query = $conn->query("SELECT id_ingrediente, nome_ingrediente FROM ingrediente ORDER BY nome_ingrediente ASC");
                    $registros = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($registros as $option):
                    ?>
                        <option value="<?php echo $option['id_ingrediente']; ?>"><?php echo $option['nome_ingrediente']; ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Quantidade do Ingrediente: -->

                <input class="input-field" type="number" name="quantidadeIngrediente_<?php echo $i; ?>" id="quantidadeIngrediente_<?php echo $i; ?>" min="0.5" step="0.5" value="1" style="width: 50px;">


                <select class="select-field" name="tipoIngrediente_<?php echo $i; ?>" id="tipoIngrediente_<?php echo $i; ?>">
                    <option value="colher(es) de café">colher de café</option>
                    <option value="colher(es) de chá">colher de chá</option>
                    <option value="colher(es) de sobremesa">colher de sobremesa</option>
                    <option value="colher(es) de sopa">colher de sopa</option>
                    <option value="copo(s) americano(s)">copo americano</option>
                    <option value="copo(s) requeijão">copo requeijão</option>
                    <option value="xícara(s) de chá">xícara de chá</option>
                    <option value="grama(s)">grama</option>
                    <option value="quilograma(s)">quilograma</option>
                    <option value="mililitro(s)">mililitro</option>
                    <option value="litro(s)">litro</option>
                    <option value="pedaço(s)">pedaço</option>
                    <option value="fatia(s)">fatia</option>
                    <option value="punhado(s)">punhado</option>
                    <option value="pitada(s)">pitada</option>
                    <option value="a gosto">a gosto</option>
                    <option value="pacote(s)">pacote</option>

                </select><br>
                <!-- 

Pitada(s)
Pote(s)
Sachê(s)
Saquinho(s)
Punhado(s)
Unidade(s)
Xicara(s)
1/2 Xicara(s)
1/3 Xicara(s)
1/4 Xicara(s)
Xicara(s) de Café
Xicara(s) de Chá
Colher(es) Café
Colher(es) Chá
Colher(es) Sopa
Colher(es) Sobremesa
Grama(s)
Quilo(s)
Militro(s)
Litro(s)
Dose(s)
Meia Dose(s)
Caixa(s)
Cubo(s)
Fatia(s)
Bola(s)

                 -->
                <script>
                
                // Inicialize o Select2

                    $(document).ready(function() {
                        $('#nome_ingrediente_<?php echo $i; ?>').select2({
                            placeholder: "Selecione um Ingrediente",
                            allowClear: true // Permite limpar a seleção
                        });
                    });
                </script>

            <?php endfor; ?>
            <br><br>
            <!-- Carrega o conteúdo do arquivo de texto -->
            <?php $placeholder_text = file_get_contents('receita.txt'); ?>

            <label>Modo de Preparo:<br><br></label>
            
            <textarea name="modoPreparo_receita" id="modoPreparo_receita" placeholder="<?php echo $placeholder_text; ?>" rows="20" cols="65" class="input-field" style="width: 488px; ;"></textarea>
            <br><br>
            <input type="submit" class="botao" value="Enviar" name="CadReceita">
        </form>
    </div>
</body>
</html>
