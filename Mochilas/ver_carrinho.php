<?php
session_start();
include 'conexao.php';

  define('MY_APP', true);

  if (isset($_SESSION['user_id'])) {
    $exibe = $_SESSION['name_user'] . ", SAIR!";
  } else {
    $exibe = "Entrar";
  }

// Verificar se está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

// Verificar se o carrinho está vazio
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<script>
        alert('Seu carrinho está vazio!');
        window.location.href = 'produtos.php';
    </script>";
    exit;
}

// Garantir que o carrinho seja um array
$carrinho = is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

$ids = array_keys($carrinho);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "SELECT * FROM tblproduto WHERE id_produto IN ($placeholders)";


$stmt = $conn->prepare($sql);
$stmt->execute($ids);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Inicializar variáveis
$total_itens = 0;
$valor_total = 0.0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="carrinho.css">
    <link rel="stylesheet" href="../footer.css">
    <script type="text/javascript" src="../jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="../styles.css">
    <title>Carrinho de Compras - Brindou.com</title>
</head>


<!--Quantidade mínima de 20 unidades por item-->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const removerForms = document.querySelectorAll(".form-remover");

  removerForms.forEach(form => {
    form.addEventListener("submit", function(event) {
      const acao = this.querySelector("button[type='submit'][clicked=true]")?.value;

      if (acao === "remover") {
        const quantidadeText = this.closest(".produto-item").querySelector(".quantidade").textContent;
        const quantidadeAtual = parseInt(quantidadeText);

        const quantidadeRemoverInput = this.querySelector("input[name='quantidade_remover']");
        const quantidadeARemover = parseInt(quantidadeRemoverInput.value);

        const quantidadeFinal = quantidadeAtual - quantidadeARemover;

        if (quantidadeFinal < 20) {
          event.preventDefault();
          alert("Quantidade mínima para compra: 20 unidades de cada item.");
        }
      }
    });

    // Detecta qual botão foi clicado
    const buttons = form.querySelectorAll("button[type='submit']");
    buttons.forEach(button => {
      button.addEventListener("click", function() {
        buttons.forEach(b => b.removeAttribute("clicked"));
        this.setAttribute("clicked", "true");
      });
    });
  });
});
</script>



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
            <a href="login/login.php"><img style="filter: invert(100%); width:50px; height: 50px;" src="../imgs/log.ico" alt="login" style="width: 40px; height: 40px;"></a>
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
  border-bottom: 2px solid #053358;">
    
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

  <!---->

  <br/><br/>

  <div class="corpo">
    <h3>Carrinho de Compras</h3>
    <div class="linha-subtitulo2"></div>
    <ul>
    <?php foreach ($produtos as $produto): ?>
        <?php
            $produto_id = $produto['id_produto'];
            $quantidade = isset($carrinho[$produto_id]) ? $carrinho[$produto_id] : 0;
            $subtotal = $produto['preco'] * $quantidade;
            $total_itens += $quantidade;
            $valor_total += $subtotal;
        ?>
        <li class="corpo-li">
            <div class="remover-tudo">
              <form action="remover_carrinho.php" method="POST">
                  <input type="hidden" name="id_produto" value="<?= $produto_id; ?>">
                  <input type="hidden" name="acao" value="remover_tudo">
                  <button type="submit" class="remover-tudo-button">Remover tudo</button>
              </form>
            </div>


            <div class="produto-item">
                <div class="produto-imagem">
                    <img id="mainImage" width="50px" class="main-image" src="listar_img.php?id=<?= $produto_id; ?>&img=2" alt="<?= htmlspecialchars($produto['descricao_resumida']); ?>">
                </div>

                <div class="produto-detalhes">
                    <p class="descricao"><strong><?= htmlspecialchars($produto['descricao_resumida']); ?></strong></p>
                    <p class="quantidade"><?= $quantidade; ?> unidades</p>
                    <p class="preco-unitario">Preço unitário: R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <p class="preco-total">Total: R$ <?= number_format($subtotal, 2, ',', '.'); ?></p>
                </div>

                <div class="produto-acoes">
                    <form action="remover_carrinho.php" method="POST" class="form-remover">
                        <input type="hidden" name="id_produto" value="<?= $produto_id; ?>">
                        <button type="submit" class="btn-rmv" name="acao" value="remover">-</button>
                        <input type="number" name="quantidade_remover" min="1" max="<?= $quantidade; ?>" value="1">
                        <button type="submit" class="btn-adc" name="acao" value="adicionar">+</button>
                    </form> 
                </div>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>

    <!-- Resumo da Compra Abaixo da Lista -->
    <div class="resumo-compra">
        <h2>Resumo da Compra</h2>

        <hr/>

            <div class="resumo-compra-infos">
                <p>Itens no carrinho: <?= count($produtos); ?></p>
                <p>Quantidade total de unidades: <?= $total_itens; ?></p>
                <br/>
                <p class="resumo-compra-total">Valor total da compra: R$ <?= number_format($valor_total, 2, ',', '.'); ?></p> <br />
                <a href="checkout.php" target="_blank">Gerar orçamento</a>
            </div>

    
  </div>


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