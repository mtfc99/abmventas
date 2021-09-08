<?php

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";


$pg = "Edición de producto";

$tipoProducto = new TipoProducto();
$aTipoProductos = $tipoProducto->obtenerTodos();

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);


if($_POST){
    if(isset($_POST["btnGuardar"])){
    	$nombreImagen = "";
        if($_FILES["imagen"]["error"] === UPLOAD_ERR_OK){
          $nombreRandom = date("Ymdhmsi");
          $archivoTmp = $_FILES["imagen"]["tmp_name"];
          $nombreArchivo = $_FILES["imagen"]["name"];
          $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
          $nombreImagen = "$nombreRandom.$extension";
          move_uploaded_file($archivoTmp, "files/$nombreImagen");
        }

        if(isset($_GET["id"]) && $_GET["id"] > 0){
            $productoAnt = new Producto();
            $productoAnt->idproducto = $_GET["id"];
            $productoAnt->obtenerPorId();
            $imagenAnterior = $productoAnt->imagen;

            //Si es una actualizacion y se sube una imagen, elimina la anterior
            if($_FILES["imagen"]["error"] === UPLOAD_ERR_OK){
                if(!$imagenAnterior != ""){
                        unlink($imagenAnterior);
                }
            } else {
                //Si no viene ninguna imagen, setea como imagen la que habia previamente
                $nombreImagen= $imagenAnterior;
            }

            $producto->imagen = $nombreImagen;
            //Actualizo un cliente existente
            $producto->actualizar();
        } else {
            //Es nuevo
            $producto->imagen = $nombreImagen;
            $producto->insertar();
        }
    } else if(isset($_POST["btnBorrar"])){
        $producto->eliminar();
        header("Location: producto-listado.php");
    }
} 
if(isset($_GET["id"]) && $_GET["id"] > 0){
    $producto->obtenerPorId();

}

include_once("header.php");
?>



<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Productos</h1>
    <?php
    if(isset($success)){
    echo $success;
}
    ?>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="producto-listado.php" class="btn btn-primary mr-2">Listado</a>
            <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
            <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
        </div>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-6 form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre ?>">
        </div>
        <div class="col-6 form-group">
            <label for="txtCantidad">Cantidad:</label>
            <input type="text" required class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad ?>" maxlength="11">
        </div>
        <div class="col-6 form-group">
            <label for="txtPrecio">Precio:</label>
            <input type="number" class="form-control" name="txtPrecio" id="txtPrecio" required value="<?php echo $producto->precio ?>">
        </div>
        <div class="col-6 form-group">
            <label for="txtTelefono">Tipo de producto:</label> <br>
            <select class="form-control d-inline" name="lstTipoProducto" id="lstTipoProducto">
                <option selected="" disabled="">Seleccione:</option>
                <?php foreach ($aTipoProductos as $tipo) : ?>
                    <?php if ($tipo->idtipoproducto == $producto->fk_idtipoproducto) : ?>
                        <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 form-group">
            <label for="txtDescripcion">Descripcion:</label> <br>
            <textarea type="text" class="form-control" name="txtDescripcion" id="txtDescripcion" cols="30" rows="5" value="<?php $producto->descripcion ?>">
        </textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-6 form-group">
            <input type="file" name="imagen" id="imagen">
        </div>
    </div>
        <div class="row">
        <p>Imagen Actual:</p>
        <img src="files/<?php echo $producto->imagen;?>" alt="" class='img-thumbnail'> 
        </div>
    </form>



    <div class="modal fade" id="modalDomicilio" tabindex="-1" role="dialog" aria-labelledby="modalDomicilioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDomicilioLabel">Domicilio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="lstTipo">Tipo:</label>
                            <select name="lstTipo" id="lstTipo" class="form-control">
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="1">Personal</option>
                                <option value="2">Laboral</option>
                                <option value="3">Comercial</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="lstProvincia">Provincia:</label>
                            <select name="lstProvincia" id="lstProvincia" onchange="fBuscarLocalidad();" class="form-control">
                                <option value="" disabled selected>Seleccionar</option>
                                <?php foreach ($aProvincias as $prov) : ?>
                                    <option value="<?php echo $prov->idprovincia; ?>"><?php echo $prov->nombre; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="lstLocalidad">Localidad:</label>
                            <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                                <option value="" disabled selected>Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="txtDireccion">Dirección:</label>
                            <input type="text" name="" id="txtDireccion" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="fAgregarDomicilio()">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
    ClassicEditor
        .create(document.querySelector('#txtDescripcion'))
        .catch(error => {
            console.error(error);
        });
</script>
<?php include_once("footer.php"); ?>