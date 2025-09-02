<?php
session_start();
include 'mochilas/conexao.php';

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="footer.css">
  <script type="text/javascript" src="jquery-3.7.1.js"></script>
  <link rel="stylesheet" href="styles.css">
  <title>Brindou.com</title>
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
      <div class="logo"><img src="imgs/brindou.com logo1.png" alt="Brindou.com"/></div>



      <div style="display: flex; gap: 2em;">
      <a style="text-decoration: none; color: white;" href="javascript:void(0);" onclick="openCart()"><img src="imgs/carrinho.png" alt="login" style="width: 40px; height: 40px;"></a>

      <div class="login" style="text-align: center;">
            <a href="Mochilas/login/login.php"><img src="imgs/log.ico" style="filter: invert(100%); width:50px; height: 50px;" alt="login" style="width: 40px; height: 40px;"></a>
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
    
          <a href="Mochilas/ver_carrinho.php" style="display: flex;
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
    
          <a href="Mochilas/consultar_pedidos.php" style="display: flex;
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
        <form class="searchbar" method="GET" action="mochilas/produtos.php">
          <input type="text" placeholder="Buscar por produtos" name="search" id="searchb">
          <button type="submit" class="btnpesquisa" id="searchButton">
            <img src="mochilas/imgs/searchico.webp" alt="Icone de pesquisa" id="iconepesquisa">
          </button>
          </form>
      </div> 
        
      <!-- Menu ao clicar -->
   <div class="leftmenu">
      <div class="titleleftmenu" style="padding-bottom: 1em;">Categorias</div>
	  <a href="index.php"><div>Início</div></a>
      <a href="mochilas/produtos.php?search=mochila"><div class="mochilas">Mochilas</div></a>
      <a href="mochilas/produtos.php?search=garrafa"><div class="garrafas">Garrafas</div></a>
      <a href="mochilas/produtos.php?search=caneta"> <div class="canetas">Canetas</div></a>
      <a href="mochilas/produtos.php?search=sacola"><div class="sacolas">Sacolas</div></a>
      <a href="mochilas/produtos.php?search=bolsa"><div class="bolsas">Bolsas</div></a>
      <a href="mochilas/produtos.php?search=caderno"><div class="cadernos">Cadernos</div></a>
      <a href="mochilas/produtos.php?search=chaveiro"><div class="chaveiros">Chaveiros</div></a>
      <a href="mochilas/produtos.php?search=copo"><div class="copos">Copos</div></a>
      <a href="mochilas/produtos.php?search=caneca"><div class="Canecas">Canecas</div></a>
      <a href="mochilas/produtos.php?search=carregador"><div class="Carregadores">Carregadores</div></a>
      <a href="mochilas/produtos.php?search=carteira"><div class="Carteiras">Carteiras</div></a>
      <a href="mochilas/produtos.php?search=cozinha"><div class="Cozinha">Cozinha</div></a>
      <a href="mochilas/produtos.php?search=estojo"> <div class="Estojos">Estojos</div></a>
      <a href="mochilas/produtos.php?search=garrafa"><div class="Ferramentas">Ferramentas</div></a>
      <a href="mochilas/produtos.php?search=fone"><div class="Fone de ouvido">Fone de ouvido</div></a>
      <a href="mochilas/produtos.php?search=guarda"> <div class="Guarda-chiva">Guarda-chuva</div></a>
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


<div class="slider">

  <div class="slides">
    <!--Radio Buttons-->
    <input type="radio" name="radio-btn" id="radio1">
    <input type="radio" name="radio-btn" id="radio2">
    <input type="radio" name="radio-btn" id="radio3">
    <input type="radio" name="radio-btn" id="radio4">
    <!--Fim Radio Buttons-->

    <!--Slide images-->
    <div class="slide first">
      <img src="imgs/Slider/slider1a.jpg" alt="Imagem 1">
    </div>
    <div class="slide">
      <img src="imgs/Slider/slider2a.jpg" alt="Imagem 2">
    </div>
    <div class="slide">
      <img src="imgs/Slider/slider3a.png" alt="Imagem 3">
    </div>
    <div class="slide">
      <img src="imgs/Slider/slider4a.jpg" alt="Imagem 4">
    </div>
    <!--Fim Slide images-->

    <!--Navigation auto-->
    <div class="navigation-auto">
      <div class="auto-btn1"></div>
      <div class="auto-btn2"></div>
      <div class="auto-btn3"></div>
      <div class="auto-btn4"></div>
    </div>
    <!--Fim Navigation auto-->

  </div>

  <div class="manual-navigation">
    <label for="radio1" class="manual-btn"></label>
    <label for="radio2" class="manual-btn"></label>
    <label for="radio3" class="manual-btn"></label>
    <label for="radio4" class="manual-btn"></label>
  </div>

</div>

<script src="script.js"></script>


<div class="separador"></div>
<br><br>


<!--Produtos Destaque-->

<div class="produtos-destaque-background">
  <div class="produtos-destaque">
    <h3 class="titulo-secao">Produtos Destaque</h3>
    <div class="linha-subtitulo"></div>
      <div class="cols cols-4">
        <?php include 'listar_produtos_home.php'; ?>    
      </div>
    </div>
  </div>
</div>

<!--Fim Produtos Destaque-->



  <br><br>


  <!--Seção Garrafas-->

  <div class="mochilas-destaque-background">
    <div class="mochilas-destaque">
      <h3 class="titulo-categoria">Garrafas</h3>
        <div class="cols cols-5">

          <?php
            $categoria = "Squeezes e Garrafas"; // O nome da categoria (como está na tabela)
            include 'listar_produtos_categoria.php';
          ?>

          
        </div>
      </div>
    </div>
  </div>

  <!--Fim Seção Garrafas-->

  <br>

    <!--Seção Mochilas-->

    <div class="mochilas-destaque-background">
      <div class="mochilas-destaque">
        <h3 class="titulo-categoria">Mochilas</h3>
          <div class="cols cols-5">
            
            <?php
              $categoria = "Mochilas e Malas"; // O nome da categoria (como está na tabela)
              include 'listar_produtos_categoria.php';
            ?>
          
          </div>
        </div>
      </div>
    </div>
  
    <!--Fim Seção Mochilas-->

    <br>

    <!--Categorias-->

<div class="categorias-destaque-background">
  <div class="categorias-destaque">
    <h3 class="titulo-secao">Categorias</h3>
    <div class="linha-subtitulo"></div>
      <div class="cols cols-6">

        <?php /* include 'listar_categorias.php'; */ ?>
        
        <div class="categoria">
          <img src="destaques/mochila.webp" alt="mochila"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Mochilas</p>
          </div>
        </div>
        <div class="categoria">
          <img src="destaques/garrafa.jpeg" alt="garrafa"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Garrafas</p>
          </div>  
        </div>
        <div class="categoria">
          <img src="destaques/sacola.jpeg" alt="sacola"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Sacolas</p>
          </div>
        </div>
        <div class="categoria">
          <img src="destaques/xicara.png" alt="xicara" width="200" height="200"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Xícaras</p>
          </div>  
        </div> 
        <div class="categoria">
          <img src="destaques/estojo.jpg" alt="estojo" width="200" height="200"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Estojos</p>
          </div>  
        </div> 
        <div class="categoria">
          <img src="destaques/caderno.webp" alt="caderno" width="200" height="200"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Cadernos</p>
          </div>  
        </div>
        <div class="categoria">
          <img src="destaques/chaveiro.jpeg" alt="chaveiro"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Chaveiros</p>
          </div>
        </div>
        <div class="categoria">
          <img src="destaques/copo.jpeg" alt="copo"/>
          <div class="nome-categoria">
            <p class="txt-categoria">Copos</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!--Fim Produtos Destaque-->

<br><br><br>

  

<br><br><br>
<div class="banner3-container">
  <div class="banner3">
    <iframe src="https://www.youtube.com/embed/OlSSFZzRh_o" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
</div>
<br><br><br>


<div id="fundo-escuro" onclick="fecharPopup()"></div>

<div id="popup">
    <span id="close-btn" onclick="fecharPopup()">&times;</span>
    <h2>Fique por Dentro das Novidades</h2>
    <p>Receba informações exclusivas sobre nossos lançamentos, promoções especiais e muito mais!
    </p>
    <p>Ao se cadastrar, você receberá:</p>
    <ul class="lista-newsletter">
        <li><strong>Lançamentos e atualizações</strong></li>
        <li><strong>Descontos e promoções especiais</strong></li>
        <li><strong>Dicas e inspirações</strong></li>
    </ul>
    <p>Receba apenas novidades que fazem a diferença para sua empresa, sem envios desnecessários.</p>

    <form onsubmit="return assinarNewsletter();">
        <input type="email" id="email" name="email" placeholder="E-mail" required
               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Insira um e-mail válido.">
        <button type="submit" id="subscribe-btn">Enviar</button>
    </form>
    <div id="message"></div> <!-- Elemento para exibir mensagens -->
</div>

<!-- Popup de Sucesso -->
<div id="popup-sucesso" onclick="fecharPopupSucesso(event)">
    <span id="close-sucesso-btn" onclick="fecharPopupSucesso(event)">&times;</span>
    <h2>Sucesso!</h2>
    <p id="sucesso-message"></p>
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

<script src="script.js"></script>


</body>
</html>