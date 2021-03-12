<?php
// var_dump($providers);
  require_once 'includes/header.php';
?>
<section id="main">
    <article>
        <h1>Tus proveedores</h1>
    </article>
    <article id="products">
        <div class="carousel-productos">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>RFC</th>
                        <th>Nombre comercial</th>
                        <th>Tipo</th>
                        <th>Teléfono</th>
                        <th>Calle</th>
                        <th>Colonia</th>
                        <th>Numero Exterior</th>
                        <th>Código Postal</th>
                        <th>Etiqueta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($providers as $prov): ?>
                    <tr>
                        <td><?=$prov['id']?></td>
                        <td><?=$prov['RFC']?></td>
                        <td><?=$prov['comercial_name']?></td>
                        <td><?=$prov['type']?></td>
                        <td><?=$prov['phone']?></td>
                        <td><?=$prov['street']?></td>
                        <td><?=$prov['suburb']?></td>
                        <td><?=$prov['number']?></td>
                        <td><?=$prov['postal_code']?></td>
                        <td><?=$prov['tag']?></td>
                        <td><a href="?page=providers&action=newProvider&parameter=<?=$prov['id']?>" class="btn btn-primary">Editar</a>
                    <!-- Button trigger modal -->
                    <a type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" class="deleteProductData btn btn-danger" data-href="?page=providers&action=getProviderToDelete&id=<?=$prov['id']?>">Eliminar</a></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura deseas eliminar este proveedor?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <img id="imageProductDelet" style="width:100%;">
                        <p id="nameProductDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero :C</a>
                        <a id="sendProvider" href="" type="button" class="btn btn-primary" data="deleteProvider">Si, Eliminar >:|</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>