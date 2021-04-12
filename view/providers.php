<?php
  require_once 'includes/header.php';
?>
<section>
    <article>
        <h2 id="title" style="margin-bottom:30px;">Tus proveedores</h2>
        <hr>
        <a href='?page=providers&action=newProvider' style="margin-bottom:20px;float:right;" class="btn btn-primary">Nuevo Proveedor</a>
    </article>
    <article>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>
                        <p>RFC</p>
                        <input type="text" class="form-control" id="filtroRFC" onkeyup="filter_proveedores('RFC')">
                    </th>
                    <th>
                        <p>Nombre comercial</p>
                        <input type="text" class="form-control" id="filtroComercial" onkeyup="filter_proveedores('comercial_name')">
                    </th>
                    <th>
                        <p>Tipo</p>
                        <input type="text" class="form-control" id="filtroTipo" onkeyup="filter_proveedores('type')">
                    </th>
                    <th>Teléfono</th>
                    <th>Calle</th>
                    <th>Colonia</th>
                    <th>Numero Exterior</th>
                    <th>Código Postal</th>
                    <th>
                        <p>Etiqueta</p>
                        <input type="text" class="form-control" id="filtroEtiqueta" onkeyup="filter_proveedores('tag')">
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="table-filter-provider">
            <?php if (count($providers) > 0): ?>
                <?php foreach ($providers as $index => $prov): ?>
                    <tr>
                        <td><?=($index + 1)?></td>
                        <td><?=$prov['RFC']?></td>
                        <td><?=$prov['comercial_name']?></td>
                        <td><?=$prov['type']?></td>
                        <td><?=$prov['phone']?></td>
                        <td><?=$prov['street']?></td>
                        <td><?=$prov['suburb']?></td>
                        <td><?=$prov['number']?></td>
                        <td><?=$prov['postal_code'] != 0 ? $prov['postal_code']: '<span class="no_disponible">No disponible</span>' ?></td>
                        <td><?=$prov['tag']?></td>
                        <td style='width:150px;'>
                            <a href="?page=providers&action=newProvider&parameter=<?=$prov['id']?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <!-- Button trigger modal -->
                            <a type="button" data-bs-toggle="modal" data-bs-target="#deleteModalProvider" class="deleteProviderData btn btn-danger" data-element="<?=$prov['id']?>"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                    <tr>
                        <td colspan="11" style="padding:0;margin:0"><div style="margin:0;" class="alert alert-primary col-md-12" role="alert">Aún no cuentas con proveedores</div></td>
                    </tr>
            <?php endif?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="deleteModalProvider" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Segura deseas eliminar este proveedor?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="successDataModal" role="alert"></div>
                        <p id="nameProviderDelete"></p>
                        <div class="dataClient">
                            <p>Productos pertenecientes: </p><b><span id="products_provider"></span></b>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">No quiero <i class="em em-white_frowning_face" aria-role="presentation" aria-label=""></i></a>
                        <a id="senDataProvider" href="" type="button" class="btn btn-primary" data="deleteProvider">Si, Eliminar <i class="em em-angry" aria-role="presentation" aria-label="ANGRY FACE"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
</section>
<?php
  require_once 'includes/footer.php';
?>