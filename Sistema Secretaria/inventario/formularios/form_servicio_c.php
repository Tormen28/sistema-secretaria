<?php
if (isset($_POST['submit'])) {
    // Configuración de conexión
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "secretaria";
    
    // Obtener datos del formulario
    $periodo = $_POST['semestre'] . '-' . $_POST['anio'];
    $titulo = $_POST['servicio'];
    $ubicacion = $_POST['ubicacion'];
    $tutor = $_POST['tutor'];
    $estudiantes = $_POST['estudiantes'] ?? [];

    // Crear conexión
    $conn = new mysqli($host, $user, $pass, $db);
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Insertar proyecto principal
    $stmt = $conn->prepare("INSERT INTO proyectos (periodo, titulo, ubicacion, tutor) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }
    
    $stmt->bind_param("ssss", $periodo, $titulo, $ubicacion, $tutor);
    
    if (!$stmt->execute()) {
        die("Error al guardar proyecto: " . $stmt->error);
    }
    
    $id_proyecto = $stmt->insert_id;
    $stmt->close();

    if (!empty($estudiantes)) {
        // Preparar consulta para estudiantes
        $stmt = $conn->prepare("INSERT INTO estudiantes_proyectos 
            (id_proyecto, carrera, cedula, nombres, apellidos) 
            VALUES (?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            die("Error en la preparación: " . $conn->error);
        }

        // Vincular parámetros
        $stmt->bind_param("issss", 
            $id_proyecto,
            $carrera,
            $cedula,
            $nombres,
            $apellidos
        );

        // Procesar cada estudiante
        foreach ($estudiantes as $est) {
            // Validar campos requeridos
            if (empty($est['carrera']) || empty($est['cedula'])) {
                echo "<script>alert('Complete todos los campos requeridos');</script>";
                continue;
            }
            
            // Limpiar y validar datos
            $carrera = $conn->real_escape_string($est['carrera']);
            $cedula = str_replace(['.', '-'], '', $est['cedula']);
            $nombres = $conn->real_escape_string($est['nombres'] ?? '');
            $apellidos = $conn->real_escape_string($est['apellidos'] ?? '');

            // Ejecutar inserción
            if (!$stmt->execute()) {
                echo "<script>alert('Error al guardar estudiante: " . $stmt->error . "');</script>";
            }
        }

        $stmt->close();
        echo "<script>alert('Proyecto y estudiantes registrados exitosamente');</script>";
    } else {
        echo "<script>alert('No hay estudiantes para registrar');</script>";
    }
    
    $conn->close();
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
        <!-- Campos de configuración del periodo -->
        <div class="input-group">
            <label for="nombre">Ingrese el Periodo:</label>
            <select id="semestre" name="semestre">
                <option value="1">Primer Periodo</option>
                <option value="2">Segundo Periodo</option>
            </select>
        </div>

        <div class="input-group">
            <label for="nombre">Ingrese año del Periodo:</label>
            <select id="anio" name="anio"></select>
        </div>

        <input type="hidden" id="periodo" name="periodo">

        <div class="input-group">
          <label for="nombre">Titulo del Servicio Comunitario:</label>
          <input type="text" name="servicio" placeholder="Titulo del Proyecto de Servicio Comunitario" id="nombre" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" required autocomplete="off" required><br>
        </div>

        <div class="input-group">
          <label for="nombre">Ubicacion del Servicio Comunitario:</label>
          <input type="text" name="ubicacion" placeholder="Ubicacion del Proyecto de Servicio Comunitario" id="nombre" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" required autocomplete="off" required><br>
        </div>
        
        <div class="input-group">
          <label for="nombre">Tutor del Servicio Comunitario:</label>
          <input type="text" name="tutor" placeholder="Nombre del tutor" id="nombre" oninput="this.value = this.value.replace(/[^A-Za-z áéíóúÁÉÍÓÚñÑ]/g, '')" required autocomplete="off" required><br><br>
        </div>

        <!-- Generador de estudiantes -->
        <div class="input-group">
            <label for="nombre">Número de estudiantes:</label>
            <input type="number" id="numStudents" min="1" value="1">
            <button type="button" onclick="generarEstudiantes()">Generar Campos Estudiantes</button>
        </div>

        <!-- Contenedor para los campos de estudiantes -->
        <div id="formContainer"></div>

        <script>
        // Configuración inicial del año
        const anioSelect = document.getElementById('anio');
        const currentYear = new Date().getFullYear();
        for (let year = currentYear + 5; year >= 2025; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            anioSelect.appendChild(option);
        }
        anioSelect.value = currentYear;
        document.getElementById('semestre').value = new Date().getMonth() < 6 ? '1' : '2';
        actualizarPeriodo();

       // Función para configurar el formato de cédula
function setupCedulaInput(tipoDocSelect, cedulaInput) {
    function getPrefix() {
        return tipoDocSelect.value + '-';
    }

    function updateCedulaFormat() {
        const prefix = getPrefix();
        const currentValue = cedulaInput.value.replace(/^[VE]-/, '');
        cedulaInput.value = prefix + currentValue;
    }

    cedulaInput.addEventListener('focus', function(e) {
        const prefix = getPrefix();
        if (!this.value.startsWith(prefix)) {
            this.value = prefix + this.value.replace(/[^0-9]/g, '');
        }
    });

    cedulaInput.addEventListener('keydown', function(e) {
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

    cedulaInput.addEventListener('input', function(e) {
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

    tipoDocSelect.addEventListener('change', function() {
        updateCedulaFormat();
        cedulaInput.dispatchEvent(new Event('input'));
    });
}

// Función para generar estudiantes con el nuevo formato
// Función para generar estudiantes con names corregidos
function generarEstudiantes() {
    const numStudents = document.getElementById('numStudents').value;
    const container = document.getElementById('formContainer');
    
    if(container.children.length > 0 && !confirm('¿Generar nuevos campos borrará los existentes. Continuar?')) return;
    
    container.innerHTML = '';

    for (let i = 0; i < numStudents; i++) {
        const studentDiv = document.createElement('div');
        studentDiv.className = 'student-section';
        studentDiv.innerHTML = `
            <h3>Estudiante ${i + 1}</h3>
            <div class="input-group">
                <label>Carrera:</label>
                <select class="carrera-select" name="estudiantes[${i}][carrera]">
                    <option value="">Seleccione carrera...</option>
                    <option value="ads">ADS</option>
                    <option value="desastres">Desastres</option>
                    <option value="economia">Economía</option>
                    <option value="enfermeria">Enfermería</option>
                    <option value="ingenieria_civil">Ingeniería Civil</option>
                </select>
            </div>
            <div class="input-group">
                <label>Cédula:</label>
                <div class="cedula-container">
                    <select class="tipo-doc" name="estudiantes[${i}][tipo_doc]">
                        <option value="V">V</option>
                        <option value="E">E</option>
                    </select>
                    <input type="text" 
                           class="cedula-input"
                           name="estudiantes[${i}][cedula]"
                           placeholder="Ej: V-12.345.678"
                           required 
                           autocomplete="off">
                </div>
                <button type="button" class="btn-buscar">Buscar</button>
            </div>
            <div class="input-group">
                <label>Nombres:</label>
                <input type="text" class="nombres" name="estudiantes[${i}][nombres]" readonly>
                <label>Apellidos:</label>
                <input type="text" class="apellidos" name="estudiantes[${i}][apellidos]" readonly>
            </div>
        `;
        
        // Configurar eventos de cédula (se mantiene igual)
        // Configurar botón buscar (se mantiene igual)
        
        container.appendChild(studentDiv);
    

        
        // Configurar eventos de cédula
        const tipoDoc = studentDiv.querySelector('.tipo-doc');
        const cedulaInput = studentDiv.querySelector('.cedula-input');
        setupCedulaInput(tipoDoc, cedulaInput);
        
        // Configurar botón buscar
        const btnBuscar = studentDiv.querySelector('.btn-buscar');
        btnBuscar.addEventListener('click', () => buscarEstudiante(studentDiv));
        
        container.appendChild(studentDiv);
    }
}

// Función de búsqueda mejorada
function buscarEstudiante(studentDiv) {
    const carrera = studentDiv.querySelector('.carrera-select').value;
    const cedula = studentDiv.querySelector('.cedula-input').value;

    if (!carrera || !cedula) {
        alert('Complete todos los campos requeridos');
        return;
    }

    fetch('buscar_estudiante.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `carrera=${encodeURIComponent(carrera)}&cedula=${encodeURIComponent(cedula)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            studentDiv.querySelector('.nombres').value = data.nombre || '';
            studentDiv.querySelector('.apellidos').value = data.apellidos || '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error en la conexión');
    });
}

// Función para calcular definitiva
function calcularDefinitiva(input) {
    const studentDiv = input.closest('.student-section');
    const notas = Array.from(studentDiv.querySelectorAll('.nota'))
                      .map(input => parseFloat(input.value) || 0);
    
    const definitiva = notas.reduce((a, b) => a + b, 0) / 4;
    studentDiv.querySelector('.definitiva').value = definitiva.toFixed(2);
}

// Función de validación de notas
function validarNota(input) {
    let value = parseFloat(input.value);
    if (value < 0) input.value = 0;
    if (value > 20) input.value = 20;
    if (isNaN(value)) input.value = '';
}

        </script>

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