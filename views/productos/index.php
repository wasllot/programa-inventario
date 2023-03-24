<?php include_once 'views/templates/header.php'; ?>
<script>
    function SoloNumeros(evt) {
        if (window.event) {
            keynum = evt.keyCode;
        } else {
            keynum = evt.which;
        }

        if ((keynum > 47 && keynum < 58) || keynum == 8 || keynum == 13) {
            return true;
        } else {
            alertaPersonalizada('error', 'INGRESE SOLO NUMEROS');
            return false;
        }
    }
</script>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'productos/inactivos'; ?>"><i class="fas fa-trash text-danger"></i> Productos Inactivos</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-productos-tab" data-bs-toggle="tab" data-bs-target="#nav-productos" type="button" role="tab" aria-controls="nav-productos" aria-selected="true">Productos</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-productos" role="tabpanel" aria-labelledby="nav-productos-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fa-solid fa-boxes-stacked"></i> Listado de Productos</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tblProductos" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Cantidad</th>
                                <th>Medida</th>
                                <th>Categoria</th>
                                <th>Foto</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade p-3" id="nav-nuevo" role="tabpanel" aria-labelledby="nav-nuevo-tab" tabindex="0">
                <form id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="foto_actual" name="foto_actual">
                    <div class="row mb-3">
                        <div class="col-md-3 mb-3">
                            <label for="codigo">Código <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input class="form-control" type="number" name="codigo" id="codigo" onkeypress="return SoloNumeros(event);" placeholder="Ingrese Código" maxlength="12" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                            </div>
                            <span id="errorCodigo" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="nombre" id="nombre" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="30" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ingrese Nombre">
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="precio_compra">Precio Compra <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="number" step="0.01" min="0.01" name="precio_compra" id="precio_compra" placeholder="Precio Compra" maxlength="5" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" required>
                            </div>
                            <span id="errorCompra" class="text-danger"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="precio_venta">Precio Venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="number" step="0.01" min="0.01" name="precio_venta" id="precio_venta" placeholder="Precio Venta" maxlength="5" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" required>
                            </div>
                            <span id="errorVenta" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="id_medida">Medida <span class="text-danger">*</span></label>
                                <select id="id_medida" class="form-control" name="id_medida">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['medidas'] as $medida) { ?>
                                        <option value="<?php echo $medida['id']; ?>"><?php echo $medida['medida']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span id="errorMedida" class="text-danger"></span>
                        </div>
                        <div class="col-md-5 mb-3">
                            <div class="form-group">
                                <label for="id_categoria">Categorias <span class="text-danger">*</span></label>
                                <select id="id_categoria" class="form-control" name="id_categoria">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['categorias'] as $categoria) { ?>
                                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span id="errorCategoria" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="foto">Foto (Opcional)</label>
                                <input id="foto" class="form-control" type="file" name="foto">
                            </div>
                            <div id="containerPreview">

                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-danger" type="button" id="btnNuevo">Nuevo</button>
                        <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>