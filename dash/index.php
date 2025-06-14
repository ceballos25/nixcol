<?php
include_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $database = new Database();
    $db = $database->getConnection();

    $query = new QuerySelect();
    $query->select('*')
        ->from('usuarios')
        ->where("usuario = :usuario")
        ->limit(1);

    $stmt = $db->prepare($query->getQuery());
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($contrasena, $row['contrasena'])) {
            // Contraseña correcta, devuelve los datos como JSON
            $response = [
                'success' => true,
                'nombre' => $row['nombre'],
                'usuario' => $row['usuario']
            ];
            echo json_encode($response);
            exit();
        } else {
            // Contraseña incorrecta
            $response = [
                'success' => false,
                'error' => 'Contraseña incorrecta.'
            ];
            echo json_encode($response);
            exit();
        }
    } else {
        // Usuario no existe
        $response = [
            'success' => false,
            'error' => 'El usuario no existe.'
        ];
        echo json_encode($response);
        exit();
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="../img/nix-sf.png" width="180" alt="">
                                </a>
                                <p class="text-center">&copy; Nixcol </p>

                                <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                                <form id="login-form">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Usuario</label>
                                        <input type="text" name="usuario" class="form-control" id="usuario"
                                            aria-describedby="emailHelp" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="contrasena" class="form-label">Contraseña</label>
                                        <input type="password" name="contrasena" class="form-control" id="contrasena"
                                            required>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" value=""
                                                id="flexCheckChecked" checked>
                                            <label class="form-check-label text-dark" for="flexCheckChecked">
                                                Recordar este dispositivo
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-dark w-100 py-8 fs-4 mb-4 rounded-2">Vamos</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#login-form').submit(function (event) {
                event.preventDefault();
                var usuario = $('#usuario').val();
                var contrasena = $('#contrasena').val();

                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: { usuario: usuario, contrasena: contrasena },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            localStorage.setItem('nombre', response.nombre);
                            localStorage.setItem('usuario', response.usuario);
                            window.location.href = 'dash.php'; // Redirige al dashboard
                        } else {
                            $('#error-message').text(response.error).show();
                        }
                    },
                    error: function () {
                        $('#error-message').text('Error al procesar la solicitud.').show();
                    }
                });
            });
        });
    </script>
</body>

</html>