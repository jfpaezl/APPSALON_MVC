<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripccion-pagina">Llena el siguiente formulario para crear una cuenta</p>   

<?php
    include_once __DIR__."/../templates/alertas.php";
?>

<form action="/crear-cuenta" method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            placeholder="Tu Nombre"
            name="nombre"
            value="<?PHP echo s($usuario->nombre); ?>"
        />
    </div>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            placeholder="Tu Apellido"
            name="apellido"
            value="<?PHP echo s($usuario->apellido); ?>"
        />
    </div>
    <div class="campo">
        <label for="telefono">Telefono</label>
        <input 
            type="tel"
            id="telefono"
            placeholder="Tu Telefono"
            name="telefono"
            value="<?PHP echo s($usuario->telefono); ?>"
        />
    </div>
    <div class="campo">
        <label for="email">E-mail</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu E-mail"
            name="email"
            value="<?PHP echo s($usuario->email); ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu Password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inisicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>