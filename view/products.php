<?php
// var_dump($productos);
  require_once 'includes/header.php';
?>
<section id="main">
    <article>
        <h1>Productos de la tienda</h1>
    </article>
    <article id="products">
        <div class="carousel-productos">
        <?php foreach ($productos as $product): ?>
            <div class="card" style="width: 18rem;">
                <img src=<?= $product['image'] == '' ? "https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80" : "images/".$product['image']; ?> class="card-img-top" alt="Eunicodin">
                <div class="card-body">
                <h5 class="card-title"><?=$product['name']?></h5>
                <p class="card-text"><?=$product['store_price']?></p>
                <a href="?page=products&action=newProduct&parameter=<?=$product['id']?>" class="btn btn-primary">Editar</a>
                <a href="?page=products&action=delProduct&parameter=<?=$product['id']?>" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        <?php endforeach;?>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>
    
    </article>
</section>
<?php
  require_once 'includes/footer.php';
?>