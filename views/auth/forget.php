<h1 class="nombre-pagina">Olvide mi Contraseña</h1>
<p class="descripcion-pagina">Reestablecee tu contraseña escribiendo tu email a continuación</p>

<?php
require_once __DIR__ . '/../templates/alertas.php'
?>

<form action="/forget" class="formulario" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="E-mail">
    </div>

    <input type="submit" value="Enviar correo" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/create-account">¿Aun no tienes una cuenta? Crear una</a>
</div>