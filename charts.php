<?php
session_start();
//incluyendo conexión con bdd
include_once("dbconn.php");

// inicializando array para el gráfico
$chartArray = [];
$dataPoints = array(
    array("label" => 0, "y" => 0),
);
//query para mostrar la tabla
$query = query('SELECT * FROM oil_wells');
$names_array = [];

if (mysqli_num_rows($query) > 0) {
    while ($auxArray = mysqli_fetch_array($query)) {
        array_push($names_array, $auxArray);
    }
}
// para reiniciar totalmente la pagina
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['datos'] = $_POST;
    header('Location: ' . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
    exit;
}
// si el usuario selecciona un pozo, se creará el gráfico de ese pozo
if (isset($_SESSION['datos'])) {
    $_POST = $_SESSION['datos'];
    if (isset($_POST['oil_wells_names'])) {
        $ow = $_POST['oil_wells_names'];
        $owname = $names_array[$ow - 1]['name'];

        $queryChart = query("SELECT psi_data.id, psi_data.oil_wells_id, oil_wells.name, psi_data.psi, psi_data.dt FROM psi_data INNER JOIN oil_wells ON psi_data.oil_wells_id = oil_wells.id WHERE oil_wells_id = '$ow' ORDER BY dt ASC;");
        $count = 0;

        if (mysqli_num_rows($queryChart) > 0) {
            while ($row = mysqli_fetch_array($queryChart)) {
                $chartArray[$count]["y"] = $row["psi"];
                $chartArray[$count]["label"] = $row["dt"];
                $count++;
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

    <!-- JS script para mostra la tabla -->
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                //theme: "light2",
                axisX: {
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "Cantidad de presión (PSI)",
                    includeZero: true,
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($chartArray, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
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
                    <i>Gráficas</i>
                </h1>
            </div>
            <!-- instrucciones -->
            <div class="ms-2 mb-3">
                <div class="col">
                    <h3>
                        Aquí podrás <strong>visualizar gráficas</strong> de cada pozo
                        <strong><u>segun su PSI en el transcurso del tiempo.</u></strong> la información está basada en
                        los registros disponibles.
                    </h3>
                </div>
                <div class="col">
                    <h5>
                        Para mostrar la gráfica, deberás escoger uno de los pozos desplegando la opción <strong>"Escoger
                            Pozo"</strong>, selecciona el que gustes y por ultimo presiona el botón
                        <strong>"Mostrar"</strong>.
                    </h5>
                </div>
            </div>

            <!-- form para seleccionar un pozo -->
            <div class="ms-3">
                <form class="needs-validation" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="namesid">Selecciona un pozo:</label>
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
                    <!-- boton para mostrar gráficas de pozo seleccionado -->
                    <div class=" form-group my-3">
                        <button type="submit" name="save_data" class="btn btn-success">Mostrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mostrar gráfica -->
        <div>
            <!-- comprueba si hay registros -->
            <?php if (count($chartArray) > 0): ?>
                <div>
                    <h2 class="text-center">
                        Histórico de Pozo
                        <?= $owname ?>
                    </h2>
                </div>
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
            <!-- Muestra un mensaje si no hay registros -->
            <?php else: ?>
                <div class="mt-3">
                    <div class="alert alert-warning text-center"" role=" alert">
                        (AUN NO HAY DATOS DISPONIPLES)
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>