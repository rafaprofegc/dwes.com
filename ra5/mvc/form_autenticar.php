<?php
echo <<<FORM
<h3>Autentíquese para una experiencia más personal</h3>
<form method="POST" action="{$_SERVER['PHP_SELF']}">
    <!-- <input type="hidden" name="idp" id="idp" value="autenticar"> -->
    <label for="email">Email</label>
    <input type="text" name="email" id="email" size="30">

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size="10">

    <button type="submit" name="idp" id="idp1" value="autenticar">Inicia sesión</button>
    <button type="submit" name="idp" id="idp2" value="registrar">Regístrese</button>
</form>
FORM;
?>