<?php
    ob_start(); // Inicia o buffer de saída
    session_start(); // Inicia a sessão
    
    include_once '../conexao.php';

    $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Verifica se o parâmetro 'pagina' está definido na URL, senão define como 1
    $quantidade_pg = 12; // Define a quantidade de ingredientes por página
    $inicio = ($quantidade_pg * $pagina) - $quantidade_pg; // Calcula o início da seleção dos registros

    $pesquisar = isset($_GET['pesquisar']) ? $_GET['pesquisar'] : ''; // Verifica se o parâmetro 'pesquisar' está definido na URL

    if ($pesquisar) { // Prepara a query para contar o número de registros encontrados na pesquisa
        $stmt = $conn->prepare("SELECT COUNT(*) FROM ingrediente WHERE nome_ingrediente LIKE :pesquisar");
        $stmt->bindValue(':pesquisar', "%$pesquisar%", PDO::PARAM_STR);
    } else { // Se não houver pesquisa, conta o total de registros sem filtro
        $stmt = $conn->query("SELECT COUNT(*) FROM ingrediente");
    }

    $stmt->execute(); // Executa a query
    $total_ingrediente = $stmt->fetchColumn(); // Obtém o número total de registros
    $num_pagina = ceil($total_ingrediente / $quantidade_pg); // Calcula o número total de páginas

    if ($pesquisar) {
        // Prepara a query para selecionar os registros encontrados na pesquisa com limitação
        $stmt = $conn->prepare("SELECT * FROM ingrediente WHERE nome_ingrediente LIKE :pesquisar LIMIT :inicio, :quantidade_pg");
        $stmt->bindValue(':pesquisar', "%$pesquisar%", PDO::PARAM_STR); 
    } else {
        // Prepara a query para selecionar todos os registros com limitação
        $stmt = $conn->prepare("SELECT * FROM ingrediente LIMIT :inicio, :quantidade_pg");
    }

    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT); // Define o valor do início para a query
    $stmt->bindValue(':quantidade_pg', $quantidade_pg, PDO::PARAM_INT); // Define a quantidade de registros por página para a query
    $stmt->execute(); // Executa a query
    $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os registros como um array associativo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap/home/bootstrap.min.css" rel="stylesheet">
    <title>Home</title>
    <style>
        .thumbnail {
            width: 100%;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .thumbnail img {
            height: 150px;
            object-fit: cover;
        }

        .caption {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .caption h3 {
            font-size: 1.5rem;
            margin: 0.5em 0;
        }

        .caption p {
            margin-top: auto;
        }

        .search-form {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .search-form .form-group {
            margin-right: 10px;
        }

        .cart-container {
            position: fixed;
            right: -300px;
            top: 0;
            width: 300px;
            height: 100%;
            background-color: white;
            border-left: 1px solid #ccc;
            padding: 10px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            transition: right 0.3s ease;
            overflow-y: auto;
            z-index: 1000;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h4 {
            margin: 0;
        }

        .cart-close {
            cursor: pointer;
        }

        .cart-items {
            list-style: none;
            padding: 0;
        }

        .cart-items li {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .show-cart {
            right: 0;
        }

        .align-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        #pesquisarReceitaButton {
            display: block;
            margin: 0 auto;
        }

        .caption p {
            display: flex;
            justify-content: center;
        }

        .header { /* Menu */
    background-color: #5B4476;;
    height: 65px;
    text-align: center;
    /* padding-top: 15px; */

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #5B4476;
    }

    li {
        float: left;
    }

    li a {
        display: block;
        color: white;
        text-align: center;
        padding: 18px 20px;
        text-decoration: none;
        font-family: Arial, Helvetica, sans-serif;
    }

    body { /* Remova a margem do body para eliminar o espaço branco */
        margin: 0;
    }
                
    ul { /* Remova a margem do ul para eliminar o espaço branco acima da barra de navegação */
        margin-top: 0;
    }
                

    ul { /* Remova o espaço ao redor do ul para eliminar o espaço branco ao redor da barra de navegação */
        padding: 0;
    }
                
    
    ul li { /* Remove o espaço entre os itens da lista */
        margin: 0;
    }
                
    li a { /* Remove a margem dos links */
            margin: 0;
    }
}
    </style>
</head>
<body>
<div class="header">
        <ul>
            <li><a class="active" href="#">Home</a></li>
            <li><a href="http://localhost/TCC/tcc_receitas/receita/listagem_receita.php">Livro de Receitas</a></li>
            <li><a href="http://localhost/TCC/tcc_receitas/usuario/cadastrar.php">Cadastre-se</a></li>
            <li><a href="http://localhost/TCC/tcc_receitas/usuario/login.php">Logar</a></li>
            <li><a href="#">Mais ▼</a></li>
          </ul>
    </div>
    </div>
    <div class="container theme-showcase" role="main">
        <div class="page-header">
            <div class="row">

                <div class="col-sm-6 col-md-6">
                    <h1>Ingredientes</h1>
                </div>

                <div class="col-sm-6 col-md-6 align-right">
                    <form id="searchForm" class="form-inline" method="GET" action="">
                        <button id="cartButton" class="btn btn-primary" style="margin-right: 10px;">Carrinho</button>
                        <div class="form-group">
                            <label for="exampleInputName2" class="sr-only">Pesquisar</label>
                            <input type="text" name="pesquisar" class="form-control" id="exampleInputName2" placeholder="Pesquisar..." value="<?php echo htmlspecialchars($pesquisar); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </form> 
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($ingredientes as $ingrediente) { ?>
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail">
                        <img src="<?php echo htmlspecialchars($ingrediente['imagem_ingrediente']); ?>" alt="Ingrediente Image">
                        <div class="caption text-center">
                            <h3><?php echo htmlspecialchars($ingrediente['nome_ingrediente']); ?></h3>
                            <p><button class="btn btn-primary add-to-cart" data-id="<?php echo htmlspecialchars($ingrediente['id_ingrediente']); ?>" data-name="<?php echo htmlspecialchars($ingrediente['nome_ingrediente']); ?>">Adicionar</button></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $num_pagina; $i++) { ?>
                    <li class="<?php echo ($pagina == $i) ? 'active' : ''; ?>">
                        <a href="?pagina=<?php echo $i; ?>&pesquisar=<?php echo htmlspecialchars($pesquisar); ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="cart-container">
        <div class="cart-header">
            <h4><br>Carrinho<br><br></h4>
            <span id="closeCart" class="cart-close">&times;</span>
        </div>
        <ul id="cartItems" class="cart-items"></ul>
        <div>
            <!-- <button id="pesquisarReceitaButton" class="btn btn-primary">Pesquisar Receita</button>   -->
            <button id="pesquisarReceitaButton" class="btn btn-primary" target="_blank">Pesquisar Receita</button>
 

        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script id="cartItemTemplate" type="text/x-custom-template">
        <li>
            <span class="item-name"></span>
            <span class="item-id" style="margin-left: 10px;"></span>
            <button class="btn btn-danger btn-sm remove-from-cart" style="margin-left: auto;">Remover</button>
        </li>
    </script>

    <script>
    $(document).ready(function(){
        function saveCart() {
            localStorage.setItem('cartItems', JSON.stringify(cartItemsArray));
        }

        function loadCart() {
            var storedCartItems = localStorage.getItem('cartItems');
            if (storedCartItems) {
                cartItemsArray = JSON.parse(storedCartItems);
                cartItemsArray.forEach(function(item) {
                    addCartItemToDOM(item.name, item.id);
                });
            }
        }

        var cartItemsArray = [];

        function addToCart(itemName, itemId) {
            cartItemsArray.push({name: itemName, id: itemId});
            addCartItemToDOM(itemName, itemId);
            saveCart();
        }

        function addCartItemToDOM(itemName, itemId) {
            var cartItemTemplate = $('#cartItemTemplate').html();
            var $cartItem = $(cartItemTemplate);
            $cartItem.find('.item-name').text(itemName);
            $cartItem.find('.item-id').text(`(ID: ${itemId})`);
            $('#cartItems').append($cartItem);
        }

        loadCart();

        $('#cartButton').click(function(event){
            event.preventDefault();
            $('#cartSidebar').toggleClass('show-cart');
        });

        $('#closeCart').click(function(){
            $('#cartSidebar').removeClass('show-cart');
        });

        $('.add-to-cart').click(function(){
            var itemName = $(this).data('name');
            var itemId = $(this).data('id');
            
            if (!cartItemsArray.some(item => item.name === itemName && item.id == itemId)) {
                addToCart(itemName, itemId);
            } else {
                alert("Este ingrediente já foi adicionado ao carrinho.");
            }
        });

        $('#cartItems').on('click', '.remove-from-cart', function(){
            var itemName = $(this).closest('li').find('.item-name').text();
            var itemId = $(this).closest('li').find('.item-id').text().replace('(ID: ', '').replace(')', '');
            cartItemsArray = cartItemsArray.filter(item => !(item.name === itemName && item.id == itemId));
            $(this).closest('li').remove();
            saveCart();
        });

        // Clear cart when the tab is closed
        $(window).on('beforeunload', function(){
            localStorage.removeItem('cartItems');
        });

        // Preserve cart items on pagination and search
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var pageUrl = $(this).attr('href');
            loadPage(pageUrl);
        });

        $('#searchForm').on('submit', function(event) {
            event.preventDefault();
            var pesquisar = $('#exampleInputName2').val();
            loadPage('?pesquisar=' + encodeURIComponent(pesquisar));
            
        });

        function loadPage(url) {
            $(window).off('beforeunload');
            window.location.href = url;
        }

    $('#pesquisarReceitaButton').click(function() {
        var cartItems = JSON.stringify(cartItemsArray);
        var form = $('<form action="listagem_receita_ingrediente.php" method="post" target="_blank">' +
                    '<input type="hidden" name="cartItems" value=\'' + cartItems + '\'>' +
                    '</form>');
        $('body').append(form);
        form.submit();
    });

});

</script>

</body>
</html>
