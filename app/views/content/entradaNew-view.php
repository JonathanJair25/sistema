<div class="container is-fluid mb-6">
    <h1 class="title">FACTURAS</h1>
    <?php
    // Obtén el producto_id desde la URL o de donde sea necesario
    $id = $insLogin->limpiarCadena($url[1]);
    
    ?>
    <h2 class="subtitle">
        <i class="fas fa-cart-plus fa-fw"></i> &nbsp; Nueva factura
        <?php if (isset($id)): ?>
            <span class="is-size-5"> - Cliente: <?php echo htmlspecialchars($id); ?></span>
        <?php endif; ?>
    </h2>
</div>


<div class="container pb-6 pt-6">
    <?php
    include "./app/views/inc/btn_back.php";

// Obtén el producto_id desde la URL o de donde sea necesario
$id = $insLogin->limpiarCadena($url[1]);

// Consulta los datos del producto
$datos = $insLogin->seleccionarDatos("Unico", "facturas", "facturas_id", $id);

        $check_empresa=$insLogin->seleccionarDatos("Normal","empresa LIMIT 1","*",0);

        if($check_empresa->rowCount()==1){
            $check_empresa=$check_empresa->fetch();
    ?>
    <div class="columns">

        <div class="column pb-6">
            <form class="pt-6 pb-6" id="entrada-barcode-form" autocomplete="off">
                
    <div class="columns">
    <div class="column is-one-quarter">
            <button type="button" class="button is-link is-light js-modal-trigger" data-target="modal-js-product" >
                <i class="fas fa-search"></i> &nbsp; AÑADIR PRODUCTO
            </button>
        </div>
        <div class="column" style="display: none;">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <input class="input" type="text" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" autofocus="autofocus" placeholder="Código de cliente" id="entrada-barcode-input" >
                </p>
                <a class="control">
                    <button type="submit" class="button is-info" id="add-product-btn" disabled>
                        <i class="far fa-check-circle"></i> &nbsp; Agregar cliente
                    </button>
                </a>
            </div>
        </div>
    </div>
</form>

            <?php
                if(isset($_SESSION['alerta_factura_agregado']) && $_SESSION['alerta_factura_agregado']!=""){
                    echo '
                    <div class="notification is-success is-light">
                      '.$_SESSION['alerta_factura_agregado'].'
                    </div>
                    ';
                    unset($_SESSION['alerta_factura_agregado']);
                }

                if(isset($_SESSION['entrada_codigo_factura']) && $_SESSION['entrada_codigo_factura']!=""){
            ?>
            <div class="notification is-info is-light mb-2 mt-2">
                <h4 class="has-text-centered has-text-weight-bold">Venta realizada</h4>
                <p class="has-text-centered mb-2">La venta se realizó con éxito. ¿Que desea hacer a continuación? </p>
                <br>
                <div class="container">
                    <div class="columns">
                        <div class="column has-text-centered">
                            <button type="button" class="button is-link is-light" onclick="print_ticket('<?php echo APP_URL."app/pdf/ticket.php?code=".$_SESSION['entrada_codigo_factura']; ?>')" >
                                <i class="fas fa-receipt fa-2x"></i> &nbsp;
                                Imprimir ticket de venta
                            </buttona>
                        </div>
                        <div class="column has-text-centered">
                            <button type="button" class="button is-link is-light" onclick="print_invoice('<?php echo APP_URL."app/pdf/invoice.php?code=".$_SESSION['entrada_codigo_factura']; ?>')" >
                                <i class="fas fa-file-invoice-dollar fa-2x"></i> &nbsp;
                                Imprimir factura de venta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    unset($_SESSION['entrada_codigo_factura']);
                }
            ?>
            <div class="table-container">
                <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-centered">Código de factura</th>
                            <th class="has-text-centered">Nombre</th>
                            <th class="has-text-centered">Meses</th>
                            <th class="has-text-centered">Precio</th>
                            <th class="has-text-centered">Total</th>
                            <th class="has-text-centered">Actualizar</th>
                            <th class="has-text-centered">Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_SESSION['datos_factura_cliente']) && count($_SESSION['datos_factura_cliente'])>=1){

                                $_SESSION['entrada_total']=0;
                                $cc=1;

                                foreach($_SESSION['datos_factura_cliente'] as $facturas){
                        ?>
                        <tr class="has-text-centered" >
                            <td><?php echo $facturas['facturas_etiqueta']; ?></td>
                            <td><?php echo $facturas['entrada_detalle_descripcion']; ?></td>
                            <td>
                                <div class="control">
                                    <input class="input sale_input-cant has-text-centered" value="<?php echo $facturas['entrada_detalle_cantidad']; ?>" id="sale_input_<?php echo str_replace(" ", "_", $facturas['facturas_etiqueta']); ?>" type="text" style="max-width: 80px;">
                                </div>
                            </td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($facturas['entrada_detalle_precio_venta'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                            <td><?php echo MONEDA_SIMBOLO.number_format($facturas['entrada_detalle_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></td>
                            <td>
                                <button type="button" class="button is-success is-rounded is-small" onclick="actualizar_cantidad('#sale_input_<?php echo str_replace(" ", "_", $facturas['facturas_etiqueta']); ?>','<?php echo $facturas['facturas_etiqueta']; ?>')" >
                                    <i class="fas fa-redo-alt fa-fw"></i>
                                </button>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/entradaAjax.php" method="POST" autocomplete="off">

                                    <input type="hidden" name="facturas_etiqueta" value="<?php echo $facturas['facturas_etiqueta']; ?>">
                                    <input type="hidden" name="modulo_entrada" value="remover_producto">

                                    <button type="submit" class="button is-danger is-rounded is-small" title="Remover producto">
                                        <i class="fas fa-trash-restore fa-fw"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                $cc++;
                                $_SESSION['entrada_total']+=$facturas['entrada_detalle_total'];
                            }
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="4"></td>
                            <td class="has-text-weight-bold">
                                TOTAL
                            </td>
                            <td class="has-text-weight-bold">
                                <?php echo MONEDA_SIMBOLO.number_format($_SESSION['entrada_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <?php
                            }else{
                                    $_SESSION['entrada_total']=0;
                        ?>
                        <tr class="has-text-centered" >
                            <td colspan="8">
                                No hay factura agregada
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="column is-one-quarter">
            <h2 class="title has-text-centered">Datos de la factura</h2>
            <hr>

            <?php if($_SESSION['entrada_total']>0){ ?>
            <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/entradaAjax.php" method="POST" autocomplete="off" name="formsale" >
                <input type="hidden" name="modulo_entrada" value="registrar_venta">
            <?php }else { ?>
            <form name="formsale">
            <?php } ?>

                <div class="control mb-5">
                    <label>Fecha</label>
                    <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="control mb-5" style="display: none;">
                    <label>Cliente</label>
                    <input class="input" type="text" name="producto_id" value="<?php echo $id; ?>" readonly>
                </div>
                

                <h4 class="subtitle is-5 has-text-centered has-text-weight-bold mb-5"><small>TOTAL: <?php echo MONEDA_SIMBOLO.number_format($_SESSION['entrada_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,MONEDA_SEPARADOR_MILLAR)." ".MONEDA_NOMBRE; ?></small></h4>

                <?php if($_SESSION['entrada_total']>0){ ?>
                <p class="has-text-centered">
                    <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar factura</button>
                </p>
                <?php } ?>
                <input type="hidden" value="<?php echo number_format($_SESSION['entrada_total'],MONEDA_DECIMALES,MONEDA_SEPARADOR_DECIMAL,""); ?>" id="entrada_total_hidden">
            </form>
        </div>

    </div>
    <?php }else{ ?>
        <article class="message is-warning">
             <div class="message-header">
                <p>¡Ocurrio un error inesperado!</p>
             </div>
            <div class="message-body has-text-centered"><i class="fas fa-exclamation-triangle fa-2x"></i><br>No hemos podio seleccionar algunos datos sobre la empresa, por favor <a href="<?php echo APP_URL; ?>companyNew/" >verifique aquí los datos de la empresa</div>
        </article>
    <?php } ?>
</div>

<!-- Modal buscar producto -->
<div class="modal" id="modal-js-product">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title is-uppercase"><i class="fas fa-search"></i> &nbsp; FACTURAS</p>
          <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body" >
            <div class="field mt-6 mb-6">
                <div class="control" style="display: none;">
                    <input class="input" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" name="input_codigo" id="input_codigo" maxlength="30" >
                </div>
            </div>
            <div class="container" id="tabla_productos"></div>
            <p class="has-text-centered" style="display: none;">
                <button type="button" class="button is-link is-light" onclick="buscar_codigo()" ><i class="fas fa-search"></i> &nbsp; Buscar</button>
            </p>
        </section>
    </div>
</div>

<script>

    /* Detectar cuando se envia el formulario para agregar producto */
    let factura_form_barcode = document.querySelector("#entrada-barcode-form");
    factura_form_barcode.addEventListener('submit', function(event){
        event.preventDefault();
        setTimeout('agregar_producto()',100);
    });


    /* Detectar cuando escanea un codigo en formulario para agregar producto */
    let sale_input_barcode = document.querySelector("#entrada-barcode-input");
    sale_input_barcode.addEventListener('paste',function(){
        setTimeout('agregar_producto()',100);
    });


    /* Agregar producto */
    function agregar_producto(){
        let codigo_producto=document.querySelector('#entrada-barcode-input').value;

        codigo_producto=codigo_producto.trim();

        if(codigo_producto!=""){
            let datos = new FormData();
            datos.append("facturas_etiqueta", codigo_producto);
            datos.append("modulo_entrada", "agregar_producto");

            fetch('<?php echo APP_URL; ?>app/ajax/entradaAjax.php',{
                method: 'POST',
                body: datos
            })
            .then(respuesta => respuesta.json())
            .then(respuesta =>{
                return alertas_ajax(respuesta);
            });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir el código del producto',
                confirmButtonText: 'Aceptar'
            });
        }
    }


   /*----------  Buscar todas las facturas  ----------*/
function buscar_facturas() {
    let datos = new FormData();
    datos.append("modulo_entrada", "buscar_todas_facturas");

    fetch('<?php echo APP_URL; ?>app/ajax/entradaAjax.php', {
        method: 'POST',
        body: datos
    })
    .then(respuesta => respuesta.text())
    .then(respuesta => {
        let tabla_productos = document.querySelector('#tabla_productos');
        tabla_productos.innerHTML = respuesta;
    });
}

// Llamar a la función buscar_facturas cuando se carga la página
document.addEventListener('DOMContentLoaded', function () {
    buscar_facturas();
});


    /*----------  Agregar codigo  ----------*/
    function agregar_codigo($codigo){
        document.querySelector('#entrada-barcode-input').value=$codigo;
        setTimeout('agregar_producto()',100);
    }


    /* Actualizar cantidad de producto */
    function actualizar_cantidad(id,codigo){
        let cantidad=document.querySelector(id).value;

        cantidad=cantidad.trim();
        codigo.trim();

        if(cantidad>0){

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Desea actualizar la cantidad de productos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, actualizar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed){

                    let datos = new FormData();
                    datos.append("facturas_etiqueta", codigo);
                    datos.append("facturas_cantidad_cliente", cantidad);
                    datos.append("modulo_entrada", "actualizar_producto");

                    fetch('<?php echo APP_URL; ?>app/ajax/entradaAjax.php',{
                        method: 'POST',
                        body: datos
                    })
                    .then(respuesta => respuesta.json())
                    .then(respuesta =>{
                        return alertas_ajax(respuesta);
                    });
                }
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado',
                text: 'Debes de introducir una cantidad mayor a 0',
                confirmButtonText: 'Aceptar'
            });
        }
    }
    

    document.addEventListener('DOMContentLoaded', function () {
    const saleInputBarcode = document.querySelector("#entrada-barcode-input");
    const addProductBtn = document.querySelector("#add-product-btn");

    saleInputBarcode.addEventListener('input', function () {
        if (saleInputBarcode.value.trim() !== "") {
            addProductBtn.disabled = false;
        } else {
            addProductBtn.disabled = true;
        }
    });

    // Activar el botón si el campo ya tiene un valor al cargar la página
    if (saleInputBarcode.value.trim() !== "") {
        addProductBtn.disabled = false;
    }
});


</script>


<?php
    include "./app/views/inc/print_invoice_script.php";
?>