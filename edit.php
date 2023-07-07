<?php
//incluyendo conexión con bdd
include_once("dbconn.php");

//query del select en el form
$query = query('SELECT * FROM oil_wells');
$names_array = [];

if (mysqli_num_rows($query) > 0) {
    while ($auxArray = mysqli_fetch_array($query)) {
        array_push($names_array, $auxArray);
    }
}
// query para buscar el registro seleccionado en la tabla de registros de index.php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $query = query("SELECT psi_data.id, psi_data.oil_wells_id, oil_wells.name, psi_data.psi, psi_data.dt FROM psi_data INNER JOIN oil_wells ON psi_data.oil_wells_id = oil_wells.id WHERE psi_data.id = '{$_GET['id']}'");
    $oil_wells = mysqli_fetch_assoc($query);
}

// haciendo el update con los datos del form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $own = $_POST['oil_wells_names'];
    $npsi = $_POST['namepsi'];
    $nE_dt = $_POST['nameEvent_dt'];

    $query = query("UPDATE psi_data SET oil_wells_id='$own', psi='$npsi', dt='$nE_dt' WHERE id = '$id'");

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pozos Petroleros</title>
    <!-- boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- css -->
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <!-- iconos de boostrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <!-- nav -->
    <nav class="navbar sticky-top bg-danger" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="bi bi-fuel-pump"></i> Registro de Datos de Pozos
                Petroleros de PDVSA</a>
        </div>
    </nav>
    <!-- main -->
    <div class="container">
        <div class="row d-flex flex-column my-3">
            <!-- titulo del archivo -->
            <div class="col">
                <h1>
                    <i>Editar</i>
                </h1>
            </div>
            <!-- instrucciones -->
            <div class="ms-2">
                <div class="col">
                    <h3>
                        Aquí podrás <strong>Editar el registro</strong> que has seleccionado.
                        <strong><u>Si te has equivocado o deseas corregir los datos de un registro</u></strong> aquí
                        podrás hacerlo facilmente.
                    </h3>
                </div>
                <div class="col">
                    <h5>
                        Abajo se muestran las opciones disponibles para editar, una vez que hayas finalizado los
                        cambios, presiona la opción <strong>"Actualizar"</strong> para guardar las modificaciones.
                    </h5>
                </div>
            </div>
        </div>

        <!-- form para actulizar el registro seleccionado -->
        <div>
            <div class="row d-flex flex-column">
                <div class="col">
                    <h6>Actualizar registro:</h6>
                </div>

                <div class="col ms-3">
                    <form method="post">

                        <!-- dato oculto con id del pozo -->
                        <div>
                            <input type="hidden" name="id" value="<?php echo $oil_wells['id']; ?>">
                        </div>

                        <!-- selecciona pozo actualizado-->
                        <div class="form-group">
                            <label class="form-label" for="namesid">Selecciona un pozo</label>
                            <?php if (count($names_array) > 0): ?>
                                <select class="form-select" aria-label="Default select example" name="oil_wells_names"
                                    id="namesid" required>
                                    <option value="<?php echo $oil_wells['oil_wells_id']; ?>"><?php echo $oil_wells['name']; ?></option>
                                    <?php foreach ($names_array as $n): ?>
                                        <?php if ($n['id'] != $oil_wells['oil_wells_id']): ?>
                                            <option value="<?php echo $n['id']; ?>"><?php echo $n['name']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <h3>No se encontró nada.</h3>
                            <?php endif; ?>
                        </div>
                        
                        <!-- ingresar PSI actualizado-->
                        <div class="form-group my-3">
                            <label class="form-label" for="psiId">Presion Actual (PSI): </label>
                            <input type="number" class="form-control" name="namepsi" id="psiid" step="0.01" min="0.01"
                                max="10000.00" placeholder="Digita del 0.01 al 10000.00"
                                value="<?php echo $oil_wells['psi']; ?>" required>
                        </div>

                        <!-- selecciona Fecha y hora actualizada-->
                        <div class="form-group my-3">
                            <label class="form-label" for="dtid">Fecha y hora de registro:</label>
                            <input class="form-control" type="datetime-local" name="nameEvent_dt" id="dtid"
                                value="<?php echo $oil_wells['dt']; ?>" required>
                        </div>

                        <!-- boton para actualizar registro -->
                        <div class=" form-group my-5">
                            <button type="submit" name="save_data" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>