<?php

/*Inserta el primer usuario de todos en caso de que instalemos el sistema
en una nueva computadora y no tengamos ningun usuario en la BBDD*/

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "admin";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Administrad";
$usuario->apellido = "";
$usuario->correo = "admin@correo.com";
$usuario->insertar();


echo "Usuario insertado.";
?>