<?php include_once 'views/templates/header.php'; ?>

<script> 
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
            <div>
                <h5 class="card-title text-center"><i class="fa-solid fa-cash-register"></i> Informes</h5>
            </div>            
        </div>       
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-apertura-tab" data-bs-toggle="tab" data-bs-target="#nav-apertura" type="button" role="tab" aria-controls="nav-apertura" aria-selected="true">Apertura y Cierre</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active mt-2" id="nav-apertura" role="tabpanel" aria-labelledby="nav-apertura-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblAperturaCierre" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Monto Inicial</th>
                                    <th>Fecha Apertura</th>
                                    <th>Fecha Cierre</th>
                                    <th>Monto Final</th>
                                    <th>Total Ventas</th>
                                    <th>Usuario</th>
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
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" id="id">
                                <label for="">Monto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input class="form-control" id="monto" type="number" step="0.01" min="0.01" name="monto" onkeypress="return SoloNumeros(event);" placeholder="Monto" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                                    <textarea id="descripcion" class="form-control" name="descripcion" rows="3" placeholder="Descripción"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label for="foto">Foto (Opcional)</label>
                                    <input id="foto" class="form-control" type="file" name="foto">
                                </div>
                                <div id="containerPreview">
                                </div>
                            </div>
                        </div>
                        <div class="float-end">
                            <button class="btn btn-primary" type="submit" id="btnRegistrarGasto">Registrar</button>
                        </div>
                    </form>

                </div>
                <div class="tab-pane fade p-3" id="nav-historial" role="tabpanel" aria-labelledby="nav-historial-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblGastos" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Monto</th>
                                    <th>Descripción</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade p-3" id="nav-movimientos" role="tabpanel" aria-labelledby="nav-movimientos-tab" tabindex="0">
                    <div class="d-flex align-items-center">
                        <div></div>
                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL . 'cajas/reporte'; ?>" target="_blank"><i class="fas fa-file-pdf text-danger"></i> Reporte</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-container-1">
                        <canvas id="reporteMovimiento"></canvas>
                    </div>
                    <ul class="list-group list-group-flush" id="listaMovimientos">
                    </ul>

                </div>
            </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>