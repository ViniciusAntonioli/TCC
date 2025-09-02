<?php
session_start();
include 'conexao.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Produto não encontrado.");
}

$id_produto = $_GET['id'];

$sql = "SELECT p.*, c.nome_categoria, m.nome_material 
        FROM tblproduto p
        JOIN tblcategoria c ON p.id_categoria = c.id_categoria
        JOIN tbltipo_material m ON p.id_tipo_material = m.id_tipo_material
        WHERE p.id_produto = :id_produto AND p.ativo = 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlRelacionados = "SELECT top 4 p.id_produto, p.descricao_resumida, p.preco 
                    FROM tblproduto p 
                    WHERE p.id_categoria = :id_categoria 
                      AND p.id_produto != :id_atual 
                      AND p.ativo = 1 
                   ";
$stmtRelacionados = $conn->prepare($sqlRelacionados);
$stmtRelacionados->bindParam(':id_categoria', $produto['id_categoria'], PDO::PARAM_INT);
$stmtRelacionados->bindParam(':id_atual', $produto['id_produto'], PDO::PARAM_INT);
$stmtRelacionados->execute();
$relacionados = $stmtRelacionados->fetchAll(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

$estoque = (int)$produto['quantidade_estoque'];
$disponivel = $estoque > 0;

if (isset($_SESSION['user_id'])) {
    $exibe = $_SESSION['name_user'] . ", SAIR!";
} else {
    $exibe = "Entrar";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../footer.css">
  <link rel="stylesheet" href="../styles.css">
  <link rel="stylesheet" href="detalhes/styles.css">
  <script type="text/javascript" src="../jquery-3.7.1.js"></script>
  <script src="detalhes/script.js"></script>
  <script src="detalhes/script2.js"></script> 
  <title>Detalhes do Produto - Brindou.com</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      font-size: 20px;
    }

    .produtos-relacionados {
  margin: 60px auto;
  padding: 20px;
  max-width: 1200px;
}

.produtos-relacionados h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 28px;
  color: #333;
}

.relacionados-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.relacionado-card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  padding: 16px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.relacionado-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.relacionado-card img {
  width: 100%;
  max-height: 180px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 12px;
}

.relacionado-card h3 {
  font-size: 18px;
  margin: 10px 0;
  color: #00111F;
}

.relacionado-card p {
  font-size: 1.8rem;
  color: #00111F;
  font-weight: bold;
}
  .desc {
  width: 500px; /* Define a largura da div */
  word-wrap: break-word; /* Permite a quebra de palavras */
  background-color:rgb(255, 255, 255); /* Cor de fundo para destacar */
}
  

</style>
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
        <form class="searchbar" method="GET" action="produtos.php">
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

<div class="container_prod_detalhado">
  <main class="produto_detalhado">
    <div class="galeria_fotos">
      <div class="zoom-container">
        <img id="mainImage" class="main-image" src="listar_img.php?id=<?= $produto['id_produto']; ?>&img=2" alt="<?= htmlspecialchars($produto['descricao_resumida']); ?>">
      </div>
        <div class="thumbnails">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <img class="thumbnail" src="listar_img.php?id=<?= $produto['id_produto']; ?>&img=<?= $i; ?>" alt="Imagem <?= $i; ?>" onclick="changeImage('listar_img.php?id=<?= $produto['id_produto']; ?>&img=<?= $i; ?>')">
        <?php endfor; ?>
    </div>
    </div>


    <div class="info_geral">

      <h1 class="produto-nome"><?= htmlspecialchars($produto['descricao_resumida']); ?></h1>
      <div class="desc"><p class="produto-nome"><?= htmlspecialchars($produto['descricao']); ?></p></div>
      <p>Quantidade em estoque: <?= $estoque ?></p>

      <?php if (!$disponivel): ?> 
        <p style="color:red;">Produto esgotado.</p>
      <?php endif; ?>

      
      <form id="formCarrinho">
        <input type="hidden" name="id_produto" id="id_produto" value="<?= $produto['id_produto']; ?>">
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" min="20" max="<?= $estoque ?>" value="20" <?= !$disponivel ? 'disabled' : '' ?>>

        <p class="produto-preco">Preço: R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></p>

        <button type="button" id="btnComprar" class="btn-comprar" <?= !$disponivel ? 'disabled' : '' ?>>Comprar</button>
        <button type="button" id="btnAdicionar" class="btn-adicionar" style="display: none;" <?= !$disponivel ? 'disabled' : '' ?>>Adicionar ao carrinho</button>
      </form>

      <div id="mensagem"></div>
    <br /><br /><br /><br />

    <div class="info_detalhes">
      <p class="info-detalhes-primeiro"><strong>• Categoria:</strong> <?= htmlspecialchars($produto['nome_categoria']); ?></p>
      <p><strong>• Material:</strong> <?= htmlspecialchars($produto['nome_material']); ?></p>
      <p><strong>• Peso:</strong> <?= htmlspecialchars($produto['peso']); ?></p>
      <p><strong>• Dimensões:</strong> <?= htmlspecialchars($produto['altura']); ?>cm x<?= htmlspecialchars($produto['largura']); ?>cm x<?= htmlspecialchars($produto['comprimento']); ?>cm</p>
      <p><strong>• Código do Produto:</strong> <?= htmlspecialchars($produto['id_produto']); ?></p>
    </div>
  </main>
</div>

<script>
function adicionarCarrinho(redirecionarPara) {
  let id_produto = document.getElementById('id_produto').value;
  let quantidade = parseInt(document.getElementById('quantidade').value);
  let maxEstoque = <?= $estoque ?>;

  if (quantidade < 20 || quantidade > maxEstoque) {
    alert("Quantidade inválida. A quantidade deve ser maior do que 20");
    return;
  }

  let formData = new FormData();
  formData.append("id_produto", id_produto);
  formData.append("quantidade", quantidade);

  fetch('carrinho.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('mensagem').innerText = data.mensagem;
    if (data.success) {
      window.location.href = redirecionarPara;
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    alert('Ocorreu um erro ao adicionar o produto.');
  });
}

document.getElementById('btnAdicionar').addEventListener('click', function() {
  adicionarCarrinho('mochilas.php');
});

document.getElementById('btnComprar').addEventListener('click', function() {
  adicionarCarrinho('ver_carrinho.php');
});
</script>

<?php if ($relacionados): ?>
  <section class="produtos-relacionados">
    <h2>Produtos Relacionados</h2>
    <div class="relacionados-grid">
      <?php foreach ($relacionados as $item): ?>
        <div class="relacionado-card">
          <a href="detalhes_produto.php?id=<?= $item['id_produto']; ?>">
            <img src="listar_img.php?id=<?= $item['id_produto']; ?>&img=1" alt="<?= htmlspecialchars($item['descricao_resumida']); ?>">
            <h3><?= htmlspecialchars($item['descricao_resumida']); ?></h3>
            <p>R$ <?= number_format($item['preco'], 2, ',', '.'); ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<footer>
  <div class="footer-container">
      <div class="footer-section">
        <h3>Brindou.com</h3>
        <p>Soluções criativas para empresas de brindes.</p>
      </div>
      <div class="footer-section">
        <h3>Links Rápidos</h3>
        <ul>
          <li><a href="#">Sobre Nós</a></li>
          <li><a href="#">Produtos</a></li>
          <li><a href="#">Contato</a></li>
          <li><a href="#">Política de Privacidade</a></li>
        </ul>
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
          <a href="" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Brindou.com - Todos os direitos reservados.</p>
  </div>
</footer>
</body>
</html>