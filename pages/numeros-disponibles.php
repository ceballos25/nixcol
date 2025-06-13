<?php
include_once '../config/config.php';
include_once '../backend/numeroDisponibleModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase NumeroDisponibleModel
$numeroDisponibleModel = new NumeroDisponibleModel($db);

// Buscar números disponibles
$numerosDisponibles = $numeroDisponibleModel->buscarNumerosDisponibles($search, $limit, $offset);

// Contar el total de números disponibles
$totalRecords = $numeroDisponibleModel->contarNumerosDisponibles($search);
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
          <h5 class="card-title fw-semibold mb-4">Números Disponibles</h5>
          <div class="card">
            <div class="card-body">
              <!-- Campo de búsqueda -->
              <form method="GET" action="">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Buscar número disponible" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
                </div>
              </form>
              <div class="table-responsive" id="numeros-disponibles-table">
                <!-- Aquí se cargarán los resultados de la búsqueda -->
                <table class="table table-striped table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col">Número</th>
                      <th scope="col">Estado</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($numerosDisponibles as $numero): ?>
                      <tr>
                        <td>
                      <span class="number"><?php echo htmlspecialchars($numero['numero']); ?></span>
                      </td>
                        <td>
                            <?php if ($numero['estado'] === 'Disponible'): ?>
                                <span class="badge bg-success"><?php echo htmlspecialchars($numero['estado']); ?></span>
                            <?php elseif ($numero['estado'] === 'Vendido'): ?>
                                <span class="badge bg-danger"><?php echo htmlspecialchars($numero['estado']); ?></span>
                            <?php elseif ($numero['estado'] === 'Reservado'): ?>
                                <span class="badge bg-info"><?php echo htmlspecialchars($numero['estado']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($numero['estado']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($numero['estado'] !== 'Vendido'): ?>
                                <button type="button" class="btn btn-outline-danger cambiar-estado-btn" 
                                        data-numero="<?php echo $numero['numero']; ?>" 
                                        data-estado="<?php echo $numero['estado']; ?>"> 
                                    <i class="ti ti-toggle-left"></i>
                                </button>
                            <?php endif; ?>
                        </td>
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

      <script src="<?php echo JS_PATH ?>/pages/numeros-disponibles.js"></script>
</body>

</html>