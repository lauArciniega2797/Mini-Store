<?php require_once 'includes/header.php';?>
<section>
    <h2 id="title" style="margin-bottom:30px;">Tus Ventas</h2>
    <hr>
    <article>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>
                        <p>Folio</p>
                        <input type="text" id="filtroFolio">
                    </th>
                    <th>
                        <p>Tipo</p>
                        <select name="status" id="filtroTipo">
                            <option value="">Selecciona...</option>
                            <option value="credito">Crèdito</option>
                            <option value="contado">Contado</option>
                        </select>
                    </th>
                    <th>
                        <p>Cliente</p>
                        <input type="text" id="filtroCliente">
                    </th>
                    <th>Subtotal</th>
                    <th>Total</th>
                    <th>Pagó con</th>
                    <th>
                        <p>Status</p>
                        <select name="status" id="filtroStatus">
                            <option value="">Selecciona...</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="pagada">Pagada</option>
                        </select>
                    </th>
                    <th>Productos</th>
                    <th>
                        <p>Fecha de creción</p>
                        <input type="text" id="filtrofecha">
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="table_sales"></tbody>
        </table>
        

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