<!-- head -->

<?php include_once '../config/config.php'; ?>+

<?php include_once INCLUDE_PATH . 'head.php'; ?>




<?php $idTransaccion = date('YmdHis') . rand(000000, 999999); ?>

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
        <!-- formulario de venta -->
        <div class="card-body">
          <h5 class="card-title fw-semibold mb-4">Generar una venta</h5>
          <div class="card">
            <div class="card-body">

              <form class="row g-4 needs-validation" novalidate action="../backend/processVenta.php" method="POST" id="form-venta">
                <div class="col-md-4">
                  <label for="celular" class="form-label">Celular</label>
                  <input type="tel" class="form-control" id="celular" name="celular" required pattern="^\d{10}$" />
                  <div class="invalid-feedback">Por favor, ingresa un número de celular válido.</div>
                </div>
                <div class="col-md-4">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required />
                  <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>
                <div class="col-md-4">
                  <label for="correo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="correo" name="email" required />
                  <div class="invalid-feedback">Por favor, ingresa un correo electrónico válido.</div>
                </div>

                <div class="col-md-4">
                  <label for="usp-custom-departamento-de-residencia" class="form-label">Departamento</label>
                  <select id="usp-custom-departamento-de-residencia" class="form-select" name="departamento" required>
                    <option selected disabled></option>
                    <option value="ANTIOQUIA">ANTIOQUIA</option>
                    <option value="AMAZONAS">AMAZONAS</option>
                    <option value="ARAUCA">ARAUCA</option>
                    <option value="ATLANTICO">ATLANTICO</option>
                    <option value="BOLIVAR">BOLIVAR</option>
                    <option value="BOYACA">BOYACA</option>
                    <option value="CALDAS">CALDAS</option>
                    <option value="CAQUETA">CAQUETA</option>
                    <option value="CASANARE">CASANARE</option>
                    <option value="CAUCA">CAUCA</option>
                    <option value="CESAR">CESAR</option>
                    <option value="CHOCO">CHOCO</option>
                    <option value="CORDOBA">CORDOBA</option>
                    <option value="CUNDINAMARCA">CUNDINAMARCA</option>
                    <option value="GUAINIA">GUAINIA</option>
                    <option value="GUAVIARE">GUAVIARE</option>
                    <option value="HUILA">HUILA</option>
                    <option value="LA GUAJIRA">LA GUAJIRA</option>
                    <option value="MAGDALENA">MAGDALENA</option>
                    <option value="META">META</option>
                    <option value="NARIÑO">NARIÑO</option>
                    <option value="NORTE DE SANTANDER">NORTE DE SANTANDER</option>
                    <option value="PUTUMAYO">PUTUMAYO</option>
                    <option value="QUINDIO">QUINDIO</option>
                    <option value="RISARALDA">RISARALDA</option>
                    <option value="SAN ANDRES Y PROVIDENCIA">SAN ANDRES Y PROVIDENCIA</option>
                    <option value="SANTANDER">SANTANDER</option>
                    <option value="SUCRE">SUCRE</option>
                    <option value="TOLIMA">TOLIMA</option>
                    <option value="VALLE DEL CAUCA">VALLE DEL CAUCA</option>
                    <option value="VAUPES">VAUPES</option>
                    <option value="VICHADA">VICHADA</option>
                  </select>
                  <div class="invalid-feedback">Por favor, selecciona un departamento.</div>
                </div>

                <div class="col-md-4">
                  <label for="usp-custom-municipio-ciudad" class="form-label">Ciudad</label>
                  <select id="usp-custom-municipio-ciudad" class="form-select" name="ciudad" required>
                    <option></option>
                    <!-- Más opciones aquí -->
                  </select>
                  <div class="invalid-feedback">Por favor, selecciona una ciudad.</div>
                </div>

                <div class="col-md-4">
                  <label for="oportunidades" class="form-label">Oportunidades</label>
                  <select id="select-oportunidades" class="form-select" name="oportunidades" required>
                    <option selected disabled></option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="otro">Otro</option>
                    <!-- Más opciones aquí -->
                  </select>
                  <div class="invalid-feedback">Por favor, selecciona las oportunidades.</div>
                </div>

                <div class="d-flex justify-content-center">
                  <div class="col-md-6">
                    <div class="card border-secondary">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <label for="total-oportunidades" class="form-label">Total Oportunidades</label>
                            <input type="number" value=" " class="form-control" id="total-oportunidades" name="total_numeros" readonly required min="5" />
                            <div class="invalid-feedback">Mínimo 5 oportunidades.</div>
                          </div>
                          <div class="col-md-6 total-oportunidades-div">
                            <label for="total-pago" class="form-label">Total a Pagar</label>
                            <input type="text" class="form-control" id="total-pago" name="total_pago" readonly required />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" value="<?php echo $idTransaccion; ?>" id="id_transaccion" name="id_transaccion" />
                  <input type="hidden" id="vendedor" name="vendedor" value="">

                      <script>
                          // Obtener el valor de localStorage
                          var nombreVendedor = localStorage.getItem('nombre');

                          // Asignar el valor al campo oculto
                          document.getElementById('vendedor').value = nombreVendedor;
                      </script>
                </div>

                <div class="col-12 d-flex justify-content-center py-4">
                  <button type="submit" class="btn btn-primary" id="submit-button">
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Generar Venta
                  </button>
                </div>
              </form>

            </div>
          </div>

        </div>

        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">&copy; Nixcol</a></p>
        </div>
        <!--  Footer Start -->

        <?php include_once INCLUDE_PATH . 'footer.php'; ?>
        <script src="<?php echo JS_PATH; ?>vender-v2.js"></script>

    </div>

    
      </body>

</html>