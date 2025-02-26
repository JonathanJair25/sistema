<section class="full-width navLateral scroll" id="navLateral">
	<div class="full-width navLateral-body">
		<div class="full-width navLateral-body-logo has-text-centered tittles is-uppercase">
			REDES ÓPTICAS
		</div>
		
		
		<nav class="full-width">
			<ul class="full-width list-unstyle menu-principal">

            <?php
                if ($_SESSION['rol'] == 2) {
            ?>
<li class="full-width">
					<a href="<?php echo APP_URL; ?>dashboard/" class="full-width">
						<div class="navLateral-body-cl">
							<i class="fas fa-home fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							Inicio
						</div>
					</a>
				</li>
                <li class="full-width divider-menu-h"></li>

<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-file-invoice fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            FACTURAS
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaNew/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-file-invoice-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Nueva factura
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaList/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-clipboard-list fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Lista de facturas
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaSearch/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-search-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Buscar factura
                </div>
            </a>
        </li>
    </ul>
</li>

<li class="full-width divider-menu-h"></li>
<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-cogs fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            CONFIGURACIONES
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <!-- Submenu for CUENTA -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-user-cog fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CUENTA
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>companyNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-store-alt fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Datos de empresa
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-user-tie fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi cuenta
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userPhoto/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi foto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for ORGANIZACIONES -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-building fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    ORGANIZACIONES
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva organización
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de organizaciones
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categorySearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar Organización
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for SERVICIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-concierge-bell fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    SERVICIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-circle fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo servicio
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de servicios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar servicio
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for CAJAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-cash-register fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CAJAS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva caja
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de cajas
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar caja
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for FACTURAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-box-open fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    PRODUCTOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo producto
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de productos
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar producto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for USUARIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-users fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    USUARIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo usuario
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de usuarios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar usuario
                        </div>
                    </a>
                </li>
            </ul>
        </li>

            <?php
                } elseif ($_SESSION['rol'] == 3) {
            ?>
<li class="full-width">
					<a href="<?php echo APP_URL; ?>dashboard/" class="full-width">
						<div class="navLateral-body-cl">
							<i class="fas fa-home fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							Inicio
						</div>
					</a>
				</li>
                <li class="full-width divider-menu-h"></li>

<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-file-invoice fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            FACTURAS
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaNew/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-file-invoice-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Nueva factura
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaList/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-clipboard-list fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Lista de facturas
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaSearch/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-search-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Buscar factura
                </div>
            </a>
        </li>
    </ul>
</li>

<li class="full-width divider-menu-h"></li>
<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-cogs fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            CONFIGURACIONES
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <!-- Submenu for CUENTA -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-user-cog fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CUENTA
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>companyNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-store-alt fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Datos de empresa
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-user-tie fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi cuenta
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userPhoto/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi foto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for ORGANIZACIONES -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-building fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    ORGANIZACIONES
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva organización
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de organizaciones
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categorySearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar Organización
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for SERVICIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-concierge-bell fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    SERVICIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-circle fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo servicio
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de servicios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar servicio
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for CAJAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-cash-register fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CAJAS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva caja
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de cajas
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar caja
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for FACTURAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-box-open fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    PRODUCTOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo producto
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de productos
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar producto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for USUARIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-users fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    USUARIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo usuario
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de usuarios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar usuario
                        </div>
                    </a>
                </li>
            </ul>
        </li>
    
            <?php
                } else {
            ?>

				<li class="full-width">
					<a href="<?php echo APP_URL; ?>dashboard/" class="full-width">
						<div class="navLateral-body-cl">
							<i class="fas fa-home fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							Inicio
						</div>
					</a>
				</li>
				<li class="full-width divider-menu-h"></li>

				<li class="full-width">
					<a href="#" class="full-width btn-subMenu">
						<div class="navLateral-body-cl">
							<i class="fas fa-users fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							CLIENTES
						</div>
						<span class="fas fa-chevron-down"></span>
					</a>
					<ul class="full-width menu-principal sub-menu-options">
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productNew/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-user-plus fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Nuevo cliente
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productList/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-clipboard-list fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Lista de clientes
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productCategory/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-map-marker-alt fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Clientes por organización
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productSearch/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-search fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Buscar cliente
								</div>
							</a>
						</li>
					</ul>
				</li>
                
                <li class="full-width divider-menu-h"></li>

				<li class="full-width">
					<a href="#" class="full-width btn-subMenu">
						<div class="navLateral-body-cl">
							<i class="fas fa-users fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							SSH
						</div>
						<span class="fas fa-chevron-down"></span>
					</a>
					<ul class="full-width menu-principal sub-menu-options">
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>sshNew/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-user-plus fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Nuevo ssh
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productList/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-clipboard-list fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Lista de clientes
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productCategory/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-map-marker-alt fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Clientes por organización
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>productSearch/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-search fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Buscar cliente
								</div>
							</a>
						</li>
					</ul>
				</li>

				<!-- <li class="full-width divider-menu-h"></li>

				<li class="full-width">
					<a href="#" class="full-width btn-subMenu">
						<div class="navLateral-body-cl">
							<i class="fas fa-address-book fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							CLIENTES
						</div>
						<span class="fas fa-chevron-down"></span>
					</a>
					<ul class="full-width menu-principal sub-menu-options">
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>clientNew/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-male fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Nuevo cliente
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>clientList/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-clipboard-list fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Lista de clientes
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>clientSearch/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-search fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Buscar cliente
								</div>
							</a>
						</li>
					</ul>
				</li> -->

				<li class="full-width divider-menu-h"></li>

				<li class="full-width">
					<a href="#" class="full-width btn-subMenu">
						<div class="navLateral-body-cl">
							<i class="fas fa-credit-card fa-fw"></i>
						</div>
						<div class="navLateral-body-cr">
							PAGOS
						</div>
						<span class="fas fa-chevron-down"></span>
					</a>
					<ul class="full-width menu-principal sub-menu-options">
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>saleNew/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-money-check-alt fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Nuevo pago
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>saleList/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-clipboard-list fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Lista de pagos
								</div>
							</a>
						</li>
						<li class="full-width">
							<a href="<?php echo APP_URL; ?>saleSearch/" class="full-width">
								<div class="navLateral-body-cl">
									<i class="fas fa-search-dollar fa-fw"></i>
								</div>
								<div class="navLateral-body-cr">
									Buscar pago
								</div>
							</a>
						</li>
					</ul>
				</li>
                <li class="full-width divider-menu-h"></li>

<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-file-invoice fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            FACTURAS
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaNew/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-file-invoice-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Nueva factura
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaList/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-clipboard-list fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Lista de facturas
                </div>
            </a>
        </li>
        <li class="full-width">
            <a href="<?php echo APP_URL; ?>entradaSearch/" class="full-width">
                <div class="navLateral-body-cl">
                    <i class="fas fa-search-dollar fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    Buscar factura
                </div>
            </a>
        </li>
    </ul>
</li>

<li class="full-width divider-menu-h"></li>
<li class="full-width">
    <a href="#" class="full-width btn-subMenu">
        <div class="navLateral-body-cl">
            <i class="fas fa-cogs fa-fw"></i>
        </div>
        <div class="navLateral-body-cr">
            CONFIGURACIONES
        </div>
        <span class="fas fa-chevron-down"></span>
    </a>
    <ul class="full-width menu-principal sub-menu-options">
        <!-- Submenu for CUENTA -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-user-cog fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CUENTA
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>companyNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-store-alt fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Datos de empresa
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userUpdate/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-user-tie fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi cuenta
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL."userPhoto/".$_SESSION['id']."/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Mi foto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for ORGANIZACIONES -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-building fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    ORGANIZACIONES
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva organización
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categoryList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de organizaciones
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>categorySearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar Organización
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for SERVICIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-concierge-bell fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    SERVICIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-circle fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo servicio
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de servicios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>servicioSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar servicio
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for CAJAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-cash-register fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    CAJAS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nueva caja
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de cajas
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>cashierSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar caja
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for FACTURAS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-box-open fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    PRODUCTOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-plus-square fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo producto
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de productos
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>facturasSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar producto
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Submenu for USUARIOS -->
        <li class="full-width">
            <a href="#" class="full-width btn-subMenu">
                <div class="navLateral-body-cl">
                    <i class="fas fa-users fa-fw"></i>
                </div>
                <div class="navLateral-body-cr">
                    USUARIOS
                </div>
                <span class="fas fa-chevron-down"></span>
            </a>
            <ul class="full-width menu-principal sub-menu-options">
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userNew/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-cash-register fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Nuevo usuario
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userList/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-clipboard-list fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Lista de usuarios
                        </div>
                    </a>
                </li>
                <li class="full-width">
                    <a href="<?php echo APP_URL; ?>userSearch/" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-search fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Buscar usuario
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        <?php
    }
?>

			</ul>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <ul>
            <li class="full-width divider-menu-h"></li>

<li class="full-width mt-5">
    <a href="<?php echo APP_URL."logOut/"; ?>" class="full-width btn-exit" >
        <div class="navLateral-body-cl">
            <i class="fas fa-power-off"></i>
        </div>
        <div class="navLateral-body-cr">
            Cerrar sesión
        </div>
    </a>
</li>
            </ul>

		</nav>
	</div>
</section>