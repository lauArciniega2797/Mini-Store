<?php
// var_dump($productos);
  require_once 'includes/header.php';
?>
<section>
    <article>
        <h1>Deudores de la tienda</h1>
    </article>
    <hr>
    <article>
        <table class="table hover">
            <thead class="table-dark">
                <tr>
                    <td>No.</td>
                    <td>
                        <p>Nombre</p>
                        <input type="text" class="form-control" id="filtroNombre" onkeyup="">
                    </td>
                    <td>
                        <p>Total Deuda</p>
                        <!-- <input type="text" class="form-control" id="filtroPrecio" onkeyup=""> -->
                    </td>
                    <td>Restante</td>
                    <td>Total abonos</td>
                    <td>Ventas al cliente</td>
                    <td>Estatus</td>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody id="debtors_table"></tbody>
        </table>

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