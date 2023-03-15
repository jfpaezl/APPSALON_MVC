<h1 class="nombre-pagina">Recuperar password</h1>
<p class="descripccion-pagina">Coloca tu nuevos password a continuación</p>   

<?php
    include_once __DIR__."/../templates/alertas.php";
?>
<?php if($erorr) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu nuevo password"
        />
    </div>
    <input type="submit" class="boton" value="Guardar nuevo password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inisicia Sesión</a>
</div>



