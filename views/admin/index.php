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
        <h5 class="card-title text-center">INGSHOP - RIF J-502226605</h5>
        <hr>
        <form class="p-4" id="formulario" autocomplete="off">
            <input type="hidden" id="id" name="id" value="<?php echo $data['empresa']['id']; ?>">
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>RIF <span class="text-danger">*</span></label>
                    <div class="input-group ">
                        <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                        <input type="number" id="rif" name="rif" class="form-control" value="<?php echo $data['empresa']['rif']; ?>" onkeypress="return SoloNumeros(event);" placeholder="Ingrese RIF" required>
                    </div>
                    <span id="errorRif" class="text-danger"></span>
                </div>
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Nombre <span class="text-danger">*</span></label>
                    <div class="input-group ">
                        <span class="input-group-text"> <i class="fa-solid fa-list"></i></span>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $data['empresa']['nombre']; ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre">
                    </div>
                    <span id="errorNombre" class="text-danger"></span>
                </div>
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Teléfono <span class="text-danger">*</span></label>
                    <div class="input-group ">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                        <input type="number" id="telefono" name="telefono" class="form-control" value="<?php echo $data['empresa']['telefono']; ?>" onkeypress="return SoloNumeros(event);" maxlength="12" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Num Telefono" required>
                    </div>
                    <span id="errorTelefono" class="text-danger"></span>
                </div>
                <div class="col-lg-6 col-sm-6 mb-2">
                    <label>Correo Electrónico <span class="text-danger">*</span></label>
                    <div class="input-group ">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?php echo $data['empresa']['correo']; ?>" placeholder="Correo" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}*." required>
                    </div>
                    <span id="errorCorreo" class="text-danger"></span>
                </div>
                <div class="col-lg-6 col-sm-6 mb-2">
                    <label>Dirección <span class="text-danger">*</span></label>
                    <div class="input-group ">
                        <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $data['empresa']['direccion']; ?>" placeholder="Direccion">
                    </div>
                    <span id="errorDireccion" class="text-danger"></span>
                </div>                
                <div class="col-lg-3 col-sm-6 mb-2">
                    <label>Impuesto (Opcional)</label>
                    <div class="input-group ">
                        <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
                        <input type="number" id="impuesto" name="impuesto" class="form-control" value="<?php echo $data['empresa']['impuesto']; ?>" onkeypress="return SoloNumeros(event);" placeholder="Ingrese Impuesto" required>
                    </div>
                </div>
                <div class="col-lg-9 col-sm-6 mb-2">
                    <div class="form-group">
                        <label for="mensaje">Mensaje (Opcional)</label>
                        <textarea id="mensaje" class="form-control" name="mensaje" rows="3" placeholder="Ingrese Mensaje"><?php echo $data['empresa']['mensaje']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button class="btn btn-primary" type="submit" id="btAccion">Actualizar</button>
            </div>
        </form>
    </div>
</div>


<?php include_once 'views/templates/footer.php'; ?>