<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
require_once('../config/config.php');
require_once('../backend/clienteModel.php');

// Parámetros de búsqueda y paginación
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Crear una instancia de la clase ClienteModel
$clienteModel = new ClienteModel($db);

// Buscar clientes
$clientes = $clienteModel->buscarClientes($search, $limit, $offset);

// Contar el total de clientes
$totalRecords = $clienteModel->contarClientes($search);
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
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="<?php echo IMG_PATH; ?>profile/user-1.jpg" alt="" width="35" height="35"
                    class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                  aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="/dash/salir.php"
                      class="btn btn-outline-primary mx-3 mt-2 d-block">Salir</a>
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
          <h5 class="card-title fw-semibold mb-4">Mis Clientes</h5>
          <div class="card">
            <div class="card-body">
              <!-- Campo de búsqueda -->
              <form method="GET" action="">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Buscar cliente" name="search"
                    id="search" value="<?php echo htmlspecialchars($search); ?>">
                </div>
              </form>
              <div class="table-responsive" id="clientes-table">
                <!-- Aquí se cargarán los resultados de la búsqueda -->
                <table class="table table-striped table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Cliente</th>
                      <th scope="col">Celular</th>
                      <th scope="col">Correo</th>
                      <th scope="col">Depto</th>
                      <th scope="col">Ciudad</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['celular']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['departamento']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['ciudad']); ?></td>
                        <td>
                          <button type="button" class="btn btn-outline-primary edit-btn"
                            data-id="<?php echo $cliente['id']; ?>"
                            data-nombre="<?php echo htmlspecialchars($cliente['nombre']); ?>"
                            data-celular="<?php echo htmlspecialchars($cliente['celular']); ?>"
                            data-correo="<?php echo htmlspecialchars($cliente['correo']); ?>"
                            data-departamento="<?php echo htmlspecialchars($cliente['departamento']); ?>"
                            data-ciudad="<?php echo htmlspecialchars($cliente['ciudad']); ?>"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="ti ti-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-outline-danger delete-btn"
                            data-id="<?php echo $cliente['id']; ?>">
                            <i class="ti ti-trash"></i>
                          </button>
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

      <!-- Modal para editar cliente -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Editar Cliente</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <div class="mb-3">
                  <label for="editNombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="editNombre" name="nombre" required>
                </div>
                <div class="mb-3">
                  <label for="editCelular" class="form-label">Celular</label>
                  <input type="text" class="form-control" id="editCelular" name="celular" required>
                </div>
                <div class="mb-3">
                  <label for="editCorreo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="editCorreo" name="correo" required>
                </div>
                <!-- <div class="mb-3">
                  <label for="editDepartamento" class="form-label">Departamento</label>
                  <input type="text" class="form-control" id="editDepartamento" name="departamento" required>
                </div>
                <div class="mb-3">
                  <label for="editCiudad" class="form-label">Ciudad</label>
                  <input type="text" class="form-control" id="editCiudad" name="ciudad" required>
                </div> -->
                <button type="submit" class="btn btn-primary">
                  <span class="spinner-border spinner-border-sm d-none" role="status"
                    aria-hidden="true"></span>
                  Guardar Cambios
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">&copy; Nixcol</a></p>
      </div>
      <!--  Footer Start -->

      <?php include_once INCLUDE_PATH . 'footer.php'; ?>

      <script src="<?php echo JS_PATH ?>/pages/clientes.js"></script>
</body>

</html>