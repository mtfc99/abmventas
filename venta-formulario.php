<?php

include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/cliente.php";
include_once "entidades/provincia.php";
include_once "entidades/localidad.php";

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$venta = new Venta();
$aVentas = $venta->obtenerTodos();

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);



$pg = "Registrar Ventas";

if ($_POST) {

    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo una venta existente
            $venta->actualizar();
            $success='<div class="alert alert-success">
            <p>Se han efectuado los cambios</p>
            </div>';
            header("Location: venta-listado.php");

        } else {
            //Inserta una venta nueva
            $venta->insertar();
            header("Location: venta-listado.php");
        }
    } else if (isset($_POST["btnBorrar"])) {
        $venta->eliminar();
        header("Location: venta-listado.php");
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) { /*Si hay un ID seteado via GET (en la URL), lo cual es el caso, se ejecuta la query obtenerPorId(); */
    $venta->obtenerPorId();
}


include_once("header.php");
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ventas</h1>
    <?php
    if(isset($success)){
        echo $success;
    }
    ?>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="venta-listado.php" class="btn btn-primary mr-2">Listado</a>
            <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
            <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-6 form-group">
            <label for="txtFechaVenta">Fecha:</label>
            <select name="txtDiaVenta" id="txtDiaVenta">
                <option selected="" disabled="">DD</option>
                <?php for ($i = 1; $i <= 31; $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "d")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>


            <select name="txtMesVenta" id="txtMesVenta">
                <option selected="" disabled="">MM</option>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "m")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>

            <select name="txtAnioVenta" id="txtAnioVenta">
                <option selected="" disabled="">Y</option>
                <?php for ($i = 1900; $i <= 2021; $i++) : ?>
                    <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "Y")) : ?>
                        <option selected><?php echo $i; ?></option>
                    <?php else : ?>
                        <option><?php echo $i; ?></option>
                    <?php endif; ?>
                <?php endfor; ?> ?>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6 form-group">
            <label for="txtCliente">Cliente:</label><br>
            <select class="form-control d-inline" name="lstCliente" id="lstCliente">
                <option selected="" disabled="">Seleccione:</option>
                <?php foreach ($aClientes as $cliente) : ?>
                    <?php if ($cliente->idcliente == $venta->fk_idcliente) : ?>
                        <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6 form-group">
            <label for="txtProducto">Producto</label><br>
            <select class="form-control d-inline" name="lstProducto" id="lstProducto">
                <option selected="" disabled="">Seleccione:</option>
                <?php foreach ($aProductos as $producto) : ?>
                    <?php if ($producto->idproducto == $venta->fk_idproducto) : ?>
                        <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="txtPrecioUnitario">Precio Unitario:</label><br>
            <input type="text" class="form-control" name="txtPrecioUnitario" id="txtPrecioUnitario" value="<?php echo $venta->preciounitario ?>">
        </div>


        <div class="col-md-6 form-group">
            <label for="txtCantidad">Cantidad</label><br>
            <input type="text" class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $venta->cantidad ?>">
            </select>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 form-group">
            <label for="txtTotal">Total</label><br>
            <input type="text" class="form-control" name="txtTotal" id="txtTotal" value="<?php echo $venta->total ?>">
            </select>
        </div>

    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php include_once("footer.php"); ?>