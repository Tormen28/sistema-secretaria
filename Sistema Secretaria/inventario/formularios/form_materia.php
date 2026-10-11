<?php
if (isset($_POST['submit'])) {
    $tipo_materia = $_POST['tipo_materia'];
    // Configuración de conexión
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "secretaria";
    
    // Obtener datos del formulario
    $periodo = $_POST['semestre'] . '-' . $_POST['anio'];
    $nombre_materia = $_POST['materia'];
    $codigo_materia = $_POST['codigo_materia'];
    $estudiantes = $_POST['estudiantes'] ?? [];

    // Filtrar estudiantes: solo los que tienen definitiva
    $estudiantes = array_filter($estudiantes, function($est) {
        return isset($est['definitiva']) && trim($est['definitiva']) !== '';
    });

    // Crear conexión
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    if (!empty($estudiantes)) {
        // Consulta modificada (eliminadas notas 1-4)
        $stmt = $conn->prepare("INSERT INTO notas (
            periodo, 
            nombre_materia, 
            codigo, 
            cedula, 
            nombres, 
            apellidos, 
            carrera, 
            definitiva,
            tipo_materia
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Tipos de parámetros ajustados
        $stmt->bind_param("sssssssds", 
            $periodo,
            $nombre_materia,
            $codigo_materia,
            $cedula,
            $nombres,
            $apellidos,
            $carrera,
            $definitiva,
            $tipo_materia
        );

        foreach ($estudiantes as $est) {        
            $carrera = $est['carrera'];
            $cedula = $est['cedula'];
            $nombres = $est['nombres'] ?? '';
            $apellidos = $est['apellidos'] ?? '';
            $definitiva = (float)($est['definitiva'] ?? 0);

            // Verificar si ya existe el registro
            $check_sql = "SELECT COUNT(*) AS count FROM notas 
                          WHERE periodo = ? 
                          AND codigo = ? 
                          AND cedula = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("sss", $periodo, $codigo_materia, $cedula);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();
            $check_stmt->close();

            if ($row['count'] > 0) {
                echo "<script>alert('Error: El estudiante con cédula $cedula ya está registrado en esta materia y período.');</script>";
                continue; // Saltar este estudiante
            }

            if (!$stmt->execute()) {
                echo "<script>alert('Error al guardar: " . $stmt->error . "');</script>";
            }
        }

        $stmt->close();
        echo "<script>alert('Registros guardados exitosamente');</script>";
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
        <li class="menu-item has-submenu">
        <a href="../formularios/form_materiaI.php" class="menu-link">Registro individual<span class="submenu-icon">&#9660;</span></a>
        
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
        <div class="form-top-fields">
            <div class="input-group">
                <label for="semestre">Periodo:</label>
                <select id="semestre" name="semestre">
                    <option value="1">Primer Periodo</option>
                    <option value="2">Segundo Periodo</option>
                </select>
            </div>

            <div class="input-group">
                <label for="anio">Año del Periodo:</label>
                <select id="anio" name="anio"></select>
            </div>


            <div class="input-group">
                <label for="selectorPensum">Pensum:</label>
                <select id="selectorPensum" name="pensum" required>
                    <option value="">-- Elija un pensum --</option>
                    <option value="pensum_ads">ADS</option>
                    <option value="pensum_civil">Civil</option>
                    <option value="pensum_desastres">Desastres</option>
                    <option value="pensum_economia">Economía</option>
                    <option value="pensum_enfermeria">Enfermería</option>
                </select>
            </div>

            <div class="input-group">
                <label for="selectorMaterias">Materia:</label>
                <select id="selectorMaterias" name="materia" required>
                    <option value="">-- Primero seleccione un pensum --</option>
                </select>
            </div>


            <div class="input-group">
                <label for="codigoMateria">Código de Materia:</label>
                <input type="text" id="codigoMateria" name="codigo_materia" readonly>
            </div>

            <input type="hidden" id="periodo" name="periodo">

            <div class="input-group">
                <label for="numStudents">Cantidad de Estudiantes:</label>
                <input type="number" id="numStudents" min="1" max="10" value="1">
                <button type="button" onclick="generarEstudiantes()">Generar Campos</button>
            </div>
        </div>

        <div id="formContainer"></div>

        <script>
document.getElementById('selectorPensum').addEventListener('change', function() {
    const pensum = this.value;
    const selectorMaterias = document.getElementById('selectorMaterias');
    const codigoMateria = document.getElementById('codigoMateria');
    const tipoMateria = document.getElementById('tipoMateria'); // Campo oculto

    if (pensum) {
        fetch(`get_materias.php?pensum=${pensum}`)
            .then(response => {
                if (!response.ok) throw new Error('Error al cargar materias');
                return response.json();
            })
            .then(data => {
                selectorMaterias.innerHTML = '<option value="">-- Seleccione materia --</option>';
                data.forEach(materia => {
                    const option = document.createElement('option');
                    option.value = materia.materia; // Nombre de la materia
                    option.textContent = `${materia.materia} (${materia.codigo})`; // Muestra nombre y código
                    option.dataset.codigo = materia.codigo; 
                    option.dataset.tipo = materia.tipo_materia; // Almacena el tipo
                    selectorMaterias.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                selectorMaterias.innerHTML = '<option value="">Error al cargar materias</option>';
                codigoMateria.value = '';
                tipoMateria.value = '';
            });
    }
});

// Actualizar campos al seleccionar materia
document.getElementById('selectorMaterias').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('codigoMateria').value = selectedOption.dataset.codigo || '';
    document.getElementById('tipoMateria').value = selectedOption.dataset.tipo || ''; // Tipo automático
});

        // Configuración inicial del año
        const anioSelect = document.getElementById('anio');
        const currentYear = new Date().getFullYear();
        for (let year = currentYear + 5; year >= 2020; year--) {
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
            <div class="student-fields">
                <div class="input-group">
                    <label for="carrera_${i}">Carrera:</label>
                    <select id="carrera_${i}" class="carrera-select" name="estudiantes[${i}][carrera]" required>
                        <option value="">Seleccione carrera...</option>
                        <option value="ads">Análisis y Diseño de Sistemas</option>
                        <option value="desastres">Administración de Desastres</option>
                        <option value="economia">Economía Social</option>
                        <option value="enfermeria">Enfermería</option>
                        <option value="ingenieria_civil">Ingeniería Civil</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="cedula_${i}">Cédula:</label>
                    <div class="cedula-container">
                        <select id="tipo_doc_${i}" class="tipo-doc" name="estudiantes[${i}][tipo_doc]">
                            <option value="V">V</option>
                            <option value="E">E</option>
                        </select>
                        <input type="text" 
                               id="cedula_${i}"
                               class="cedula-input"
                               name="estudiantes[${i}][cedula]"
                               placeholder="Ej: V-12.345.678"
                               required
                               autocomplete="off">
                    </div>
                    <button type="button" class="btn-buscar">Buscar</button>
                </div>
                <div class="input-group">
                    <label for="nombres_${i}">Nombres:</label>
                    <input type="text" id="nombres_${i}" class="nombres" name="estudiantes[${i}][nombres]" readonly>
                </div>
                <div class="input-group">
                    <label for="apellidos_${i}">Apellidos:</label>
                    <input type="text" id="apellidos_${i}" class="apellidos" name="estudiantes[${i}][apellidos]" readonly>
                </div>
                <div class="input-group">
                    <label for="definitiva_${i}">Nota Definitiva:</label>
                    <input type="number" 
                           id="definitiva_${i}"
                           min="0" 
                           max="20" 
                           step="0.01"
                           class="definitiva"
                           name="estudiantes[${i}][definitiva]"
                           required
                           oninput="validarNota(this)">
                </div>
            </div>
        `;

        // Configurar formato de cédula
        const tipoDoc = studentDiv.querySelector('.tipo-doc');
        const cedulaInput = studentDiv.querySelector('.cedula-input');
        setupCedulaInput(tipoDoc, cedulaInput);

        // Configurar botón buscar
        const btnBuscar = studentDiv.querySelector('.btn-buscar');
        btnBuscar.addEventListener('click', () => buscarEstudiante(studentDiv));

        container.appendChild(studentDiv);
    }
}

// Función auxiliar para validar nota
function validarNota(input) {
    let value = parseFloat(input.value);
    if (value < 0) input.value = 0;
    if (value > 20) input.value = 20;
    if (isNaN(value)) input.value = '';
}

// Función para buscar estudiante (se mantiene igual)
function buscarEstudiante(studentDiv) {
    const carrera = studentDiv.querySelector('.carrera-select').value;
    const cedulaInput = studentDiv.querySelector('.cedula-input').value;
    
    fetch('buscar_estudiante.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `carrera=${encodeURIComponent(carrera)}&cedula=${encodeURIComponent(cedulaInput)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            // Llenar campos nombres y apellidos
            studentDiv.querySelector('.nombres').value = data.nombre;
            studentDiv.querySelector('.apellidos').value = data.apellidos;
        }
    });


}
        </script>

        <input type="hidden" id="tipoMateria" name="tipo_materia">
        
        <div class="buttons">
            <button type="reset">Limpiar</button>
            <button type="submit" name="submit">Registrar</button>
          </div>
        </div>
    </form>

    


</body>
</html>
