<?php

    require '../funciones.php';

  

    $registracion = new Registration();

    if (isset($_POST['submit'])) {
       $resultadoregistro = $registracion->estudiantes(
        $_POST['cedula'],
        $_POST['nombre'],
        $_POST['apellidos'],
        $_POST['carrera'],
        $_POST['fecha_nac'],
        $_POST['genero'],
        $_POST['militar'],
        $_POST['rango'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['direccion'],
        $_POST['fecha_ing'],
        $_POST['fecha_egr']
       );
       if ($resultadoregistro == 1) {
        echo "<script> alert('Registro exitoso'); </script>";
       } elseif ($resultadoregistro == 10){
        echo "<script> alert('Registro ya existe'); </script>";

       } elseif ($resultadoregistro == 100){
        echo "<script> alert('Hubo un fallo'); </script>";
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../inventario_styles.css">

</head>
<body>

<nav class="logo-nav">
    <a href="../index.php" class="logo-link"><i class="fas fa-home"></i> Secretaría</a>
    <img src="../../css/logo1.jpg" alt="Logo" class="logo-img">
</nav>

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

       
        </li>

        <!-- Menú de Salida -->
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <i class="fas fa-cog"></i>
                <span></span>
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


    <h2>REGISTRO DE ESTUDIANTES</h2>
    
    <form action="" method="POST">

    <div class="input-group">
    <label for="tipo-doc">Tipo:</label>
<select id="tipo-doc">
  <option value="V">V</option>
  <option value="E">E</option>
</select>        
</div>

    <div class="input-group">
          <label for="cedula">C.I:</label>
          <input type="text" id="cedula" name="cedula" placeholder="Ingrese Cédula" required autocomplete="off">

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


            </div> 


            <div class="input-group">
          <label for="nombre">Nombres:</label>
          <input type="text" name="nombre" placeholder="Ingrese sus Nombres" id="nombre" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" required autocomplete="off" required><br><br>
        </div>
        

            <div class="input-group">
          <label for="Apellidos">Apellidos:</label>
          <input type="text" name="apellidos" id="apellidos" placeholder="Ingrese sus Apellidos" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="carrera">Carrera:</label>
          <select name="carrera" id="carrera" required>
            <option value="" hidden="">Seleccione la Carrera</option>
            <option value="ads">Análisis y diseño de Sistemas</option>
            <option value="enfermeria">Enfermería</option>
            <option value="ingenieria_civil">Ingeniería Civil</option>
            <option value="desastres">Desastres</option>
            <option value="economia">Economía social</option>
          </select><br><br>
        </div>

        <div class="input-group">
          <label for="fecha_nac">Fecha de Nacimiento:</label>
          <input type="date" name="fecha_nac" id="fecha_nac" required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="Genero">Genero:</label>
          <select name="genero" id="genero" required>
            <option value="" hidden="">Seleccione un genero</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
          </select><br><br>
        </div>

        <div class="input-group">
          <label for="Militar">Militar:</label>
          <select name="militar" id="militar">
            <option value="" hidden=""></option>
            <option value="Si">Si</option>
            <option value="No">No</option>
          </select><br><br>
        </div>

        <div class="input-group">
          <label for="Rango">Indique el Rango:</label>
          <input type="text" name="rango" id="rango" placeholder="Rango" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')"  autocomplete="off" ><br><br>
        </div>


        <div class="input-group">
          <label for="telefono">Teléfono:</label>
          <input type="text" id="telefono" name="telefono" placeholder="Ingresar Numero de Telefono"  autocomplete="off">

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
          <input type="email" name="correo" placeholder="Ingrese su Correo" id="correo" required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="Direccion">Dirección:</label>
          <input type="text" name="direccion" id="direccion" placeholder="Indique su dirección" required autocomplete="off" required><br><br>
        </div>

        <div class="input-group">
          <label for="Fecha_ing">Fecha de ingreso:</label>
          <input type="date" name="fecha_ing" id="fecha_ing" required autocomplete="off" required><br><br>
        </div>
        
        <div class="input-group">
          <label for="Fecha">Fecha de Egreso:</label>
          <input type="date" name="fecha_egr" id="fecha"  autocomplete="off"><br><br>
        </div>

        <div class="input-group">
        </div>

        <div class="input-group">
          <div class="buttons">
            <button type="reset">Limpiar</button>
            <button type="submit" name="submit">Registrar</button>
          </div>
        </div>

    </form>

</body>
</html>