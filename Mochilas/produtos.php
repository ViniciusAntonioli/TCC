<?php
  session_start();

  define('MY_APP', true);

  if (isset($_SESSION['user_id'])) {
    $exibe = $_SESSION['name_user'] . ", SAIR!";
  } else {
    $exibe = "Entrar";
  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles.css">
  <link rel="stylesheet" href="../footer.css">
  <link rel="stylesheet" href="categorystyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="scriptm.js"></script>
  <title>Categorias - Brindou.com</title>
</head>
<body>

  <header>
    <nav>

      <!--Menu Hamburguer-->

      
      <div class="menu-container-hamburguer">
        <div class="hamburguer">
          <div class="line"></div>
          <div class="line"></div>
          <div class="line"></div>
        </div>
        <span class="menu-text">MENU</span>
      </div>
      <!-------------------->
      <!---Logo-->

      <div class="logo"><img src="../imgs/brindou.com logo1.png" alt="Brindou.com"/></div>



      <div style="display: flex; gap: 2em;">
      <a style="text-decoration: none; color: white;" href="javascript:void(0);" onclick="openCart()"><img src="../imgs/carrinho.png" alt="login" style="width: 40px; height: 40px;"></a>

      <div class="login" style="text-align: center;">
            <a href="login/login.php"><img src="../imgs/log.ico" style="filter: invert(100%); width:50px; height: 50px;" alt="login" style="width: 40px; height: 40px;"></a>
            <div style="font-weight: bold; font-size: 0.8em; color: rgb(255, 255, 255); margin-top: 5px;"><?= $exibe ?></div>
           
       
          
          </div> 

          
      </div>
       
      <div class="cart-container" style="padding: 5px 10px;
  overflow: hidden;
  display: none;
  align-items: center;
  justify-content: center;
  gap: 10px;
  flex-direction: row;
  background-color: #00111f;
  position: fixed;
  top: 146px;
  left: 0;
  width: 100%;
  height: 60px;
  z-index: 99999;
  border-bottom: 2px solid #053358;
  border-radius: 0 0 10px 10px;">
    
          <a href="ver_carrinho.php" style="display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: bold;
    height: 40px;
    text-decoration: none;
    color: white;
    background-color: rgb(5, 51, 88);
    padding: 0 30px;
    border-radius: 5px;">
            Ver Carrinho
          </a>
    
          <a href="consultar_pedidos.php" style="display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: bold;
    height: 40px;
    text-decoration: none;
    color: white;
    background-color: rgb(5, 51, 88);
    padding: 0 30px;
    border-radius: 5px;">
            Pedidos
          </a>

      </div>

      <script>
        function openCart() {
          if(document.querySelector('.cart-container').style.display == 'flex') {
            document.querySelector('.cart-container').style.display = 'none';
          } else {
            document.querySelector('.cart-container').style.display = 'flex';
          }
        }

      </script>
      </div>
      </nav>
      <div class="secondbar">
        <form class="searchbar" method="GET" action="mochilas.php">
          <input type="text" placeholder="Buscar por produtos" name="search" id="searchb">
          <button type="submit" class="btnpesquisa" id="searchButton">
            <img src="imgs/searchico.webp" alt="Icone de pesquisa" id="iconepesquisa">
          </button>
          </form>
      </div> 
        
      <!-- Menu ao clicar -->
   <div class="leftmenu">
      <div class="titleleftmenu" style="padding-bottom: 1em;">Categorias</div>
	  <a href="../index.php"><div>Início</div></a>
      <a href="produtos.php?search=mochila"><div class="mochilas">Mochilas</div></a> 
      <a href="produtos.php?search=garrafa"><div class="garrafas">Garrafas</div></a>
      <a href="produtos.php?search=caneta"> <div class="canetas">Canetas</div></a>
      <a href="produtos.php?search=sacola"><div class="sacolas">Sacolas</div></a>
      <a href="produtos.php?search=bolsa"><div class="bolsas">Bolsas</div></a>
      <a href="produtos.php?search=caderno"><div class="cadernos">Cadernos</div></a>
      <a href="produtos.php?search=chaveiro"><div class="chaveiros">Chaveiros</div></a>
      <a href="produtos.php?search=copo"><div class="copos">Copos</div></a>
      <a href="produtos.php?search=caneca"><div class="Canecas">Canecas</div></a>
      <a href="produtos.php?search=carregador"><div class="Carregadores">Carregadores</div></a>
      <a href="produtos.php?search=carteira"><div class="Carteiras">Carteiras</div></a>
      <a href="produtos.php?search=cozinha"><div class="Cozinha">Cozinha</div></a>
      <a href="produtos.php?search=estojo"> <div class="Estojos">Estojos</div></a>
      <a href="produtos.php?search=garrafa"><div class="Ferramentas">Ferramentas</div></a>
      <a href="produtos.php?search=fone"><div class="Fone de ouvido">Fone de ouvido</div></a>
      <a href="produtos.php?search=guarda"> <div class="Guarda-chiva">Guarda-chuva</div></a>
   </div>

  </header>

  <!--Menu esquerdo -->
  <script>
        var menu = document.querySelector(".hamburguer");
        var leftmenu = document.querySelector(".leftmenu");

        menu.addEventListener("click", function () {
          leftmenu.classList.toggle("active");
          menu.toggle("active")
        });

        leftmenu.addEventListener("mouseleave", function() {
          leftmenu.classList.toggle("active");
          menu.toggle("active")
        });
  </script>
  <main>
    <div class="filter-container">
      <h2 id="filter-title">Filtrar produtos</h2>

      <details>
  <summary>
    <input type="checkbox" onclick="toggleAllCheckboxes(this, 'categoria-checkbox')" id="categoriasmochila">
    <label for="categoriasmochila">Categorias</label>
    <script>
      function toggleAllCheckboxes(source, className) {
        let checkboxes = document.querySelectorAll('.' + className);
        for (let i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = source.checked;
        }
      }
    </script>
  </summary>

  <form class="filter-form">
    <?php

    include 'conexao.php'; 

    $sql_categorias = "SELECT id_categoria, nome_categoria FROM tblcategoria ORDER BY nome_categoria";
    $stmt_categorias = $conn->query($sql_categorias); // Use $conn->query() para SELECT sem parâmetros

    while ($row_categoria = $stmt_categorias->fetch(PDO::FETCH_ASSOC)) { // Use $stmt->fetch()
        echo '<div class="filter-option">';
        echo '  <input type="checkbox" id="categoria_' . $row_categoria['id_categoria'] . '" name="categoria[]" value="' . $row_categoria['id_categoria'] . '" class="categoria-checkbox">';
        echo '  <label for="categoria_' . $row_categoria['id_categoria'] . '">' . htmlspecialchars($row_categoria['nome_categoria']) . '</label>';
        echo '</div>';
    }
    ?>
  </form>
</details>
<br />

    <details>
  <summary>
    <input type="checkbox" onclick="toggleAllCheckboxes(this, 'cor-checkbox')" id="cor">
    <label for="cor">Cores</label>
  </summary>

  <form class="filter-form">
    <?php

    $sql_cores = "SELECT DISTINCT cor FROM tblproduto WHERE cor IS NOT NULL AND cor <> '' ORDER BY cor";
    $stmt_cores = $conn->query($sql_cores);


    while ($row_cor = $stmt_cores->fetch(PDO::FETCH_ASSOC)) {
        $cor_limpa = htmlspecialchars($row_cor['cor']);
        $id_cor = strtolower(str_replace(' ', '_', $cor_limpa));
        echo '<div class="filter-option">';
        echo '  <input type="checkbox" id="cor_' . $id_cor . '" name="cor[]" value="' . $cor_limpa . '" class="cor-checkbox">';
        echo '  <label for="cor_' . $id_cor . '">' . $cor_limpa . '</label>';
        echo '</div>';
    }
    ?>

  </form>
</details>
<br />

  <details>
  <summary>
    <input type="checkbox" onclick="toggleAllCheckboxes(this, 'material-checkbox')" id="material">
    <label for="material">Material</label>
  </summary>

  <form class="filter-form">
    <?php

    $sql_materiais = "SELECT id_tipo_material, nome_material FROM tbltipo_material ORDER BY nome_material";
    $stmt_materiais = $conn->query($sql_materiais);



    while ($row_material = $stmt_materiais->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="filter-option">';
        echo '  <input type="checkbox" id="material_' . $row_material['id_tipo_material'] . '" name="material[]" value="' . $row_material['id_tipo_material'] . '" class="material-checkbox">';
        echo '  <label for="material_' . $row_material['id_tipo_material'] . '">' . htmlspecialchars($row_material['nome_material']) . '</label>';
        echo '</div>';
    }
    ?>
    </details><br /><br />

    
  
    <label style="font-weight: bold; font-size: 16px;" for="preco_range">Preço</label>
  
  <form class="filter-form" id="preco-form">
    <div style="margin: 10px 0;">
      <label for="preco_min">Mínimo: R$<span id="min_price_display">0</span></label><br>
      <input type="number" id="preco_min" name="preco_min" min="0" max="1000" step="1" value="0" oninput="updatePriceDisplay()">
    </div>
    <div style="margin: 10px 0;">
      <label for="preco_max">Máximo: R$<span id="max_price_display">1000</span></label><br>
      <input type="number" id="preco_max" name="preco_max" min="0" max="1000" value="1000" oninput="updatePriceDisplay()">
    </div>
  </form>


    <button onclick="showResults();" type="submit">Aplicar Filtro</button>
    <script>
    document.getElementById('preco_min').addEventListener('input', function() {
        const minPrice = parseFloat(document.getElementById('preco_min').value);
        const maxPrice = parseFloat(document.getElementById('preco_max').value);
        if (minPrice < 0 || minPrice > 1000) {
            document.getElementById('preco_min').value = 0;
        }
        
    });
    document.getElementById('preco_max').addEventListener('input', function() {
        const minPrice = parseFloat(document.getElementById('preco_min').value);
        const maxPrice = parseFloat(document.getElementById('preco_max').value);
        if (maxPrice > 1000) {
            document.getElementById('preco_max').value = 1000;
        }
        
    });

    document.getElementById('preco_max').addEventListener('mouseout', function() {
        const minPrice = parseFloat(document.getElementById('preco_min').value);
        const maxPrice = parseFloat(document.getElementById('preco_max').value);
        if (maxPrice < minPrice || maxPrice > 1000) {
            document.getElementById('preco_max').value = 1000;
        }
        
    });

    function showResults() {
  const params = new URLSearchParams();

  document.querySelectorAll("input[type='checkbox']:checked").forEach(cb => {
    if (cb.name === "categoria[]") {
      params.append("categoria[]", cb.value);
    }
    if (cb.name === "cor[]") {
      params.append("cor[]", cb.value);
    }
    if (cb.name === "material[]") {
      params.append("material[]", cb.value);
    }
  });

  const precoMin = document.getElementById('preco_min').value;
  const precoMax = document.getElementById('preco_max').value;
  params.append('preco_min', precoMin);
  params.append('preco_max', precoMax);


  window.location.href = "produtos.php?" + params.toString();
}


  // Função para limpar filtros
  function limpa() {
    // Redireciona para a página sem nenhum filtro
    window.location.href = "produtos.php";
  }


</script>



  </div>
  <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
  <h3 class="titulo-secao">Produtos Destaque <?= !empty($categoria) ? " - " . htmlspecialchars($categoria) : '' ?></h3>
  <div class="linha-subtitulo"></div>

  <br/><br/>
  <div style="display: flex; justify-content: start; width: 100%; gap: 50px; ">
    <button id="btnpair" onclick="pair()">Mudar visualização</button>
    <h3 onclick="limpa()" id="limpa">Limpar filtros</h3>
  </div>
  <hr />
  
<?php

include 'index.php';

?>


<script>

    const images = document.querySelectorAll(".hover-img");

    images.forEach((img) => {
        const originalSrc = img.src;               
        const hoverSrc = "imgs/mochilas/" + img.getAttribute("data-hover"); 

        img.addEventListener("mouseenter", () => {
            img.src = hoverSrc;
        });

        img.addEventListener("mouseleave", () => {
            img.src = originalSrc;
        });
    });

</script>





</div>

  </main>
  <footer>
    <div class="footer-container">
      <div class="footer-section">
        <h3>Brindou.com</h3>
        <p>Soluções criativas para empresas de brindes.</p>
      </div>
      <div class="footer-section">
        <h3>Contato</h3>
        <p><i class="fas fa-phone"></i> (11) 1111-2222</p>
        <p><i class="fas fa-envelope"></i> contato@brindou.com</p>
        <p><i class="fas fa-map-marker-alt"></i> Rua dos Brindes, 123, São Paulo, SP</p>
      </div>
      <div class="footer-section">
        <h3>Siga-nos</h3>
        <div class="social-icons">
          <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Brindou.com - Todos os direitos reservados.</p>
    </div>
  </footer>

</body>
</html>