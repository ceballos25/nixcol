<script>
    // Verificar si las variables 'nombre' y 'usuario' existen en localStorage
    if (!localStorage.getItem('nombre') || !localStorage.getItem('usuario')) {
        // Redirigir al usuario al módulo de inicio de sesión
        window.location.href = '../dash/';
    }
</script>
<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between m-2">
            <a href="<?php echo BASE_URL; ?>" class="text-nowrap logo-img">
                <img src="../img/logo-nix.png" width="100%" alt="" style="border-radius: 10px;" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">INICIO</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo BASE_URL; ?>dash/dash.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Principal</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MENU</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>vender.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-shopping-cart"></i>
                        </span>
                        <span class="hide-menu">Vender</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>detalle-ventas.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout"></i>
                        </span>
                        <span class="hide-menu">Ventas al detalle</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>numeros-vendidos.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-list"></i>
                        </span>
                        <span class="hide-menu">Números Vendidos</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">CLIENTES</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>clientes.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Mis Clientes</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">CONFIGURACION</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>respaldo.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-server"></i>
                        </span>
                        <span class="hide-menu">Respaldo</span>
                    </a>
                </li><li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>numeros-disponibles.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-list"></i>
                        </span>
                        <span class="hide-menu">Números</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?php echo PAGES_PATH; ?>escoger.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-shopping-cart"></i>
                        </span>
                        <span class="hide-menu">Escoger Premiado</span>
                    </a>
                </li>
                    <li class="sidebar-item">
                    <a target="_blank" class="sidebar-link" href="http://nixcol.com/functions/mercado-pago/cron.php" aria-expanded="false">
                        <span>
                            <i class="ti ti-settings"></i>
                        </span>
                        <span class="hide-menu">Forzar Ventas</span>
                    </a>
                </li>
            </ul>
        </nav>
        </div>
    </aside>
