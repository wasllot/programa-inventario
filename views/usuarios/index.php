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
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'usuarios/inactivos'; ?>"><i class="fa-solid fa-trash text-danger"></i> Usuarios Inactivos </a>
                    </li>
                </ul>
            </div>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-usuarios-tab" data-bs-toggle="tab" data-bs-target="#nav-usuarios" type="button" role="tab" aria-controls="nav-usuarios" aria-selected="true">Usuarios</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-usuarios" role="tabpanel" aria-labelledby="nav-usuarios-tab" tabindex="0">
                <h5 class="card-title text-center"> <i class="fa-solid fa-users-line"></i> Lista de Usuarios</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hove nowrap" id="tblUsuarios" style="width: 100%;">
                        <thead>
                            <tr>

                                <th>Nombres</th>
                                <th>Ci</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Rol</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-nuevo" role="tabpanel" aria-labelledby="nav-nuevo-tab" tabindex="0">
                <!--CREANDO FORMULARIO-->
                <form class="p-4" id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Ci</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                                <input type="number" id="ci" name="ci" class="form-control" onkeypress="return SoloNumeros(event);" placeholder="Ingrese C.I" maxlength="8" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                            </div>
                            <span id="errorCi" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Nombres</label>
                            <div class="input-group ">
                                <span class="input-group-text"> <i class="fa-solid fa-list"></i></span>
                                <input type="text" onkeypress="return SoloLetras(event);" id="nombres" name="nombres" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="20" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ingrese Nombres" required>
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Apellidos</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-list"></i></span>
                                <input type="text" onkeypress="return SoloLetras(event);" id="apellidos" name="apellidos" class="form-control" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="20" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ingrese Apellidos" required>
                            </div>
                            <span id="errorApellido" class="text-danger"></span>
                        </div>
                        <div class="col-lg-8 col-sm-6 mb-2">
                            <label>Correo Electrónico</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}*." required>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Teléfono</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="number" id="telefono" name="telefono" class="form-control" onkeypress="return SoloNumeros(event);" maxlength="11" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Num Telefono" required>
                            </div>
                            <span id="errorTelefono" class="text-danger"></span>
                        </div>
                        <div class="col-lg-8 col-sm-6 mb-2">
                            <label>Dirección</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Direccion" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}*." required>
                            </div>
                            <span id="errorDireccion" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Contraseña</label>
                            <div class="input-group ">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Ingrese Contraseña">
                            </div>
                            <span id="errorContraseña" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Perfil</label>
                            <div class="input-group ">
                                <label class="input-group-text" for="rol"><i class="fa-solid fa-user"></i></label>
                                <select class="form-select" id="rol" name="rol">
                                    <option value="" selected>Seleccionar</option>
                                    <option value="1">ADMINISTRADOR</option>
                                    <option value="2">VENDEDOR</option>
                                </select>
                            </div>
                            <span id="errorRol" class="text-danger"></span>
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