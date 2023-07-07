<?php
session_start();
//incluyendo conexión con bdd
include_once("dbconn.php");

//query para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $deletequery = query("DELETE FROM psi_data WHERE id = '{$_GET['id']}'");
    }
}
//query para mostrar la tabla
$query = query('SELECT psi_data.id, oil_wells.name, psi_data.psi, psi_data.dt FROM psi_data INNER JOIN oil_wells ON psi_data.oil_wells_id = oil_wells.id ORDER BY id');
$oil_wells_array = [];

if (mysqli_num_rows($query) > 0) {
    while ($auxArray = mysqli_fetch_array($query)) {
        array_push($oil_wells_array, $auxArray);
    }
}
//query del select en el form
$names = query('SELECT * FROM oil_wells');
$names_array = [];

if (mysqli_num_rows($names) > 0) {
    while ($auxArray2 = mysqli_fetch_array($names)) {
        array_push($names_array, $auxArray2);
    }
}

//insertando datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['datos'] = $_POST;
    header('Location: ' . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
    exit;
}

if (isset($_SESSION['datos'])) {
    $_POST = $_SESSION['datos'];
    if (isset($_POST['oil_wells_names']) && isset($_POST['namepsi']) && isset($_POST['nameEvent_dt']) && isset($_POST['id_psi_data_name'])) {
        $ow = $_POST['oil_wells_names'];
        $psi = $_POST['namepsi'];
        $event_dt = $_POST['nameEvent_dt'];
        $event_id_psi_data = $_POST['id_psi_data_name'];

        //chequeando si no hay otro igual
        $check = query("SELECT psi_data.id, oil_wells.id FROM psi_data INNER JOIN oil_wells ON psi_data.oil_wells_id = oil_wells.id WHERE psi_data.id = '$event_id_psi_data' AND oil_wells.id = '$ow'");
        if (mysqli_num_rows($check) > 0) {
            echo "Error: Ya existe un pozo con el mismo número y ubicación.";
        } else {
            //insertando
            $insertquery = query("INSERT INTO psi_data (id, oil_wells_id, psi, dt) VALUES (NULL, '$ow', '$psi','$event_dt')");

            if ($insertquery) {
                //mostrar la tabla nueva
                $query = query('SELECT psi_data.id, oil_wells.name, psi_data.psi, psi_data.dt FROM psi_data INNER JOIN oil_wells ON psi_data.oil_wells_id = oil_wells.id ORDER BY id');
                $oil_wells_array = [];

                if (mysqli_num_rows($query) > 0) {
                    while ($auxArray = mysqli_fetch_array($query)) {
                        array_push($oil_wells_array, $auxArray);
                    }
                }
            } else {
                echo "Error.";
            }
        }
    }
    unset($_SESSION['datos']);
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
                    <i>Bienvenido al Registro de Datos de Pozos Petroleros de PDVSA.</i>
                </h1>
            </div>
            <!-- instrucciones -->
            <div class="ms-2">
                <div class="col">
                    <h3>
                        Aquí podrás ver un <strong>registro de los datos</strong> de Nuestros Pozos Petroleros. Además,
                        podrás
                        <strong><u>registrar,editar o eliminar</u></strong> los datos que gustes de la tabla.
                    </h3>
                </div>
                <div class="col">
                    <h5>
                        Abajo tendrás disponible la opción <strong>"Graficas"</strong>, que te permitirá visualizar
                        <strong>gráficas
                            comparativas</strong> de los datos recogidos de nuestros pozos según su <strong>PSI en el
                            transcurso del tiempo.</strong>
                    </h5>
                </div>
            </div>
        </div>

        <!-- form para ingresar registros -->
        <div class="row d-flex flex-column">
            <div class="col">
                <h6>Crear un nuevo Registro:</h6>
            </div>
            <div class="col ms-3">
                <form class="needs-validation" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                    <!-- selecciona pozo -->
                    <div class="form-group">
                        <label class="form-label" for="namesid">Selecciona un pozo</label>
                        <?php if (count($names_array) > 0): ?>
                            <select class="form-select" aria-label="Default select example" name="oil_wells_names"
                                id="namesid" required>
                                <option selected disabled value="">Escoger Pozo</option>
                                <?php foreach ($names_array as $n): ?>
                                    <option value="<?= $n['id'] ?>"> <?= $n['name'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <h3>No se encontró nada.</h3>
                        <?php endif; ?>
                    </div>
                    <!-- ingresar PSI -->
                    <div class="form-group my-3">
                        <label class="form-label" for="psiId">Presion Actual (PSI): </label>
                        <input type="number" class="form-control" name="namepsi" id="psiid" step="0.01" min="0.01"
                            max="10000.00" placeholder="Digita del 0.01 al 10000.00" required>
                    </div>
                    <!-- selecciona Fecha y hora-->
                    <div class="form-group my-3">
                        <label class="form-label" for="dtid">Fecha y hora de registro:</label>
                        <input class="form-control" type="datetime-local" name="nameEvent_dt" id="dtid"
                            value="<?php echo date("Y-m-d\TH-i"); ?>"" required>
                        </div>
                        <!-- boton para registrar pozo -->
                        <div class=" form-group my-5">
                        <button type="submit" name="save_data" class="btn btn-primary">Registrar Datos</button>
                    </div>
                    <!-- dato oculto con el id del desde la tabla de registros (foreing key)-->
                    <div>
                        <?php if (count($oil_wells_array) > 0): ?>
                            <?php foreach ($oil_wells_array as $oil_wells): ?>
                                <input type="hidden" name="id_psi_data_name" value="<?= $oil_wells['id'] ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h3>No se encontró nada.</h3>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- boton para ver gráficas -->
        <div class="d-flex justify-content-end">
            <div class="my-2 me-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <a class="button" href="charts.php">Graficas</a>
                </button>
            </div>
        </div>

        <!-- Mostrando tabla con registros -->
        <div>
            <h6>Datos Registrados</h6>
        </div>
        <div>
            <table class="table">
                <?php if (count($oil_wells_array) > 0): ?> <!-- comprueba si hay registros -->
                    <!-- encabezado -->
                    <thead>
                        <tr class="head">
                            <td>Nº Identificación de Registro</td>
                            <td>Nombre del Pozo</td>
                            <td>PSI</td>
                            <td>Fecha y Hora</td>
                            <td colspan="2">Opciones</td>
                        </tr>
                    </thead>
                    <!-- Contenido -->
                    <tbody>
                        <!-- imprime los registros seleccionados en la query y almacenados en el array oil_wells_array-->
                        <?php foreach ($oil_wells_array as $oil_wells): ?>
                            <tr class="tabla">
                                <td>
                                    <?= $oil_wells['id'] ?>
                                </td>
                                <td>
                                    <?= $oil_wells['name'] ?>
                                </td>
                                <td>
                                    <?= $oil_wells['psi'] ?>
                                </td>
                                <td>
                                    <?= $oil_wells['dt'] ?>
                                </td>
                                <!-- Boton para editar registro -->
                                <td>
                                    <button type="submit" class="btn btn-outline-info">
                                        <a class="button" href="edit.php?id=<?php echo $oil_wells['id'] ?>">Editar</a>
                                    </button>
                                </td>
                                <!-- boton para eliminar registro -->
                                <td>
                                    <button type="submit" class="btn btn-outline-danger">
                                        <a class="button" href="index.php?id=<?php echo $oil_wells['id'] ?>">Eliminar</a>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php else: ?>
                    <h3>No se encontró nada.</h3>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>