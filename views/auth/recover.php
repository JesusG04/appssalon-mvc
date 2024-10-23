<h1 class="nombre-pagina">Recuperar Contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuacion</p>

<?php require_once __DIR__ .'/../templates/alertas.php'?>
<?php
if ($error) {
    return;
} ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña</label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Constraseña nueva" />
    </div>
    <input type="submit" value="Guardar contraseña" class="boton">

</form>
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>
    <a href="/create-account">¿Aun no tienes una cuenta? Crear una</a>
</div>