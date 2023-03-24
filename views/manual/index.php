<?php include_once 'views/templates/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="card radius-10">
        <div class="card-body">
            <div>
                <h5 class="card-title">Manual del Sistema PROG.ING.SHOP</h5>
            </div>
            <hr />
            <div class="row row-cols-auto g-3">
                <div class="col">
                    <a href="views\manual\Manual.pdf" target="_blank">
                    <button type="button" class="btn btn-info px-5" id="btnAccion"><i class='bx bx-cloud-download mr-1'></i>DESCARGAR</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php include_once 'views/templates/footer.php'; ?>