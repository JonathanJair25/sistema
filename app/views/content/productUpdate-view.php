<div class="container is-fluid mb-6">
    <h1 class="title">CLIENTES</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar cliente</h2>
</div>

<div class="container pb-1 pt-1">
    <?php
        include "./app/views/inc/btn_back.php";

        // Obtén el producto_id desde la URL o de donde sea necesario
        $id = $insLogin->limpiarCadena($url[1]);
    
        // Consulta los datos del producto
        $datos = $insLogin->seleccionarDatos("Unico", "producto", "producto_id", $id);
    
        if ($datos->rowCount() == 1) {
            $datos = $datos->fetch();
    
            // Consulta el nombre y precio del servicio asociado al producto
            $servicio_id = $datos['servicios_id'];
            $servicio_nombre = '';
            $servicio_precio_mensual = '';
            $servicio_datos = $insLogin->seleccionarDatos("Unico", "servicios", "servicios_id", $servicio_id);
            if ($servicio_datos->rowCount() == 1) {
                $servicio_datos = $servicio_datos->fetch();
                $servicio_nombre = $servicio_datos['servicios_nombre'];
                $servicio_precio_mensual = $servicio_datos['servicios_precio_mensual'];
            }
    
            // Consulta los pagos asociados a este producto

    
            // Comienza el formulario y la interfaz HTML
    ?>

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productoAjax.php" method="POST" autocomplete="off">
        <input type="hidden" name="modulo_producto" value="actualizar">
        <input type="hidden" name="producto_id" value="<?php echo $datos['producto_id']; ?>">
   


        <p class="has-text-centered" style="font-size: 1.5em;">
            <strong>FACTURACIÓN</strong>
        </p>
        <br><br>

<!-- Aquí se agrega el menú -->
<ul class="menu-principal-horizontal">
    <li>
    <a href="<?php echo APP_URL; ?>productPagos/<?php echo $datos['producto_id']; ?>/">
            <i class="fas fa-cart-plus fa-fw"></i>
            <span>PAGOS</span>
        </a>
    </li>
    <li>
        <a href="<?php echo APP_URL; ?>facturasPagos/<?php echo $datos['producto_id']; ?>/">
            <i class="fas fa-clipboard-list fa-fw"></i>
            <span>FACTURAS</span>
        </a>
    </li>
</ul>
<style>
    .menu-principal-horizontal {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center; /* Alinea los elementos al centro horizontalmente */
        background-color: #f5f5f5; /* Color de fondo del bloque */
        border-radius: 8px; /* Borde redondeado del bloque */
        padding: 10px; /* Espacio interno del bloque */
    }

    .menu-principal-horizontal li {
        display: inline-block; /* Mostrar elementos en línea */
        margin: 0 10px; /* Espacio entre elementos del menú */
    }

    .menu-principal-horizontal a {
        display: block;
        text-align: center;
        text-decoration: none;
        color: #333; /* Color del texto */
        font-size: 14px; /* Tamaño de fuente */
        padding: 10px; /* Espacio alrededor del texto */
    }

    .menu-principal-horizontal a:hover {
        background-color: #ddd; /* Color de fondo al pasar el mouse */
        border-radius: 5px; /* Bordes redondeados al pasar el mouse */
    }

    .menu-principal-horizontal i {
        margin-right: 5px; /* Espacio entre el icono y el texto */
    }
</style>

<br>

<!-- Aquí empieza el formulario -->
<div class="columns">
    <div class="column">
        <div class="control has-text-centered">
            <label class="has-text-centered">Saldo de la cuenta <?php echo CAMPO_OBLIGATORIO; ?></label>
            <input class="input" type="text" name="producto_codigo" value="<?php echo $datos['saldo_cuenta']; ?>" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required readonly style="text-align: center; font-size: 20px;">
        </div>
    </div>
    <div class="column">
        <div class="control has-text-centered">
            <label class="has-text-centered">Crédito <?php echo CAMPO_OBLIGATORIO; ?></label>
            <input class="input" type="text" name="producto_credito" value="<?php echo $datos['producto_credito']; ?>" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required readonly style="text-align: center; font-size: 20px;">
        </div>
    </div>
    <div class="column">
        <div class="control has-text-centered">
            <label class="has-text-centered">Pendientes <?php echo CAMPO_OBLIGATORIO; ?></label>
            <input class="input" type="text" name="producto_codigo" value="<?php echo $datos['saldo_pendiente']; ?>" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required readonly style="text-align: center; font-size: 20px;">
        </div>
    </div>
</div>



    <div class="columns is-centered">
        <div class="column is-half">
            <div class="control">
                <strong class="has-text-centered" style="font-size: 1.1rem;">
                    <span class="has-text-weight-bold" style="font-size: 1rem;">ID: </span><?php echo $datos['producto_codigo']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Nombre: </span><?php echo $datos['producto_nombre'] . " " . $datos['producto_apellidos']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Servicio: </span><?php echo $servicio_nombre . ' - ' . $servicio_precio_mensual; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Organización: </span>
                    <?php
                        $datos_categorias = $insLogin->seleccionarDatos("Normal", "categoria", "*", 0);
                        while ($campos_categoria = $datos_categorias->fetch()) {
                            if ($campos_categoria['categoria_id'] == $datos['categoria_id']) {
                                echo $campos_categoria['categoria_nombre'];
                                break;
                            }
                        }
                    ?><br><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Dirección: </span><?php echo $datos['producto_direccion']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Referencias: </span><?php echo $datos['producto_referencias']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">CP: </span><?php echo $datos['producto_cp']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Teléfonos: </span><?php echo $datos['producto_telefono'] . " - " . $datos['producto_telefono2']; ?><br><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Poste: </span><?php echo $datos['producto_poste']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Etiqueta: </span><?php echo $datos['producto_etiqueta']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Nodo-Caja: </span><?php echo $datos['producto_nodo']; ?><br>
                    <span class="has-text-weight-bold" style="font-size: 1rem;">Contrato: </span><?php echo $datos['producto_contrato']; ?><br>
                </strong>
            </div>
        </div>


    
    <!-- Columna derecha con los campos del formulario -->
    <div class="column is-half">
    <div class="field">
        <label class="label">Estado del Producto</label>
        <div class="control">
            <div class="select">
                <select name="producto_estado" required>
                    <option value="habilitado" <?php echo ($datos['producto_estado'] == 'habilitado') ? 'selected' : ''; ?>>Habilitado</option>
                    <option value="deshabilitado" <?php echo ($datos['producto_estado'] == 'deshabilitado') ? 'selected' : ''; ?>>Deshabilitado</option>
                </select>
            </div>
        </div>
    </div>
        <div class="field">
            <label class="label">Servicio<?php echo CAMPO_OBLIGATORIO; ?></label>
            <div class="control">
                <div class="select">
                    <select id="servicios_id" name="servicios_id">
                        <?php
                            $datos_servicios = $insLogin->seleccionarDatos("Normal", "servicios", "*", 0);
                            $cc = 1;
                            while ($campos_servicios = $datos_servicios->fetch()) {
                                $selected = ($campos_servicios['servicios_id'] == $datos['servicios_id']) ? 'selected' : '';
                                echo '<option value="' . $campos_servicios['servicios_id'] . '" data-precio="' . $campos_servicios['servicios_precio_mensual'] . '" ' . $selected . '>' . $cc . ' - ' . $campos_servicios['servicios_nombre'] . ' ' . ($selected ? '(Actual)' : '') . '</option>';
                                $cc++;
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="field">
            <label class="label">Precio Mensual</label>
            <div class="control">
                <input id="servicios_precio_mensual" class="input" type="text" name="servicio_precio_mensual" value="<?php echo $datos['servicio_precio_mensual']; ?>" readonly>
            </div>
        </div>
        
        <div class="field">
            <label class="label">La facturación empieza</label>
            <div class="control">
                <input class="input" type="date" name="producto_fecha_facturacion" value="<?php echo $datos['producto_fecha_facturacion']; ?>" required>
            </div>
        </div>
        
        <div class="field">
            <label class="label">IP<?php echo CAMPO_OBLIGATORIO; ?></label>
            <div class="control">
                <input class="input" type="text" name="producto_ip" 
                   pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})\.){3}(?:25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})$|^([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4}|:)$" 
                   value="<?php echo $datos['producto_ip']; ?>" maxlength="39" required>
            </div>
        </div>
        <br>
        <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
    </p>
    </div>
</div>
<br><br>
<br><br>
        <p class="has-text-centered" style="font-size: 1.5em;">
    <strong id="toggle-button" style="cursor: pointer;">
        DATOS DEL CLIENTE <span id="toggle-arrow">▼</span>
    </strong>
</p>
<br><br>
<div id="client-data" style="display: none;">
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Código de cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="text" name="producto_codigo" value="<?php echo $datos['producto_codigo']; ?>" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required readonly>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="text" name="producto_nombre" value="<?php echo $datos['producto_nombre']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Apellidos <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="text" name="producto_apellidos" value="<?php echo $datos['producto_apellidos']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <label>Organización<?php echo CAMPO_OBLIGATORIO; ?></label><br>
            <div class="select">
                <select name="producto_categoria">
                    <?php
                        $datos_categorias = $insLogin->seleccionarDatos("Normal", "categoria", "*", 0);
                        $cc = 1;
                        while ($campos_categoria = $datos_categorias->fetch()) {
                            if ($campos_categoria['categoria_id'] == $datos['categoria_id']) {
                                echo '<option value="' . $campos_categoria['categoria_id'] . '" selected="">' . $cc . ' - ' . $campos_categoria['categoria_nombre'] . ' (Actual)</option>';
                            } else {
                                echo '<option value="' . $campos_categoria['categoria_id'] . '">' . $cc . ' - ' . $campos_categoria['categoria_nombre'] . '</option>';
                            }
                            $cc++;
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Correo</label>
                <input class="input" type="text" name="producto_correo" value="<?php echo $datos['producto_correo']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Teléfono <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="text" name="producto_telefono" value="<?php echo $datos['producto_telefono']; ?>" pattern="[0-9()+]{8,20}" maxlength="100" required>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Teléfono 2</label>
                <input class="input" type="text" name="producto_telefono2" value="<?php echo $datos['producto_telefono2']; ?>" pattern="[0-9()+]{8,20}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Fecha de registro <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="date" name="producto_fecha_registro" value="<?php echo $datos['producto_fecha_registro']; ?>" required>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Dirección completa <?php echo CAMPO_OBLIGATORIO; ?></label>
                <input class="input" type="text" name="producto_direccion" value="<?php echo $datos['producto_direccion']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="100" required>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Referencias</label>
                <input class="input" type="text" name="producto_referencias" value="<?php echo $datos['producto_referencias']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>CP</label>
                <input class="input" type="text" name="producto_cp" value="<?php echo $datos['producto_cp']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>            
    </div>
    <br>
    <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
    </p>
</div>

<script>
    document.getElementById('toggle-button').addEventListener('click', function() {
        var clientData = document.getElementById('client-data');
        var toggleArrow = document.getElementById('toggle-arrow');
        if (clientData.style.display === 'none' || clientData.style.display === '') {
            clientData.style.display = 'block';
            toggleArrow.textContent = '▲';
        } else {
            clientData.style.display = 'none';
            toggleArrow.textContent = '▼';
        }
    });
</script>


<br>
<p class="has-text-centered" style="font-size: 1.5em;">
    <strong id="toggle-button2" style="cursor: pointer;">
        ATRIBUTOS PERSONALIZADOS <span id="toggle-arrow2">▼</span>
    </strong>
</p>
<br><br>
<div id="custom-attributes" style="display: none;">
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Poste</label>
                <input class="input" type="text" name="producto_poste" value="<?php echo $datos['producto_poste']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Etiqueta</label>
                <input class="input" type="text" name="producto_etiqueta" value="<?php echo $datos['producto_etiqueta']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Nodo-Caja</label>
                <input class="input" type="text" name="producto_nodo" value="<?php echo $datos['producto_nodo']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Contrato</label>
                <input class="input" type="text" name="producto_contrato" value="<?php echo $datos['producto_contrato']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
            </div>
        </div>    
    </div>
    <br>
    <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
    </p>
</div>

<script>
    document.getElementById('toggle-button2').addEventListener('click', function() {
        var customAttributes = document.getElementById('custom-attributes');
        var toggleArrow2 = document.getElementById('toggle-arrow2');
        if (customAttributes.style.display === 'none' || customAttributes.style.display === '') {
            customAttributes.style.display = 'block';
            toggleArrow2.textContent = '▲';
        } else {
            customAttributes.style.display = 'none';
            toggleArrow2.textContent = '▼';
        }
    });
</script>
<br>
<p class="has-text-centered" style="font-size: 1.5em;">
    <strong id="toggle-button3" style="cursor: pointer;">
        GRAFICA DE CONSUMO <span id="toggle-arrow3">▼</span>
    </strong>
</p>
<br><br>
<div id="grafica" style="display: none;">
    <canvas id="trafficChart" width="600" height="300"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function fetchQueueData() {
        try {
            // Asegúrate de que la ruta es correcta
            const response = await fetch('/sistemaredes/app/views/content/queueTrafficController.php?producto_id=<?php echo $datos['producto_id']; ?>');
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }

            const data = await response.json();
            console.log('Data fetched:', data); // Debugging line

            if (data.error) {
                console.error('Error fetching queue data:', data.error);
                return;
            }

            // Convert bytes to megabits (1 byte = 8 bits, 1 megabit = 1,000,000 bits)
            const upload = (data.upload * 8) / 1_000_000; // Convert bytes per second to megabits per second
            const download = (data.download * 8) / 1_000_000_0; // Convert bytes per second to megabits per second

            return {
                upload,
                download,
                name: data.name
            };
        } catch (error) {
            console.error('Error fetching queue data:', error);
        }
    }

    async function updateChart(chart) {
        const data = await fetchQueueData();
        if (data) {
            chart.data.labels.push(new Date().toLocaleTimeString()); // Add a timestamp
            chart.data.datasets[0].data.push(data.upload); // Upload data
            chart.data.datasets[1].data.push(data.download); // Download data

            // Limit the number of data points displayed
            if (chart.data.labels.length > 20) {
                chart.data.labels.shift();
                chart.data.datasets[0].data.shift();
                chart.data.datasets[1].data.shift();
            }

            chart.update();
        }
    }

    function startChart() {
        const ctx = document.getElementById('trafficChart').getContext('2d');
        const trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [], // Initialize empty
                datasets: [{
                    label: 'Upload (Mbps)',
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    data: [] // Initialize empty
                }, {
                    label: 'Download (Mbps)',
                    borderColor: 'blue',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    data: [] // Initialize empty
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Mbps'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(2) + ' Mbps';
                            }
                        }
                    }
                }
            }
        });

        // Update the chart every second
        setInterval(() => updateChart(trafficChart), 1000);
    }

    document.getElementById('toggle-button3').addEventListener('click', function() {
        var grafica = document.getElementById('grafica');
        var toggleArrow3 = document.getElementById('toggle-arrow3');
        if (grafica.style.display === 'none' || grafica.style.display === '') {
            grafica.style.display = 'block';
            toggleArrow3.textContent = '▲';
            startChart();
        } else {
            grafica.style.display = 'none';
            toggleArrow3.textContent = '▼';
        }
    });
</script>


        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
    <br>
    <script>
    document.getElementById('servicios_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var precioMensual = selectedOption.getAttribute('data-precio');
        document.getElementById('servicios_precio_mensual').value = precioMensual;
    });
</script>
</form>
    <?php
        } else {
            include "./app/views/inc/error_alert.php";
        }
    ?>
</div>
