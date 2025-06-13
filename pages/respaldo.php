<?php
include_once '../config/config.php';
include_once '../backend/respaldoModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase RespaldoModel
$respaldoModel = new RespaldoModel($db);

// Buscar registros de respaldo
$respaldo = $respaldoModel->buscarRespaldo($search, $limit, $offset);

// Contar el total de registros de respaldo
$totalRecords = $respaldoModel->contarRespaldo($search);
$totalPages = ceil($totalRecords / $limit);

// Crear una instancia de la clase Paginator
$paginator = new Paginator($totalRecords, $limit, $page);
?>

<?php include_once INCLUDE_PATH . 'head.php'; ?>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <?php include_once INCLUDE_PATH . 'sliderbar.php'; ?>

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="<?php echo IMG_PATH; ?>profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="/dash/salir.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Salir</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->

      <div class="container-fluid">

        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Respaldo</h5>
          <div class="card">
            <div class="card-body">
              <!-- Campo de búsqueda -->
              <form method="GET" action="">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Buscar respaldo" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
                </div>
              </form>
              <div class="table-responsive" id="respaldo-table">
                <!-- Aquí se cargarán los resultados de la búsqueda -->
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Cliente</th>
                      <th scope="col">Celular</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Depto</th>
                      <th scope="col">Ciudad</th>
                      <th scope="col">Números</th>
                      <th scope="col">Pago</th>
                      <th scope="col">Transacción</th>
                      <th scope="col">Fecha</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($respaldo as $registro): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($registro['id']); ?></td>
                      <td><?php echo htmlspecialchars($registro['cliente']); ?></td>
                        <td>
                            <a target="_black" href="https://wa.me/57<?php echo htmlspecialchars($registro['celular']); ?>?text<?php echo htmlspecialchars($registro['celular']); ?>">
                                <?php echo htmlspecialchars($registro['celular']); ?>
                            </a>
                        </td>
                      <td><?php echo htmlspecialchars($registro['correo']); ?></td>
                      <td><?php echo htmlspecialchars($registro['departamento']); ?></td>
                      <td><?php echo htmlspecialchars($registro['ciudad']); ?></td>
                      <td><?php echo htmlspecialchars($registro['oportunidades']); ?></td>
                      <td><?php echo '$' . number_format($registro['pago'], 0, ',', '.'); ?></td>                      <td><?php echo htmlspecialchars($registro['id_transaccion']); ?></td>
                      <td><?php echo htmlspecialchars(date('d/m/Y h:i A', strtotime($registro['fecha']))); ?></td>

                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- Paginación -->
              <div id="pagination-links">
                <?php echo $paginator->createLinks(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">&copy; Nixcol</a></p>
      </div>
      <!--  Footer Start -->

      <?php include_once INCLUDE_PATH . 'footer.php'; ?>

      <script src="<?php echo JS_PATH ?>/pages/respaldo.js"></script>
</body>

</html>