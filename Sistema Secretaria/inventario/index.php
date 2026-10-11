<?php
// Incluir verificación de sesión
require_once '../includes/session_check.php';
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Secretaria</title>
    <link rel="stylesheet" href="styless3.css" />
    <script>
      // Prevenir que el usuario pueda volver atrás después de cerrar sesión
      history.pushState(null, null, location.href);
      window.onpopstate = function() {
        history.go(1);
      };
    </script>
  </head>
  <body>
    <header class="header">
      <div class="container">
        <div class="btn-menu">
          <label for="btn-menu">☰</label>
        </div>
        <div class="logo">
          <h1>Secretaria</h1>
        </div>
        <nav class="menu">
          <a href="../login/cerrarsesion.php">Cerrar Sesión</a>
        </nav>
      </div>
    </header>
    <div class="capa"></div>
    
    <!-- Menú lateral -->
    <input type="checkbox" id="btn-menu" />
    <div class="container-menu">
      <div class="cont-menu">
        <nav>
          <a href="./listas/lista_usuario.php">Lista de Usuarios</a>
          
          <!-- Grupo Registrar -->
          <div class="menu-item">
            <a href="#">Carreras ▼</a>
            <div class="sub-menu">
              <a href="listas/lista_ADS.php">Análisis y Diseño de Sistemas</a>
              <a href="listas/lista_enfermeria.php">Enfermería</a>
              <a href="listas/lista_ingenieria_civil.php">Ingeniería Civil</a>
              <a href="listas/lista_desastres.php">Administración de Desastres</a>
              <a href="listas/lista_economia.php">Economia Social</a>
              
            </div>
          </div>
          
          <!-- Grupo Listados -->
          <div class="menu-item">
            <a href="formularios/form_estudiantes.php">Registro de Estudiante </a>
            <a href="formularios/form_servicio_c.php">Registro de Servicio Comunitario </a>
            <a href="listas/lista_proyectos_servicio_comunitario.php">Lista de Servicio Comunitario </a>
            <a href="formularios/form_pasantias.php">Registro de Pasantia </a>
             <a href="listas/lista_pasantes.php">Lista de Pasantia </a>
            <a href="formularios/form_materia.php">Materia</a>
            <a href="listas/lista_articulo.php">Articulo 118 </a>
            <a href="RespaldoRestore/php/index.php">Respaldo de Base de Datos </a>
          </div>
          
        </nav>
        <label for="btn-menu">✖️</label>
      </div>
    </div>
  </body>
</html>