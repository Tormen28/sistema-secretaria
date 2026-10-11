<?php
require '../funciones.php';
include("conexion.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Secretaría</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../usuarios_list_stylesss.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../inventario_styles.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<nav class="menu">
    <ul class="menu-list">
        <!-- Menú Estudiantes -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-user-graduate"></i>
                <span>Estudiantes</span>
                <span class="submenu-icon">&#9660;</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../formularios/form_estudiantes.php" class="submenu-link">
                        <i class="fas fa-user-plus"></i> Registrar Estudiante
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_ADS.php" class="submenu-link">
                        <i class="fas fa-list"></i> Análisis y Diseño de Sistemas
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_enfermeria.php" class="submenu-link">
                        <i class="fas fa-user-nurse"></i> Enfermería
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_ingenieria_civil.php" class="submenu-link">
                        <i class="fas fa-hard-hat"></i> Ingeniería Civil
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_desastres.php" class="submenu-link">
                        <i class="fas fa-shield-alt"></i> Adm. de Desastres
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_economia.php" class="submenu-link">
                        <i class="fas fa-chart-line"></i> Economía Social
                    </a>
                </li>
            </ul>
        </li>

        <!-- Menú Servicio Comunitario -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-hands-helping"></i>
                <span>Servicio Comunitario</span>
                <span class="submenu-icon">&#9660;</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../formularios/form_servicio_c.php" class="submenu-link">
                        <i class="fas fa-plus-circle"></i> Registrar
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_proyectos_servicio_comunitario.php" class="submenu-link">
                        <i class="fas fa-list"></i> Lista
                    </a>
                </li>
            </ul>
        </li>

        <!-- Menú Pasantías -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-briefcase"></i>
                <span>Pasantías</span>
                <span class="submenu-icon">&#9660;</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../formularios/form_pasantias.php" class="submenu-link">
                        <i class="fas fa-plus-circle"></i> Registrar
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="../listas/lista_pasantes.php" class="submenu-link">
                        <i class="fas fa-list"></i> Lista
                    </a>
                </li>
            </ul>
        </li>

        <!-- Menú Materia -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-book"></i>
                <span>Materias</span>
                <span class="submenu-icon">&#9660;</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../formularios/form_materia.php" class="submenu-link">
                        <i class="fas fa-plus-circle"></i> Registrar
                    </a>
                </li>
                <li class="submenu-item">
                    <a href="#" class="submenu-link">
                        <i class="fas fa-list"></i> Lista
                    </a>
                </li>
            </ul>
        </li>



        <!-- Menú de Salida -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-cog"></i>
                <span>SALIR</span>
                <span class="submenu-icon">&#9660;</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../../login/cerrarsesion.php" class="submenu-link">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<div class="container-main">
  <?php 
  

  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  // Validar parámetros GET
  if (!isset($_GET['cedula']) || !isset($_GET['tabla'])) {
      die("Error: Parámetros cedula o tabla no recibidos");
  }
  
  // Sanitizar y validar
  $cedula = mysqli_real_escape_string($conex, $_GET['cedula']);
  $tabla = mysqli_real_escape_string($conex, $_GET['tabla']);
  
  // Lista blanca de tablas permitidas
  $tablas_permitidas = ['ads', 'enfermeria', 'ingenieria_civil', 'desastres', 'economia'];
  if (!in_array($tabla, $tablas_permitidas)) {
      die("Error: Tabla no válida");
  }
  
  // Procesar actualización
  if (isset($_POST['enviar'])) {
      // Recoger y sanitizar datos POST
      $nueva_cedula = mysqli_real_escape_string($conex, $_POST['cedula']);
      $nombre = mysqli_real_escape_string($conex, $_POST['nombre']);
      $apellidos = mysqli_real_escape_string($conex, $_POST['apellidos']);
      $carrera = mysqli_real_escape_string($conex, $_POST['carrera']);
      $fecha_nac = mysqli_real_escape_string($conex, $_POST['fecha_nac']);
      $genero = mysqli_real_escape_string($conex, $_POST['genero']);
      $militar = mysqli_real_escape_string($conex, $_POST['militar']);
      $rango = mysqli_real_escape_string($conex, $_POST['rango']);
      $telefono = mysqli_real_escape_string($conex, $_POST['telefono']);
      $correo = mysqli_real_escape_string($conex, $_POST['correo']);
      $direccion = mysqli_real_escape_string($conex, $_POST['direccion']);
      $fecha_ing = mysqli_real_escape_string($conex, $_POST['fecha_ing']);
      $fecha_egr = mysqli_real_escape_string($conex, $_POST['fecha_egr']);
      $estado = mysqli_real_escape_string($conex, $_POST['estado']);
  
      // Consulta preparada para actualización
      $sql = "UPDATE $tabla SET
              cedula = ?,
              nombre = ?,
              apellidos = ?,
              carrera = ?,
              fecha_nac = ?,
              genero = ?,
              militar = ?,
              rango = ?,
              telefono = ?,
              correo = ?,
              direccion = ?,
              fecha_ing = ?,
              fecha_egr = ?,
              estado = ?
              WHERE cedula = ?";
  
      $stmt = mysqli_prepare($conex, $sql);
      mysqli_stmt_bind_param($stmt, "sssssssssssssss",
          $nueva_cedula,
          $nombre,
          $apellidos,
          $carrera,
          $fecha_nac,
          $genero,
          $militar,
          $rango,
          $telefono,
          $correo,
          $direccion,
          $fecha_ing,
          $fecha_egr,
          $estado,
          $cedula
      );
  
      if (mysqli_stmt_execute($stmt)) {
          echo "<script>
                  alert('Registro actualizado correctamente');
                  window.location.href = '../listas/lista_".$tabla.".php';
                </script>";
      } else {
          echo "<script>
                  alert('Error al actualizar: ".mysqli_error($conex)."');
                  history.back();
                </script>";
      }
      exit();
  
  } else {
      // Obtener datos para edición
      $sql = "SELECT * FROM $tabla WHERE cedula = ?";
      $stmt = mysqli_prepare($conex, $sql);
      mysqli_stmt_bind_param($stmt, "s", $cedula);
      mysqli_stmt_execute($stmt);
      $resultado = mysqli_stmt_get_result($stmt);
      
      if (!$row = mysqli_fetch_assoc($resultado)) {
          die("Error: Registro no encontrado");
      }
  
  ?>
  





    <h2>Editar Estudiantes</h2>
    
    <form method="POST">
    <input type="hidden" name="tabla" value="<?= $tabla ?>">
    

    <div class="input-group">
    <label for="tipo-doc">Tipo:</label>
<select id="tipo-doc">
  <option value="V">V</option>
  <option value="E">E</option>
</select>        
</div>

    <div class="input-group">
          <label for="cedula">C.I:</label>
          <input type="text" name="cedula" value="<?= htmlspecialchars($row['cedula']) ?>" required>

<script>
const tipoDoc = document.getElementById('tipo-doc');
const cedula = document.getElementById('cedula');

function getPrefix() {
  return tipoDoc.value + '-';
}

cedula.addEventListener('focus', function(e) {
  const prefix = getPrefix();
  if (!this.value.startsWith(prefix)) {
    this.value = prefix + this.value.replace(/[^0-9]/g, '');
  }
});

cedula.addEventListener('keydown', function(e) {
  const prefix = getPrefix();
  const prefixLength = prefix.length;
  const selectionStart = this.selectionStart;
  
  if (selectionStart < prefixLength) {
    if (![9, 16, 17, 18, 37, 38, 39, 40].includes(e.keyCode)) {
      e.preventDefault();
      return;
    }
  }

  if (selectionStart >= prefixLength) {
    const allowedKeys = [8, 9, 13, 37, 38, 39, 46];
    const isNumber = (e.keyCode >= 48 && e.keyCode <= 57) || 
                    (e.keyCode >= 96 && e.keyCode <= 105);
    
    if (!allowedKeys.includes(e.keyCode) && !isNumber) {
      e.preventDefault();
    }
  }
});

cedula.addEventListener('input', function(e) {
  const prefix = getPrefix();
  const prefixLength = prefix.length;
  let input = e.target;
  let caret = input.selectionStart;
  
  let numbers = input.value.slice(prefixLength).replace(/[^0-9]/g, '');
  
  let formatted = numbers.split('').reverse().join('')
                       .match(/.{1,3}/g)
                       ?.join('.')
                       .split('').reverse().join('') || '';
  
  input.value = prefix + formatted;
  
  let numbersCaret = Math.max(caret - prefixLength, 0);
  let dotsAdded = Math.floor((numbers.length - 1) / 3);
  let newCaret = Math.min(
    prefixLength + numbersCaret + dotsAdded,
    input.value.length
  );
  
  input.setSelectionRange(newCaret, newCaret);
});

// Actualizar el prefijo cuando cambia el tipo de documento
tipoDoc.addEventListener('change', function() {
  const currentNumbers = cedula.value.replace(/^[VE]-/, '').replace(/[^0-9]/g, '');
  cedula.value = getPrefix() + currentNumbers;
  cedula.dispatchEvent(new Event('input'));
});
</script>





            <div class="input-group">
    <label for="nombre">Nombres:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>
    <br><br>
</div>
        

            <div class="input-group">
          <label for="Apellidos">Apellidos:</label>
          <input type="text" name="apellidos" value="<?= htmlspecialchars($row['apellidos']) ?>" required>
          </div>

        <div class="input-group">
        <label for="carrera">Carrera:</label>
        <select name="carrera" required>
            <?php foreach ($tablas_permitidas as $carrera_option): ?>
                <option value="<?= $carrera_option ?>" <?= ($row['carrera'] == $carrera_option) ? 'selected' : '' ?>>
                    <?= strtoupper(str_replace('_', ' ', $carrera_option)) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

        <div class="input-group">
          <label for="fecha_nac">Fecha de Nacimiento:</label>
          <input type="date" name="fecha_nac" value="<?= $row['fecha_nac'] ?>" required>
          </div>

        <div class="input-group">
          <label for="Genero">Genero:</label>
          <select name="genero" required>
            <option value="Masculino" <?= ($row['genero'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
            <option value="Femenino" <?= ($row['genero'] == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
        </select><br><br>
        </div>

        <div class="input-group">
          <label for="Militar">Militar:</label>
          <select name="militar" required>
            <option value="Si" <?= ($row['militar'] == 'Si') ? 'selected' : '' ?>>Si</option>
            <option value="No" <?= ($row['militar'] == 'No') ? 'selected' : '' ?>>No</option>
        </select><br><br>
        </div>

        <div class="input-group">
          <label for="Rango">Indique el Rango:</label>
          <input type="text" name="rango" id="rango" placeholder="Rango" value="<?= $row['rango'] ?>"  oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')"  autocomplete="off" ><br><br>
        </div>


        <div class="input-group">
          <label for="telefono">Teléfono:</label>
          <input type="text" id="telefono" name="telefono" value="<?= $row['telefono'] ?>"  placeholder="Ingresar Numero de Telefono"  autocomplete="off">

<script>
document.getElementById('telefono').addEventListener('keydown', function(e) {
    // Permitir solo números y teclas de control
    if (
        [8, 9, 13, 37, 38, 39, 46].includes(e.keyCode) ||  // Teclas de control
        (e.keyCode >= 48 && e.keyCode <= 57) ||            // Números teclado principal
        (e.keyCode >= 96 && e.keyCode <= 105)               // Números teclado numérico
    ) {
        return;
    }
    e.preventDefault();
});

document.getElementById('telefono').addEventListener('input', function(e) {
    const input = e.target;
    const caret = input.selectionStart;
    const numbers = input.value.replace(/[^0-9]/g, '');
    
    // Formatear con guiones cada 4 dígitos
    const formatted = numbers.split('').reduce((acc, digit, index) => {
        return (index % 4 === 0 && index !== 0) 
            ? acc + '-' + digit 
            : acc + digit;
    }, '');
    
    // Ajustar posición del cursor
    const dashesInValue = (formatted.match(/-/g) || []).length;
    const dashesBeforeCaret = (input.value.slice(0, caret).match(/-/g) || []).length;
    const newCaret = caret + (dashesInValue - dashesBeforeCaret);
    
    input.value = formatted;
    input.setSelectionRange(newCaret, newCaret);
});
</script>

        </div>

        <div class="input-group">
          <label for="Correo">Correo Gmail:</label>
          <input type="email" name="correo" value="<?= $row['correo'] ?>"  placeholder="Ingrese su Correo" id="correo" required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="Direccion">Dirección:</label>
          <input type="text" name="direccion" value="<?= $row['direccion'] ?>"  id="direccion" placeholder="Indique su dirección"  required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="Fecha_ing">Fecha de ingreso:</label>
          <input type="date" name="fecha_ing" value="<?= $row['fecha_ing'] ?>"  id="fecha_ing" required autocomplete="off" required><br><br>
        </div>
        
        <div class="input-group">
          <label for="Fecha">Fecha de Egreso:</label>
          <input type="date" name="fecha_egr" value="<?= $row['fecha_egr'] ?>"  id="fecha"  autocomplete="off"><br><br>
        </div>

        <div class="input-group">
          <label for="Estado">Estado:</label>
          <select name="estado" required>
            <option value="activo" <?= ($row['estado'] == 'activo') ? 'selected' : '' ?>>activo</option>
            <option value="inactivo" <?= ($row['estado'] == 'inactivo') ? 'selected' : '' ?>>inactivo</option>
        </select><br><br>
        </div>

        <div class="input-group">
        </div>

        <div class="input-group">
          <div class="buttons">
            <button type="reset">Limpiar</button>
            <button type="submit" name="enviar">Registrar</button>
          </div>
        </div>

</form>
<?php
  }
    ?>
</div>
</body>
</html>
