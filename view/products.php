<?php
// var_dump($productos);
  require_once 'includes/header.php';
?>
<section>
    <article>
        <h1>Productos de la tienda</h1>
        <div id="options_view">
            <div id="types_of_view">
                <a class="list_items" data-element="vista_list"><i class="fas fa-th-list"></i></a>
                <a class="list_items" data-element="vista_tarjetas"><i class="fas fa-th"></i></a>
            </div>
            <a href="?page=products&action=newProduct" class="btn btn-primary">Nuevo Producto</a>
        </div>
    </article>
    <hr>
    <article id="products">
        <div class="carousel-productos" id="vista_tarjetas">
        <?php foreach ($productos as $product): ?>
            <div class="card" style="width: 18rem;">
                <?php if($product['status'] == 'empty' || $product['status'] == 'warning'): ?>
                    <span style="background-color:<?= $product['status'] == 'empty' ? '#ec4646' : '#ffe268';?>;color:<?= $product['status'] == 'empty' ? 'white' : '';?>" class="status_info"><?= $product['status'] == 'empty' ? 'Agotado' : 'Por agotarse';?></span>
                <?php endif; ?>
                <img src=<?= $product['image'] == '' ? "https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80" : "images/".$product['image']; ?> class="card-img-top" alt="Eunicodin">
                <div class="card-body">
                    <div class="info_price_name">
                        <h5 class="card-title"><?=$product['name']?></h5>
                        <span class="card-text">$ <?=$product['store_price']?></span>
                    </div>
                    <p class="cant_info">Disponibles: <?=$product['quantity']?></p>
                    <div class="action-buttons">
                        <a href="?page=products&action=newProduct&parameter=<?=$product['id']?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <!-- Button trigger modal -->
                        <!-- <a type='button' id='deleteSaleData' onclick='eliminarVenta(".$sale['id'].")' data-bs-toggle='modal' data-bs-target='#deleteProductModal' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a>"; -->
                        <!-- <a type='button' id='deleteSaleData' onclick='eliminarProducto(< ?=$product["id"]?>)' data-bs-toggle='modal' data-bs-target='#deleteProductModal' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a> -->
                        <a type="button" href='javascript:void(0)' data-bs-toggle="modal" data-bs-target="#deleteProductModal" class="deleteDataProduct btn btn-danger" onclick="eliminarProducto(<?= $product['id']?>)"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>


        <div class="active" id="vista_list">
            <table class="table hover">
                <thead class="table-dark">
                    <tr>
                        <td>No.</td>
                        <td>imagen</td>
                        <td>
                            <p>Nombre</p>
                            <input type="text" class="form-control" id="filtroNombre" onkeyup="filter_products('name')">
                        </td>
                        <td>
                            <p>Precio</p>
                            <input type="text" class="form-control" id="filtroPrecio" onkeyup="filter_products('price')">
                        </td>
                        <td>Precio de proveedor</td>
                        <td>
                            <p>Proveedor</p>
                            <input type="text" class="form-control" id="filtroProveedor" onkeyup="filter_products('procedence_store')">
                        </td>
                        <td>
                            <p>Disponibles</p>
                            <input type="text" class="form-control" id="filtroDisponibles" value=1 >
                            <select name="status" id="filtroDisponiblesType" class="form-select" onchange="filter_products('quantity')">
                                <option value="">Selecciona...</option>
                                <option value=">">Mayor a</option>
                                <option value="<">Menor a</option>
                                <option value="=">Igual a</option>
                            </select>
                        </td>
                        <td>
                            <p>Status</p>
                            <select name="status" id="filtroStatus" class="form-select" onchange="filter_products('status')">
                                <option value="">Selecciona...</option>
                                <option value="full">Completo</option>
                                <option value="warning">Por agotarse</option>
                                <option value="empty">Agotado</option>
                            </select>
                        </td>
                        <td>Acciones</td>
                    </tr>
                </thead>
                <tbody id="table-filter-products">
                    <?php if(count($productos) > 0):?>
                        <?php foreach ($productos as $index => $product): ?>
                            <tr>
                                <td><?= $index + 1?></td>
                                <td><img height="50px" src=<?= $product['image'] == '' ? "https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80" : "images/".$product['image']; ?> class="card-img-top" alt="Eunicodin"></td>
                                <td><?=$product['name']?></td>
                                <td>$ <?=$product['store_price']?></td>
                                <td>$ <?=$product['price']?></td>
                                <td><?=$product['procedence_store']?></td>
                                <td><?=$product['quantity']?></td>
                                <?php if($product['status'] == 'full'):?>
                                    <td>Completo</td>
                                <?php elseif($product['status'] == 'warning'):?>
                                    <td>Por agotarse</td>
                                <?php else:?>
                                    <td>Agotado</td>
                                <?php endif;?>
                                <td>
                                    <a href="?page=products&action=newProduct&parameter=<?=$product['id']?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                    <!-- Button trigger modal -->
                                    <!-- onclick="delete_product(< ?= $product['id']?>)" -->
                                    <a type="button" href='javascript:void(0)' data-bs-toggle="modal" data-bs-target="#deleteProductModal" class="deleteDataProduct btn btn-danger" data-element="<?= $product['id']?>"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="9" style="padding:0;margin:0"><div style="margin:0;" class="alert alert-primary col-md-12" role="alert">Aún no cuentas con productos</div></td>
                        </tr>
                    <?php endif?>
                </tbody>
            </table>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero <i class="em em-white_frowning_face" aria-role="presentation" aria-label=""></i></a>
                        <a id="senData" href="" type="button" class="btn btn-primary" data="deleteProduct">Si, Eliminar <i class="em em-angry" aria-role="presentation" aria-label="ANGRY FACE"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>