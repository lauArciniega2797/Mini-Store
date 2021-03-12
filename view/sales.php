<?php
// var_dump($clienos);
  require_once 'includes/header.php';
?>
<section id="main">
    <article>
        <h1>Tus clientes</h1>
    </article>
    <article id="products">
        <div class="carousel-productos">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>E-mail</th>
                        <th>Teléfono</th>
                        <th>Crédito aprobado</th>
                        <th>Limite de crédito</th>
                        <th>Días de crédito</th>
                        <th>Referencia bancaria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($client as $clien): ?>
                    <tr>
                        <td><?=$clien['id']?></td>
                        <td><?=$clien['name']?></td>
                        <td><?=$clien['email']?></td>
                        <td><?=$clien['phone']?></td>
                        <td><?=$clien['approved_credit']?></td>
                        <td><?=$clien['credit_limit']?></td>
                        <td><?=$clien['credit_days']?></td>
                        <td><?=$clien['bank_reference']?></td>
                        <td><a href="?page=clients&action=newClient&parameter=<?=$clien['id']?>" class="btn btn-primary">Editar</a>
                    <!-- Button trigger modal -->
                    <a type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" class="deleteProductData btn btn-danger" data-href="?page=clients&action=getClientToDelete&id=<?=$clien['id']?>">Eliminar</a></td>
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
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura deseas eliminar este cliente?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <img id="imageProductDelet" style="width:100%;">
                        <p id="nameProductDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero :C</a>
                        <a id="sendClient" href="" type="button" class="btn btn-primary" data="deleteClient">Si, Eliminar >:|</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>