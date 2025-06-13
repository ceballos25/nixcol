<?php
include_once '../config/config.php';
include_once '../backend/ventaModel.php';

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase VentaModel
$ventaModel = new VentaModel($db);

// Buscar ventas
$ventas = $ventaModel->buscarVentas($search, $limit, $offset);

// Contar el total de ventas
$totalRecords = $ventaModel->contarVentas($search);
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
          <h5 class="card-title fw-semibold mb-4">Detalle de Ventas</h5>
          <div class="card">
            <div class="card-body">
              <!-- Campo de búsqueda -->
              <form method="GET" action="">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Buscar venta" name="search" id="search" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                </div>
              </form>
              <div class="table-responsive" id="ventas-table">
                <!-- Aquí se cargarán los resultados de la búsqueda -->
                <table class="table table-striped table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Cliente</th>
                      <th scope="col">Celular</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Números</th>
                      <th scope="col">Pago</th>
                      <th scope="col">Tipo</th>
                      <th scope="col">Vendedor</th>
                      <th scope="col">Fecha</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($ventas as $venta): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($venta['id']); ?></td>
                      <td><?php echo htmlspecialchars($venta['cliente_nombre']); ?></td>
                      <td><?php echo htmlspecialchars($venta['cliente_celular']); ?></td>
                      <td><?php echo htmlspecialchars($venta['cliente_correo']); ?></td>
                      <td><?php echo htmlspecialchars($venta['total_numeros']); ?></td>
                      <td><?php echo '$' . number_format($venta['total_pago'], 0, ',', '.'); ?></td>
                      <td>
                          <?php if ($venta['tipo'] === 'PW'): ?>
                              <span class="badge bg-primary"><?php echo htmlspecialchars($venta['tipo']); ?></span>
                          <?php elseif ($venta['tipo'] === 'VM'): ?>
                              <span class="badge bg-secondary"><?php echo htmlspecialchars($venta['tipo']); ?></span>
                          <?php else: ?>
                              <?php echo htmlspecialchars($venta['tipo']); ?>
                          <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge <?php echo ($venta['vendedor'] === 'VF') ? 'bg-danger' : 'bg-success'; ?>">
                          <?php echo htmlspecialchars($venta['vendedor']); ?>
                        </span>
                      </td>
                      <td><?php echo htmlspecialchars(date('d/m/Y h:i A', strtotime($venta['fecha']))); ?></td>
                      <td>
                      <button type="button" class="btn btn-outline-primary mx-1" onclick="confirmarEnvio('<?php echo urlencode($venta['id']); ?>', '<?php echo urlencode($venta['cliente_celular']); ?>', '<?php echo urlencode($venta['cliente_nombre']); ?>', '<?php echo urlencode($venta['cliente_correo']); ?>')">
                          <i class="ti ti-send"></i>
                      </button>

                      </td>
                      <td>
                      <button type="button" class="btn btn-outline-danger mx-1" onclick="confirmarAnulacion(
                            '<?php echo urlencode($venta['id']); ?>', 
                            '<?php echo urlencode($venta['cliente_celular']); ?>', 
                            '<?php echo urlencode($venta['cliente_nombre']); ?>', 
                            '<?php echo urlencode($venta['cliente_correo']); ?>'
                        )">
                            <i class="ti ti-ban"></i>
                        </button>
                      </td>


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

      <script src="<?php echo JS_PATH ?>/pages/detalle-ventas-v2.js"></script>
</body>

</html>

