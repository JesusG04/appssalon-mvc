<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php require_once __DIR__.'/../templates/alertas.php' ?>

<form class="fromulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Correo electronico"
            name="email" 
            value="<?php echo s($auth->email) ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input
            type="password"
            id="password"
            placeholder="Contraseña"
            name="password" 
        />
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/create-account">¿Aun no tienes una cuenta? Crear una</a>
    <a href="/forget">¿Olvidaste tu contraseña?</a>
</div>