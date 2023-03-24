<?php include_once 'views/templates/header.php'; ?>

<script> 

function SoloLetras(e)
{
key = e.keyCode || e.which;
tecla = String.fromCharCode(key).toString();
letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnopqrstuvwxyzáéíóú";

especiales = [8,13];
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

</script>

<div class="card">
    <div class="card-body">
    <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'categorias/inactivos'; ?>"><i class="fa-solid fa-trash text-danger"></i> Categorias Inactivas </a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-categorias-tab" data-bs-toggle="tab" data-bs-target="#nav-categorias" type="button" role="tab" aria-controls="nav-categorias" aria-selected="true">Categorias</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-categorias" role="tabpanel" aria-labelledby="nav-categorias-tab" tabindex="0">
                <h5 class="card-title text-center"> <i class="fa-solid fa-tags"></i> Lista de Categorias </h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hove nowrap" id="tblCategorias" style="width: 100%;">
                        <thead>
                            <tr>

                                <th>Nombre</th>
                                <th>Fecha</th>
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
                        <div class="col-md-12">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" onkeypress="return SoloLetras(event);" name="nombre" id="nombre" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="Ingrese Categoria" required>
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
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