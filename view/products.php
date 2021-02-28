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
                    <!-- Button trigger modal -->
                    <a type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" class="deleteProductData btn btn-danger" data-href="?page=products&action=getProductToDelete&id=<?=$product['id']?>">Eliminar</a>
                </div>
            </div>
        <?php endforeach;?>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura desdeas eliminar este producto?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <img id="imageProductDelet" style="width:100%;">
                        <p id="nameProductDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero :C</a>
                        <a id="senData" href="" type="button" class="btn btn-primary" data="deleteProduct">Si, Eliminar >:|</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>