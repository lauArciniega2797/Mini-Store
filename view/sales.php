<?php require_once 'includes/header.php';?>
<section id="main">
    <article>
        <h1>Tus ventas</h1>
    </article>
    <article id="products">
        <div class="carousel-productos">
            <table style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Fecha de creción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="table_sales"></tbody>
            </table>
            <!-- <a href="" class="btn btn-primary" id="productstofinish">Ver mas productos por terminar</a> -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura deseas eliminar esta venta?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <p>Folio: <span id="folioSale"></span></p>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero :C</a>
                        <a id="senDataSale" href="" type="button" class="btn btn-primary" data="deleteSale">Si, Eliminar >:|</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>