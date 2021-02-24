<?php
var_dump($productos);
  require_once 'includes/header.php';
?>
  <section id="main">
    <article id="profile">
      <img src="img/profile-picture.png" alt="<?= $_SESSION['user']?> Profile Photo">
      <h1> Hola <strong><?= $_SESSION['user']?></strong> </h1>
    </article>
    <article id="products">

      <h2>Productos por agotarse</h2>
      <div class="carousel-productos">
      
        <div class="card" style="width: 18rem;">
          <img src="..." class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
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