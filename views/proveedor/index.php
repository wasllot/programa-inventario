<?php include_once 'views/templates/header.php'; ?>

<script> 

function SoloLetras(e)
{
key = e.keyCode || e.which;
tecla = String.fromCharCode(key).toString();
letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnopqrstuvwxyzáéíóú";

especiales = [8,13,32];
tecla_especial = false
for(var i in especiales) {
if(key == especiales[i]){
tecla_especial = true;
break;
}
}

if(letras.indexOf(tecla) == -1 && !tecla_especial)
{
alertaPersonalizada('error', 'INGRESE SOLO LETRAS');
return false;
}
}
function SoloNumeros(evt)
{
if(window.event){
keynum = evt.keyCode;
}
else{
keynum = evt.which;
}

if((keynum > 47 && keynum < 58) || keynum == 8 || keynum== 13)
{
return true;
}
else
{
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
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'proveedor/inactivos'; ?>"><i class="fas fa-trash text-danger"></i> Proveedores Inactivos</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-proveedores-tab" data-bs-toggle="tab" data-bs-target="#nav-proveedores" type="button" role="tab" aria-controls="nav-proveedores" aria-selected="true">Proveedores</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-proveedores" role="tabpanel" aria-labelledby="nav-proveedores-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fa-solid fa-user-tie"></i> Listado de Proveedores</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblProveedores" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Rif</th>
                                <th>Nombre</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th>Dirección</th>
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
                    <div class="row mb-3">
                    <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <label for="identidad">RIF <span class="text-danger">*</span></label>
                                <select id="identidad" class="form-control" name="identidad">
                                    <option value="">SELECCIONAR</option>
                                    <option value="J">J</option>
                                    <option value="G">G</option>
                                    <option value="N">N</option>
                                </select>
                            </div>
                            <span id="errorIdentidad" class="text-danger"></span>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="num_identidad">Rif <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="number" name="rif" id="rif" onkeypress="return SoloNumeros(event);" placeholder="RIF" maxlength="9" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                            </div>
                            <span id="errorRif" class="text-danger"></span>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="30" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="nombre" id="nombre" placeholder="Nombre" required>
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input class="form-control" type="number" name="telefono" id="telefono" onkeypress="return SoloNumeros(event);" maxlength="11" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Telefono" required>
                            </div>
                            <span id="errorTelefono" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo">Correo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input class="form-control" type="email" name="correo" id="correo" placeholder="Correo Electrónico" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}*." required>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="direccion">Dirreción <span class="text-danger">*</span></label>
                                <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Dirección"></textarea>
                            </div>
                            <span id="errorDireccion" class="text-danger"></span>
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