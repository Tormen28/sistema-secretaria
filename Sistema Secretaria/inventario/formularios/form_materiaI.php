<?php
if (isset($_POST['submit'])) {
    // Configuración de conexión
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "secretaria";
    
    // Obtener datos del estudiante
    $cedula = $_POST['cedula'];
    $carrera = $_POST['carrera'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $materias = $_POST['materias'] ?? [];

    // Crear conexión
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    if (!empty($materias)) {
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
        
        $stmt->bind_param("sssssssds", 
            $periodo,
            $nombre_materia,
            $codigo,
            $cedula,
            $nombres,
            $apellidos,
            $carrera,
            $definitiva,
            $tipo_materia
        );

        foreach ($materias as $materia) {
            $periodo = $materia['semestre'] . '-' . $materia['anio'];
            $nombre_materia = $materia['materia'];
            $codigo = $materia['codigo'];
            $definitiva = (float)($materia['definitiva'] ?? 0);
            $tipo_materia = $materia['tipo_materia'] ?? 'sin_tipo';
            error_log("Datos materia: " . print_r($materia, true)); // Verificar todos los campos
    
            // Validar nota
            if ($definitiva < 0 || $definitiva > 20) {
                echo "<script>alert('Error en materia $nombre_materia: La nota debe estar entre 0 y 20');</script>";
                continue; // Saltar esta materia
            }
    
            if (!$stmt->execute()) {
                echo "<script>alert('Error al guardar: " . $stmt->error . "');</script>";
            }
        }

        $stmt->close();
        echo "<script>alert('Registros guardados exitosamente');</script>";
    } else {
        echo "<script>alert('No hay materias para registrar');</script>";
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


  <div class="form-container">
    <h2>REGISTRO DE NOTAS POR ESTUDIANTE</h2>
    
    <form action="" method="POST">
        <!-- Sección del Estudiante -->
        <div class="student-card">
            <h3>Datos del Estudiante</h3>
            
            <div class="input-group">
                <label>Carrera:</label>
                <select id="carrera" name="carrera" required>
                    <option value="">Seleccione carrera...</option>
                    <option value="ads">Análisis y Diseño de Sistemas</option>
                    <option value="desastres">Desastres</option>
                    <option value="economia">Economía</option>
                    <option value="enfermeria">Enfermería</option>
                    <option value="ingenieria_civil">Ingeniería Civil</option>
                </select>
            </div>
            
            <div class="input-group">
                <label>Cédula:</label>
                <div class="cedula-container">
                    <select class="tipo-doc" name="tipo_doc" style="width: 70px;">
                        <option value="V">V</option>
                        <option value="E">E</option>
                    </select>
                    <input type="text" 
                           id="cedula"
                           name="cedula"
                           placeholder="Ej: V-12.345.678"
                           required
                           style="flex-grow: 1;">
                    <button type="button" onclick="buscarEstudiante()">Buscar Estudiante</button>
                </div>
            </div>
            
            <div class="input-group">
                <div class="nombre-apellido-container">
                    <div>
                        <label>Nombres:</label>
                        <input type="text" id="nombres" name="nombres" readonly style="width: 300px;">
                    </div>
                    <div>
                        <label>Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" readonly style="width: 300px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Materias -->
        <div class="materias-section">
            <h3>Registro de Materias</h3>
            
            <div class="input-group">
                <label>Cantidad de materias a registrar:</label>
                <div class="number-input-container">
                    <input type="number" id="numMaterias" min="1" value="1" style="width: 100px;">
                    <button type="button" onclick="generarMaterias()" class="generate-button">
                        Generar Campos
                    </button>
                </div>
            </div>
            
            <div id="materiasContainer"></div>
        </div>

        <!-- Botones de acción -->
        <div class="input-group">
          <div class="buttons">
            <button type="reset">Limpiar Formulario</button>
            <button type="submit" name="submit">Guardar Registros</button>
          </div>
        </div>
    </form>
    </div>

    <script>
    // Función mejorada para formato de cédula
    function setupCedulaInput(tipoDocSelect, cedulaInput) {
        const formatCedula = (value, tipo) => {
            let cleaned = value.replace(/[^0-9]/g, '');
            let formatted = tipo + '-';
            
            if (cleaned.length > 0) {
                // Asegurar máximo 8 dígitos
                cleaned = cleaned.substring(0, 8);
                
                // Dividir en grupos: 2-3-3
                const parts = [];
                if (cleaned.length > 5) {
                    parts.push(cleaned.substring(0, 2));
                    parts.push(cleaned.substring(2, 5));
                    parts.push(cleaned.substring(5, 8));
                } else if (cleaned.length > 2) {
                    parts.push(cleaned.substring(0, 2));
                    parts.push(cleaned.substring(2, 5));
                } else {
                    parts.push(cleaned);
                }
                
                formatted += parts.join('.');
            }
            return formatted;
        };

        const updateCedula = () => {
            const tipo = tipoDocSelect.value;
            let currentValue = cedulaInput.value.replace(/^[VE]-/, '');
            cedulaInput.value = formatCedula(currentValue, tipo);
        };

        tipoDocSelect.addEventListener('change', updateCedula);
        cedulaInput.addEventListener('input', updateCedula);
    }

    // Función para buscar estudiante mejorada
    async function buscarEstudiante() {
        const carrera = document.getElementById('carrera').value;
        let cedula = document.getElementById('cedula').value;

        // Normalizar cédula para búsqueda
        cedula = cedula.toUpperCase().replace(/[^VE0-9]/g, '');
        const tipo = cedula.charAt(0);
        const numero = cedula.slice(1).replace(/\D/g, '');
        cedula = `${tipo}-${numero}`;

        try {
            const response = await fetch('buscar_estudiante.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `carrera=${encodeURIComponent(carrera)}&cedula=${encodeURIComponent(cedula)}`
            });
            
            const data = await response.json();
            
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('nombres').value = data.nombre || '';
                document.getElementById('apellidos').value = data.apellidos || '';
                if (!data.nombre) alert('Estudiante encontrado pero sin datos de nombre');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al buscar el estudiante');
        }
    }

    // Función para generar campos de materias mejorada
    function generarMaterias() {
        const numMaterias = document.getElementById('numMaterias').value;
        const container = document.getElementById('materiasContainer');
        container.innerHTML = '';

        for (let i = 0; i < numMaterias; i++) {
            const materiaDiv = document.createElement('div');
            materiaDiv.className = 'materia-section';
            materiaDiv.innerHTML = `
                <h4>Materia ${i + 1}</h4>
                
                <div class="input-group-row">
                    <div class="input-group">
                        <label>Periodo:</label>
                        <select name="materias[${i}][semestre]" class="periodo-select">
                            <option value="1">Primer Periodo</option>
                            <option value="2">Segundo Periodo</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label>Año:</label>
                        <select name="materias[${i}][anio]" class="anio-select">
                            ${generateYearOptions()}
                        </select>
                    </div>
                </div>

                <div class="input-group-row">
                    <div class="input-group">
                        <label>Pensum:</label>
                        <select class="pensum-select" 
                                name="materias[${i}][pensum]"
                                onchange="cargarMaterias(this, ${i})" 
                                required>
                            <option value="">-- Seleccione pensum --</option>
                            ${generatePensumOptions()}
                        </select>
                    </div>
                    
                            <div class="input-group-row">
                    <div class="input-group">
                        <label>Materia:</label>
                        <select class="materia-select" 
                                name="materias[${i}][materia]"
                                onchange="actualizarCodigo(this, ${i})" 
                                required>
                            <option value="">-- Seleccione materia --</option>
                        </select>
                        <!-- Campo oculto MOVIDO dentro del mismo grupo -->
                        <input type="hidden" class="tipo-materia" name="materias[${i}][tipo_materia]">
                    </div>
                </div>

                <div class="input-group-row">
                    <div class="input-group">
                        <label>Código:</label>
                        <input type="text" class="codigo" name="materias[${i}][codigo]" readonly>
                    </div>
                    
                    <div class="input-group">
                        <label>Nota Definitiva:</label>
                       <input type="number" 
                        min="0" 
                        max="20" 
                        step="0.01"
                        name="materias[${i}][definitiva]"
                        required
                        class="nota-input"
                        onkeyup="if(this.value > 20) this.value = 20;">
                    </div>
                </div>
            `;

            container.appendChild(materiaDiv);
        }
    }

    document.querySelectorAll('.nota-input').forEach(input => {
    input.addEventListener('input', function(e) {
        let value = parseFloat(this.value);
        if (value > 20) {
            this.value = 20;
            alert('La nota máxima permitida es 20');
        }
    });
});

    // Funciones auxiliares
    function generateYearOptions() {
        let options = '';
        const currentYear = new Date().getFullYear();
        for (let year = currentYear + 1; year >= 2020; year--) {
            options += `<option value="${year}">${year}</option>`;
        }
        return options;
    }

    function generatePensumOptions() {
        const pensums = [
            'pensum_ads',
            'pensum_civil',
            'pensum_desastres',
            'pensum_economia',
            'pensum_enfermeria'
        ];
        return pensums.map(p => `
            <option value="${p}">${p.replace('pensum_', '').toUpperCase()}</option>
        `).join('');
    }

    async function cargarMaterias(select, index) {
    const pensum = select.value;
    const materiaSelect = select.closest('.input-group-row').querySelector('.materia-select');
    
    try {
        const response = await fetch(`get_materias.php?pensum=${pensum}`);
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        
        const data = await response.json();
        
        materiaSelect.innerHTML = '<option value="">-- Seleccione materia --</option>';
        data.forEach(materia => {
            const option = document.createElement('option');
            option.value = materia.materia;
            option.textContent = `${materia.materia} (${materia.codigo})`;
            // Asegurar que los datos se toman correctamente
            option.dataset.codigo = materia.codigo;
            option.dataset.tipo = materia.tipo_materia;
            materiaSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando materias:', error);
        alert('Error al cargar las materias: ' + error.message);
    }
}

function actualizarCodigo(select, index) {
    // Asegurar que busca el input dentro del mismo grupo
    const row = select.closest('.input-group-row');
    const codigoInput = row.nextElementSibling.querySelector('.codigo');
    const tipoInput = row.querySelector('.tipo-materia'); // Selector corregido
    
    const selectedOption = select.options[select.selectedIndex];
    codigoInput.value = selectedOption.dataset.codigo || '';
    tipoInput.value = selectedOption.dataset.tipo || ''; // Asignación directa
}


    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        setupCedulaInput(
            document.querySelector('.tipo-doc'),
            document.getElementById('cedula')
        );
    });
    
    </script>
    </script>
</body>
</html>