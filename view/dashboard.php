<?php
  require_once 'includes/header.php';
?>
<body>
  <header id="header">
    <nav>
      <div><h2>EUNICODIN</h2></div>
      <div>
        <ul>
          <li><a href="#dashboard">Inicio</a></li>
          <li><a href="#productos">Productos</a></li>
          <li><a href="#nuevo_producto">Nuevo Producto</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <section id="main">
    <article id="profile">
      <img src="img/profile-picture.png" alt="<?= $_SESSION['user']?> Profile Photo">
      <h1> Hola <strong><?= $_SESSION['user']?></strong> </h1>
    </article>
    <article id="products">

      <h2>Productos por agotarse</h2>
      <div class="carousel-productos">
        <div class="queue">
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
          <div class="product">
            <div class="product-data">
              <img src="" alt="">
              <h3 class="name">Nombre</h3>
              <p class="description">pequeña descripcion</p>
              <span><i>$</i>20.00</span>
            </div>
            <div class="product-buttons">
              <a href="" class="edit"></a>
              <a href="" class="delete"></a>
            </div>
          </div>
        </div>
      </div>
      
      <h2>Mas vendidos</h2>
      <div>
      </div>
    </article>
  </section>
<?php
  require_once 'includes/footer.php';
?>