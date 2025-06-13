<?php

include_once '../config/config.php';
include_once DOCUMENT_ROOT . '/backend/queryDash.php';

// Crear una instancia de la clase DashboardQueries
$dashboardQueries = new DashboardQueries($db);

// Obtener los datos del dashboard
$totalClientes = $dashboardQueries->getTotalClientes();
$totalVentas = $dashboardQueries->getTotalVentas();
$totalPagos = $dashboardQueries->getTotalPagos();
$totalNumerosVendidos = $dashboardQueries->getTotalNumerosVendidos();
$faltanXvender = $dashboardQueries->getFaltanxVender();
$faltanXvender = $dashboardQueries->getFaltanxVender();

// Obtener los datos de ventas por tipo 'PW' para hoy
$ventasHoyPW = $dashboardQueries->getVentasPorTipoHoyPW('PW');
$totalVentasHoyPW = $ventasHoyPW['total_ventas'] ?? 0;
$totalNumerosHoyPW = $ventasHoyPW['total_numeros'] ?? 0;
$totalDineroHoyPW = $ventasHoyPW['total_dinero'] ?? 0;

$ventasHoyVM = $dashboardQueries->getVentasPorTipoHoyVM('VM');
$totalVentasHoyVM = $ventasHoyVM['total_ventas'] ?? 0;
$totalNumerosHoyVM = $ventasHoyVM['total_numeros'] ?? 0;
$totalDineroHoyVM = $ventasHoyVM['total_dinero'] ?? 0;

//CONSOLIDADO HOY
$consolidadoVentasHoy = $totalVentasHoyPW + $totalVentasHoyVM;
$consolidadoTotalNumerosHoy = $totalNumerosHoyPW + $totalNumerosHoyVM;
$consolidadoTotalDineroHoy = $totalDineroHoyPW + $totalDineroHoyVM;


// Obtener los datos de ventas por tipo 'PW' general
$ventasGeneralPW = $dashboardQueries->getVentasPorTipoHoyGeneral('PW');
$totalVentasGeneralPW = $ventasGeneralPW['total_ventas'] ?? 0;
$totalNumerosGeneralPW = $ventasGeneralPW['total_numeros'] ?? 0;
$totalDineroGeneralPW = $ventasGeneralPW['total_dinero'] ?? 0;

// Obtener los datos de ventas por tipo 'vm' general
$ventasGeneralVM = $dashboardQueries->getVentasPorTipoHoyGeneral('VM');
$totalVentasGeneralVM = $ventasGeneralVM['total_ventas'] ?? 0;
$totalNumerosGeneralVM = $ventasGeneralVM['total_numeros'] ?? 0;
$totalDineroGeneralVM = $ventasGeneralVM['total_dinero'] ?? 0;

//CONSOLIDADO GENERAL
$consolidadoVentasGeneral = $totalVentasGeneralPW + $totalVentasGeneralVM;
$consolidadoTotalNumerosGeneral = $totalNumerosGeneralPW + $totalNumerosGeneralVM;
$consolidadoTotalDineroGeneral = $totalDineroGeneralPW + $totalDineroGeneralVM;

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
                  <img src="<?php echo IMG_PATH . '/profile/user-1.jpg'; ?>" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="salir.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Salir</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">

        <div class="row">
          <!-- numeros vendidos -->
          <div class="col-lg-3">
            <!-- Yearly Breakup -->
            <div class="card overflow-hidden bg-primary shadow-lg rounded">
              <div class="card-body p-3">
                <h5 class="card-title mb-9 fw-semibold">Vendidos <span><i class="ti ti-list"></i></span></h5>
                <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="fw-semibold mb-3"><?php echo isset($totalNumerosVendidos) ? htmlspecialchars($totalNumerosVendidos) : '0'; ?></h4>
                    <div class="d-flex align-items-center">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ventas en dinero -->
          <div class="col-lg-3">
            <!-- Yearly Breakup -->
            <div class="card overflow-hidden bg-success shadow-lg rounded">
              <div class="card-body p-3">
                <h5 class="card-title mb-9 fw-semibold">Ventas <span><i class="ti ti-shopping-cart"></i></span></h5>
                <div class="row align-items-center">
                  <div class="col-8">
                    <h4 class="fw-semibold mb-3"><?php echo '$' . number_format($totalPagos ?? 0, 0, ',', '.'); ?></h4>
                    <div class="d-flex align-items-center">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- mis clientes -->
          <div class="col-lg-3">
            <!-- Yearly Breakup -->
            <div class="card overflow-hidden bg-warning shadow-lg rounded">
              <div class="card-body p-3">
                <h5 class="card-title mb-9 fw-semibold">Mis Clientes <span><i class="ti ti-user"></i></span></h5>
                <div class="row align-items-center">
                  <div class="col-8">
                    <h4 class="fw-semibold mb-3"><?php echo htmlspecialchars($totalClientes); ?></h4>
                    <div class="d-flex align-items-center">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- faltan por vender -->
          <div class="col-lg-3">
            <!-- Yearly Breakup -->
            <div class="card overflow-hidden bg-danger shadow-lg rounded">
              <div class="card-body p-3">
                <h5 class="card-title mb-9 fw-semibold">Faltan por Vender <span><i class="ti ti-eye"></i></span></h5>
                <div class="row align-items-center">
                  <div class="col-8">
                    <h4 class="fw-semibold mb-3"><?php echo htmlspecialchars($faltanXvender); ?></h4>
                    <div class="d-flex align-items-center">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!--  Row 1 -->
        <div class="row mt-5">
          <div class="col-lg-12 d-flex align-items-strech">
            <div class="card-body">
              <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                <div class="mb-3 mb-sm-0">
                  <h5 class="card-title fw-semibold">Resumen diario</h5>
                </div>
              </div>
              <!-- tarjetas de resumen diario -->
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Página Web <span><i class="ti ti-link"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalVentasHoyPW); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalNumerosHoyPW); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($totalDineroHoyPW, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Venta Manual <span><i class="ti ti-user"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalVentasHoyVM); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalNumerosHoyVM); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($totalDineroHoyVM, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Consolidado <span><i class="ti ti-server"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($consolidadoVentasHoy); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($consolidadoTotalNumerosHoy); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($consolidadoTotalDineroHoy, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  Row 1 End -->

        <!--  Row 2 -->
        <div class="row">
          <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Clientes con más compras</h5>
                  </div>
                </div>
                <div id="clientes"></div>
              </div>
            </div>
          </div>
        </div>
        <!--  Row 2 End -->

        <!--  Row 3 -->
        <div class="row">
          <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
              <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Ciudad con más Ventas</h5>
                  </div>
                </div>
                <div id="ciudades"></div>
              </div>
            </div>
          </div>
        </div>
        <!--  Row 3 End -->

        <!--  Row 4 -->
        <div class="row mt-5">
          <div class="col-lg-12 d-flex align-items-strech">
            <div class="card-body">
              <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                <div class="mb-3 mb-sm-0">
                  <h5 class="card-title fw-semibold">Resumen general</h5>
                </div>
              </div>
              <!-- tarjetas de resumen diario -->
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Página Web <span><i class="ti ti-link"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalVentasGeneralPW); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalNumerosGeneralPW); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($totalDineroGeneralPW, 0, ',', '.'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Venta Manual <span><i class="ti ti-user"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalVentasGeneralVM); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($totalNumerosGeneralVM); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($totalDineroGeneralVM, 0, ',', '.'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header">
                          Consolidado <span><i class="ti ti-server"></i></span>
                        </div>
                        <div class="card-body">
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Ventas:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($consolidadoVentasGeneral); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Números:</h5>
                            <span class="badge rounded-pill text-bg-dark"><?php echo htmlspecialchars($consolidadoTotalNumerosGeneral); ?></span>
                          </div>
                          <div class="d-flex justify-content-between m-2">
                            <h5 class="card-title mb-0">Dinero:</h5>
                            <span class="badge rounded-pill text-bg-dark">$<?php echo number_format($consolidadoTotalDineroGeneral, 0, ',', '.'); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  Row 4 End -->



        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">&copy; Nixcol</a></p>
        </div>
      </div>
    </div>

  </div>

  <!--  Footer Start -->
  <?php include_once INCLUDE_PATH . 'footer.php'; ?>

  <!-- scripts solo para el dashboard -->
  <script src="<?php echo LIBS_PATH; ?>apexcharts/dist/apexcharts.min.js" defer></script>
  <script src="<?php echo JS_PATH; ?>dashboard.js" defer></script>
</body>

</html>