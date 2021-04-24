<?php
  require_once 'includes/header.php';
?>
  <section id="main" class="dashboard">
    <article id="profile">
      <img src="img/profile-picture.png" alt="<?= $_SESSION['user']?> Profile Photo">
      <h1> Bienvenida <strong><?= $_SESSION['user']?></strong> </h1>
    </article>
    <article id="products">
      <h2>Productos por agotarse o agotados</h2>
      <br>
    <?php if (count($productos) > 0):?>
      <div class="owl-carousel owl-theme">
        <?php foreach ($productos as $product):?>
          <div class="item" style="border: 2px solid #d7d7d7;background-color:white;border-radius: 4px;box-shadow: 0px 0px 5px #cfcfcf;">
            <div class="product_blog_img" style="height:300px;">
              <img style="height:100%;width:100%;" src=<?= isset($product['image']) && !empty($product['image']) ? "images/".$product['image'] : "https://images.unsplash.com/photo-1454789548928-9efd52dc4031?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80"; ?> class="card-img-top" alt="Eunicodin">
            </div>
            <div class="product_blog_cont">
              <h5 class="card-title"><?=$product['name']?></h5>
              <p class="card-text">Cantidad en stock: <?=$product['quantity']?></p>
            </div>
          </div>
        <?php endforeach;?>
      </div>
    <?php else:?>
      <div class="alert alert-primary" role="alert" style="width:100%;">
        <p>No hay productos por terminar en tu inventario :D</p>
        <a href="?page=products&action=" class="btn btn-primary" style="float:right;">Ver todos los productos</a>
      </div>
    <?php endif;?>
    </article>
  </section>
<?php
  require_once 'includes/footer.php';
?>