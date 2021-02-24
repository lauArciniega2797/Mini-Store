<?php
// var_dump($productos);
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
      <?php foreach ($productos as $product): ?>
        <div class="card" style="width: 18rem;" 
          <?php if($product['status'] == 'empty'):?> style="border:1px solid red;" <?php elseif($product['status'] == 'warning'): ?>style="border:1px solid yellow;"<?php endif;?>>
          <img src=<?= $product['image'] == '' ? "https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80" : "images/".$product['image']; ?> class="card-img-top" alt="Eunicodin">
          <div class="card-body">
            <h5 class="card-title"><?=$product['name']?></h5>
            <p class="card-text"><?=$product['store_price']?></p>
            <!-- <a href="#" class="btn btn-primary">Editar</a>
            <a href="#" class="btn btn-primary">Eliminar</a> -->
          </div>
        </div>
      <?php endforeach;?>
      </div>
      
      <h2>Mas vendidos</h2>
      <div>
      </div>
    </article>
  </section>
<?php
  require_once 'includes/footer.php';
?>