<?php
// Incluir verificación de sesión
require_once '../includes/session_check.php';

// Incluir archivo de conexión
require_once('./listas/conexion.php');

// Verificar si hay error de conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Definir las secciones disponibles
$secciones = [
    'carreras' => [
        'ads' => 'ADS',
        'economia' => 'Economía',
        'enfermeria' => 'Enfermería',
        'ingenieria_civil' => 'Ingeniería Civil',
        'desastres' => 'Gestión de Desastres'
    ],
    'servicio' => [
        'proyectos' => 'Servicio Comunitario'
    ],
    'pasantias' => [
        'pasantias' => 'Pasantías'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes - Vista de Solo Lectura</title>
    <script>
      // Prevenir que el usuario pueda volver atrás después de cerrar sesión
      history.pushState(null, null, location.href);
      window.onpopstate = function() {
        history.go(1);
      };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        /* Estilos para tablas responsivas */
        .table-responsive {
            width: 100%;
            margin: 25px 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            background: white;
            position: relative;
            display: block;
        }
        
        .table-responsive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            border-radius: 10px 10px 0 0;
        }
        
        .responsive-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95em;
            min-width: 100%;
            position: relative;
            table-layout: auto;
        }
        
        .responsive-table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .responsive-table thead tr {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        .responsive-table th {
            padding: 15px 18px;
            border: none;
            position: relative;
        }
        
        .responsive-table th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .responsive-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            color: #444;
        }
        
        .responsive-table tbody tr {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .responsive-table tbody tr:nth-of-type(even) {
            background-color: #f9fafb;
        }
        
        .responsive-table tbody tr:hover {
            background-color: #f1f7fe;
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .responsive-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-nowrap {
            white-space: nowrap;
        }
        
        /* Mejorar el scroll horizontal en móviles */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #3498db #f1f1f1;
        }
        
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: #3498db;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #2980b9;
        }
        
        .no-data {
            padding: 30px 20px;
            text-align: center;
            color: #6c757d;
            font-size: 1.1em;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
            border-top: 1px solid #eee;
        }
        
        /* Estilos para dispositivos móviles */
        @media screen and (max-width: 992px) {
            .responsive-table {
                min-width: 100%;
                font-size: 0.9em;
            }
            
            .table-responsive {
                margin: 15px 0;
                border-radius: 8px;
                overflow: hidden;
            }
            
            .responsive-table thead {
                display: none;
            }
            
            .responsive-table tbody {
                display: block;
                width: 100%;
            }
            
            .responsive-table tbody tr {
                display: block;
                margin: 0 0 15px 0;
                padding: 15px 12px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                transition: transform 0.2s, box-shadow 0.2s;
                position: relative;
                overflow: hidden;
            }
            
            .responsive-table tbody tr:active {
                transform: scale(0.98);
            }
            
            .responsive-table tbody tr::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: linear-gradient(to bottom, #3498db, #2ecc71);
            }
            
            .responsive-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 8px;
                text-align: right;
                border-bottom: 1px solid #f0f0f0;
                position: relative;
                padding-left: 120px;
                min-height: 44px;
            }
            
            .responsive-table tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 100px;
                font-weight: 600;
                color: #2c3e50;
                text-align: left;
                font-size: 0.85em;
                text-transform: capitalize;
            }
            
            .responsive-table tbody td:last-child {
                border-bottom: none;
            }
            
            .no-data {
                margin: 15px 0;
                border-radius: 8px;
                padding: 20px 15px;
                font-size: 1em;
                background: white;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            
            /* Efecto de carga para tablas */
            @keyframes shimmer {
                0% { background-position: -1000px 0; }
                100% { background-position: 1000px 0; }
            }
            
            .loading-row {
                animation: shimmer 2s infinite linear;
                background: linear-gradient(to right, #f6f7f8 0%, #e9ecef 50%, #f6f7f8 100%);
                background-size: 1000px 100%;
            }
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px 20px;
        }
        
        /* Efecto de carga */
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        .loading {
            animation: pulse 1.5s infinite ease-in-out;
            background: #f0f0f0;
            color: transparent;
            border-radius: 4px;
            user-select: none;
            pointer-events: none;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 15px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        .view-only-notice {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            border-left: 4px solid #ffeeba;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            flex-wrap: wrap;
        }
        .tablinks {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #495057;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-right: 5px;
            border-radius: 4px 4px 0 0;
        }
        .tablinks:hover {
            background: rgba(52, 152, 219, 0.1);
        }
        .tablinks.active {
            color: #3498db;
            border-bottom: 3px solid #3498db;
            font-weight: 600;
        }
        .tabcontent {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            animation: fadeIn 0.3s ease;
        }
        .tabcontent.active {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }
        
        /* Ajustar ancho de columnas */
        th:nth-child(1), td:nth-child(1) { width: 18%; } /* Nombres */
        th:nth-child(2), td:nth-child(2) { width: 18%; } /* Apellidos */
        th:nth-child(3), td:nth-child(3) { width: 12%; } /* Teléfono */
        th:nth-child(4), td:nth-child(4) { width: 22%; } /* Correo */
        th:nth-child(5), td:nth-child(5) { width: 12%; } /* Botón Ver Notas */
        th:nth-child(6), td:nth-child(6) { width: 18%; } /* Estado */
        
        /* Asegurar que el contenido de la tabla no se desborde */
        td {
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Estilos para las tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            text-transform: uppercase;
            font-size: 0.8em;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Estilo para el botón de ver notas */
        .btn-ver-notas {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-ver-notas:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        }
        .btn-ver-notas i {
            margin-right: 5px;
            font-size: 12px;
        }
        
        /* Estilos para las pestañas principales */
        .main-tabs {
            display: flex;
            background-color: #2c3e50;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
            overflow: hidden;
        }
        
        .main-tablinks {
            background-color: inherit;
            color: white;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .main-tablinks:hover {
            background-color: #34495e;
        }
        
        .main-tablinks.active {
            background-color: #3498db;
            color: white;
        }
        
        .main-tabcontent {
            display: none;
            padding: 20px 0;
            animation: fadeEffect 0.5s;
        }
        
        .main-tabcontent.active {
            display: block;
        }

        /* Estilos para las pestañas secundarias */
        .tabs {
            display: flex;
            flex-wrap: wrap;
            background-color: #f1f1f1;
            border-radius: 0 0 8px 8px;
            margin-bottom: 20px;
            overflow: hidden;
            padding: 5px 10px;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
            padding: 20px 0;
            box-sizing: border-box;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1060;
        }
        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }
        .close:hover {
            color: #000;
        }
        .modal-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .modal-body {
            max-height: 65vh;
            overflow-y: auto;
            padding: 10px 5px;
        }
        .nota-item {
            padding: 12px 10px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        .nota-item:hover {
            background-color: #f9f9f9;
        }
        .nota-item h4 {
            margin: 0 0 8px 0;
            color: #2c3e50;
        }
        .nota-item p {
            margin: 4px 0;
            color: #555;
        }
        .nota-item:last-child {
            border-bottom: none;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .section-title {
            color: #2c3e50;
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f3f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-users"></i> Lista de Estudiantes</h1>
            <a href="../login/cerrarsesion.php" class="btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
        
        <div class="view-only-notice">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Modo de solo lectura</strong>
                <p>No tiene permisos para editar o eliminar registros. Si necesita realizar cambios, contacte a un administrador.</p>
            </div>
        </div>

        <!-- Pestañas principales -->
        <div class="main-tabs">
            <button class="main-tablinks active" onclick="openMainTab(event, 'carreras')">
                <i class="fas fa-graduation-cap"></i> Carreras
            </button>
            <button class="main-tablinks" onclick="openMainTab(event, 'servicio')">
                <i class="fas fa-hands-helping"></i> Servicio Comunitario
            </button>
            <button class="main-tablinks" onclick="openMainTab(event, 'pasantias')">
                <i class="fas fa-briefcase"></i> Pasantías
            </button>
        </div>

        <?php foreach ($secciones as $seccionId => $subsecciones): ?>
            <div id="<?php echo $seccionId; ?>" class="main-tabcontent">
                <div class="tabs">
                    <?php foreach ($subsecciones as $id => $nombre): ?>
                        <button class="tablinks" onclick="openTab(event, '<?php echo $id; ?>')">
                            <i class="fas fa-<?php 
                                echo $id === 'ads' ? 'laptop-code' : 
                                    ($id === 'enfermeria' ? 'user-nurse' : 
                                    ($id === 'civil' ? 'hard-hat' : 
                                    ($id === 'desastres' ? 'shield-alt' : 
                                    ($id === 'proyectos' ? 'hands-helping' : 
                                    ($id === 'pasantias' ? 'briefcase' : 'chart-line'))))); ?>">
                            </i>
                            <?php echo $nombre; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($subsecciones as $id => $nombre): ?>
                    <div id="<?php echo $id; ?>" class="tabcontent">
                        <h2 class="section-title"><?php echo $nombre; ?></h2>
                        <div style="overflow-x:auto;">
                            <?php
                            // Determinar qué tipo de contenido mostrar según la sección
                            if ($seccionId === 'carreras') {
                                // Mostrar estudiantes de carreras
                                $query = "SELECT e.nombre, e.apellidos, e.telefono, e.correo, e.estado, e.cedula
                                        FROM $id e
                                        WHERE e.estado = 'activo' 
                                        ORDER BY e.apellidos, e.nombre";
                                
                                echo '<table><thead><tr>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Notas</th>
                                    <th>Estado</th>
                                </tr></thead><tbody>';
                                
                                $result = mysqli_query($conn, $query);
                                
                                if (!$result) {
                                    error_log("Error en la consulta: " . mysqli_error($conn));
                                    echo "<tr><td colspan='6' class='error'>Error al cargar los datos. Por favor, intente nuevamente.</td></tr>";
                                } elseif (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                        echo "<td><button class='btn-ver-notas' data-cedula='" . htmlspecialchars($row['cedula']) . "' data-nombre='" . htmlspecialchars($row['nombre'] . ' ' . $row['apellidos']) . "'><i class='fas fa-book-open'></i> Ver Notas</button></td>";
                                        echo "<td><span style='color: #27ae60;'><i class='fas fa-check-circle'></i> " . ucfirst(htmlspecialchars($row['estado'])) . "</span></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='no-data'>No hay estudiantes registrados en esta sección</td></tr>";
                                }
                                echo '</tbody></table>';
                                
                            } elseif ($seccionId === 'servicio' && $id === 'proyectos') {
                                // Mostrar proyectos de servicio comunitario
                                $query = "SELECT  
                                    ep.carrera, 
                                    ep.cedula, 
                                    ep.nombres, 
                                    ep.apellidos,
                                    p.titulo AS nombre_proyecto,
                                    p.periodo,
                                    p.ubicacion,
                                    p.tutor,
                                    p.fecha_registro
                                FROM estudiantes_proyectos ep
                                JOIN proyectos p ON ep.id_proyecto = p.id_proyecto
                                WHERE 1=1
                                ORDER BY p.fecha_registro DESC";
                                
                                // Debug: Mostrar consulta
                                // echo "<!-- Consulta: " . $query . " -->";
                                
                                $result = mysqli_query($conn, $query);
                                
                                if (!$result) {
                                    error_log("Error en la consulta: " . mysqli_error($conn));
                                    echo "<div class='error'>Error al cargar los proyectos. Por favor, intente nuevamente.</div>";
                                } else {
                                    echo '<div class="table-responsive">';
                                    if (mysqli_num_rows($result) > 0) {
                                        echo '<table id="tablaProyectos" class="responsive-table">';
                                        echo '<thead><tr>
                                            <th>Carrera</th>
                                            <th>Cédula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Proyecto</th>
                                            <th>Período</th>
                                            <th>Ubicación</th>
                                            <th>Tutor</th>
                                            <th>Fecha Registro</th>
                                        </tr></thead><tbody>';
                                        
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td data-label='Carrera'>" . htmlspecialchars($row['carrera'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Cédula'>" . htmlspecialchars($row['cedula'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Nombres'>" . htmlspecialchars($row['nombres'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Apellidos'>" . htmlspecialchars($row['apellidos'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Proyecto'>" . htmlspecialchars($row['nombre_proyecto'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Período'>" . htmlspecialchars($row['periodo'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Ubicación'>" . htmlspecialchars($row['ubicacion'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Tutor'>" . htmlspecialchars($row['tutor'] ?? 'N/A') . "</td>";
                                            echo "</tr>";
                                        }
                                        echo '</tbody></table>';
                                    } else {
                                        echo "<div class='no-data'>No hay proyectos registrados</div>";
                                    }
                                    echo '</div>';
                                }
                                
                            } elseif ($seccionId === 'pasantias' && $id === 'pasantias') {
                                // Mostrar pasantías
                                // Primero verificamos si las tablas existen
                                $check_tables = mysqli_query($conn, "SHOW TABLES LIKE 'estudiantes_pasantias'");
                                $table1_exists = (mysqli_num_rows($check_tables) > 0);
                                
                                $check_tables = mysqli_query($conn, "SHOW TABLES LIKE 'pasantias'");
                                $table2_exists = (mysqli_num_rows($check_tables) > 0);
                                
                                if (!$table1_exists || !$table2_exists) {
                                    echo "<div class='error'>Error: Tablas no encontradas. " . 
                                         "estudiantes_pasantias: " . ($table1_exists ? 'Existe' : 'No existe') . 
                                         ", pasantias: " . ($table2_exists ? 'Existe' : 'No existe') . "</div>";
                                    return;
                                }
                                
                                // Verificar estructura de las tablas
                                $query = "DESCRIBE estudiantes_pasantias";
                                $result = mysqli_query($conn, $query);
                                $estructura_estudiantes = [];
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estructura_estudiantes[] = $row['Field'];
                                    }
                                }
                                
                                $query = "DESCRIBE pasantias";
                                $result = mysqli_query($conn, $query);
                                $estructura_pasantias = [];
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estructura_pasantias[] = $row['Field'];
                                    }
                                }
                                
                                echo '<div class="debug-info" style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 4px; font-family: monospace;">';
                                echo "<strong>Estructura de tablas:</strong><br>";
                                echo "estudiantes_pasantias: " . implode(", ", $estructura_estudiantes) . "<br>";
                                echo "pasantias: " . implode(", ", $estructura_pasantias) . "<br>";
                                echo "</div>";
                                
                                // Consulta principal
                                $query = "SELECT  
                                    ep.carrera, 
                                    ep.cedula, 
                                    ep.nombres, 
                                    ep.apellidos,
                                    pa.titulo,
                                    pa.fecha_inicio,
                                    pa.fecha_culminacion,
                                    pa.institucion,
                                    pa.tutor_academico,
                                    pa.tutor_institucional
                                FROM estudiantes_pasantias ep
                                JOIN pasantias pa ON ep.id_pasantia = pa.id_pasantia
                                WHERE 1=1
                                ORDER BY pa.fecha_inicio DESC";
                                
                                // Debug: Mostrar consulta
                                echo "<!-- Consulta: " . $query . " -->";
                                
                                $result = mysqli_query($conn, $query);
                                
                                if (!$result) {
                                    error_log("Error en la consulta de pasantías: " . mysqli_error($conn));
                                    echo "<div class='error'>Error en la consulta de pasantías: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
                                } else {
                                    $num_rows = mysqli_num_rows($result);
                                    echo '<div class="debug-info" style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 4px; font-family: monospace;">';
                                    echo "Consulta ejecutada. Filas encontradas: " . $num_rows . "<br>";
                                    echo "Último error: " . htmlspecialchars(mysqli_error($conn)) . "<br>";
                                    echo "</div>";
                                    
                                    echo '<div class="table-responsive">';
                                    if ($num_rows > 0) {
                                        echo '<table id="tablaPasantes" class="responsive-table">';
                                        echo '<thead><tr>
                                            <th>Carrera</th>
                                            <th>Cédula</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Pasantía</th>
                                            <th>Inicio</th>
                                            <th>Culminación</th>
                                            <th>Institución</th>
                                            <th>Tutor Académico</th>
                                        </tr></thead><tbody>';
                                        
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td data-label='Carrera'>" . htmlspecialchars($row['carrera'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Cédula'>" . htmlspecialchars($row['cedula'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Nombres'>" . htmlspecialchars($row['nombres'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Apellidos'>" . htmlspecialchars($row['apellidos'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Pasantía' class='text-nowrap'>" . htmlspecialchars($row['titulo'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Inicio' class='text-nowrap'>" . (!empty($row['fecha_inicio']) ? date('d/m/Y', strtotime($row['fecha_inicio'])) : 'N/A') . "</td>";
                                            echo "<td data-label='Culminación' class='text-nowrap'>" . (!empty($row['fecha_culminacion']) ? date('d/m/Y', strtotime($row['fecha_culminacion'])) : 'En curso') . "</td>";
                                            echo "<td data-label='Institución'>" . htmlspecialchars($row['institucion'] ?? 'N/A') . "</td>";
                                            echo "<td data-label='Tutor Académico'>" . htmlspecialchars($row['tutor_academico'] ?? 'N/A') . "</td>";
                                            echo "</tr>";
                                        }
                                        echo '</tbody></table>';
                                    } else {
                                        echo "<div class='no-data'>No hay pasantías registradas</div>";
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Modal para mostrar las notas -->
    <div id="notasModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-header">
                <h2>Notas del Estudiante</h2>
                <p id="estudianteInfo"></p>
            </div>
            <div class="modal-body" id="notasContainer">
                <!-- Aquí se cargarán las notas -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Variables globales
    var modal = document.getElementById('notasModal');
    var span = document.getElementsByClassName('close')[0];
    var estudianteInfo = document.getElementById('estudianteInfo');
    var notasContainer = document.getElementById('notasContainer');
    var estudianteActual = null;
    
    // Función para cambiar entre pestañas principales
    function openMainTab(evt, tabName) {
        // Obtener todos los elementos con clase 'main-tabcontent' y ocultarlos
        var tabcontent = document.getElementsByClassName("main-tabcontent");
        for (var i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }

        // Obtener todos los botones con clase 'main-tablinks' y quitar la clase 'active'
        var tablinks = document.getElementsByClassName("main-tablinks");
        for (var i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Mostrar la pestaña actual y agregar la clase 'active' al botón que abrió la pestaña
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
        
        // Activar la primera pestaña de la sección
        var firstTab = document.querySelector('#' + tabName + ' .tablinks');
        if (firstTab) firstTab.click();
    }

    // Mostrar la primera pestaña por defecto
    document.addEventListener('DOMContentLoaded', function() {
        // Activar la primera pestaña principal
        var firstMainTab = document.querySelector('.main-tablinks');
        if (firstMainTab) firstMainTab.click();
        
        // Activar la primera pestaña secundaria
        var firstSubTab = document.querySelector('.main-tabcontent.active .tablinks');
        if (firstSubTab) firstSubTab.click();
        
        // Configurar evento para el botón de cerrar
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        // Configurar eventos para los botones de ver notas
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-ver-notas')) {
                var cedula = e.target.getAttribute('data-cedula');
                var nombre = e.target.getAttribute('data-nombre');
                cargarNotas(cedula, nombre);
            }
        });
    });
    
    // Función para cargar las notas del estudiante
    function cargarNotas(cedula, nombre) {
        // Mostrar información del estudiante
        estudianteInfo.textContent = nombre;
        notasContainer.innerHTML = '<p>Cargando notas...</p>';
        
        // Mostrar el modal
        modal.style.display = 'block';
        
        // Mostrar mensaje de carga
        const loadingMessage = document.createElement('div');
        loadingMessage.className = 'loading-message';
        loadingMessage.innerHTML = '<p>Cargando notas, por favor espere...</p>';
        notasContainer.appendChild(loadingMessage);
        
        // Hacer la petición AJAX para obtener las notas con manejo de tiempo de espera
        const xhr = new XMLHttpRequest();
        const url = 'obtener_notas.php';
        const params = 'cedula=' + encodeURIComponent(cedula) + '&_=' + new Date().getTime();
        
        // Configurar timeout
        const timeout = 30000; // 30 segundos
        
        // Configurar el objeto XHR
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.timeout = timeout;
        
        // Manejar la respuesta
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                // Eliminar mensaje de carga
                const loadingMessages = document.querySelectorAll('.loading-message');
                loadingMessages.forEach(msg => msg.remove());
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        // Verificar si la sesión ha expirado
                        if (response.session_expired) {
                            window.location.href = '../index.php?session_expired=1';
                            return;
                        }
                        
                        if (response.success) {
                            if (response.notas && response.notas.length > 0) {
                                let html = '';
                                response.notas.forEach(function(nota) {
                                    // Escapar el contenido para prevenir XSS
                                    const materia = nota.nombre_materia || 'Sin nombre';
                                    const definitiva = nota.definitiva || 'N/A';
                                    const periodo = nota.periodo || 'N/A';
                                    
                                    html += `
                                        <div class="nota-item">
                                            <h4>${materia}</h4>
                                            <p><strong>Nota:</strong> ${definitiva}</p>
                                            <p><strong>Período:</strong> ${periodo}</p>
                                        </div>
                                    `;
                                });
                                notasContainer.innerHTML = html;
                            } else {
                                notasContainer.innerHTML = '<p>No hay notas registradas para este estudiante.</p>';
                            }
                        } else {
                            const errorMsg = response.message || 'Error desconocido al cargar las notas';
                            notasContainer.innerHTML = `<p class="error-message">Error: ${errorMsg}</p>`;
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta:', e);
                        notasContainer.innerHTML = '<p class="error-message">Error al procesar la respuesta del servidor.</p>';
                    }
                } else if (xhr.status === 401) {
                    // No autorizado - sesión expirada
                    window.location.href = '../index.php?session_expired=1';
                } else {
                    console.error('Error en la petición:', xhr.status, xhr.statusText);
                    notasContainer.innerHTML = '<p class="error-message">Error en la conexión con el servidor. Código: ' + xhr.status + '</p>';
                }
            }
        };
        
        // Manejar errores de red
        xhr.onerror = function() {
            notasContainer.innerHTML = '<p class="error-message">Error de red. Verifique su conexión e intente nuevamente.</p>';
        };
        
        // Manejar tiempo de espera agotado
        xhr.ontimeout = function() {
            notasContainer.innerHTML = '<p class="error-message">Tiempo de espera agotado. Por favor, intente nuevamente.</p>';
        };
        
        // Enviar la petición
        try {
            xhr.send(params);
        } catch (e) {
            console.error('Error al enviar la petición:', e);
            notasContainer.innerHTML = '<p class="error-message">Error al enviar la solicitud.</p>';
        }
    }

    function openTab(evt, tabName) {
        // Evitar comportamiento por defecto si es un evento
        if (evt) evt.preventDefault();
        
        // Obtener el contenedor de pestañas padre
        var parentTab = evt.currentTarget.closest('.main-tabcontent');
        
        if (parentTab) {
            // Ocultar todos los tabcontent dentro del mismo contenedor padre
            var tabcontents = parentTab.getElementsByClassName("tabcontent");
            for (var i = 0; i < tabcontents.length; i++) {
                tabcontents[i].classList.remove("active");
            }
            
            // Desactivar todos los tablinks dentro del mismo contenedor padre
            var tablinks = parentTab.getElementsByClassName("tablinks");
            for (var i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            
            // Activar la pestaña actual
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        } else {
            // Comportamiento alternativo si no se encuentra el contenedor padre
            console.error("No se pudo encontrar el contenedor de pestañas principal");
        }
    }
    </script>
</body>
</html>
