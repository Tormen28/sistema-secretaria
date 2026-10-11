<?php
include('conexion.php'); // Asegúrate de que este archivo defina $conn correctamente

// Consulta para listar estudiantes en proyectos de servicio comunitario
$query_proyectos = "
    SELECT  
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
    ORDER BY p.fecha_registro DESC
";

// Función para mostrar la tabla de estudiantes en proyectos
function mostrarTablaProyectos($result, $titulo) {
    echo "<div class='table-responsive'>";
    if ($result && $result->num_rows > 0) {
        echo "<table id='tablaProyectos' class='responsive-table'>";
        echo "<thead><tr>";
        // Obtener los campos una sola vez
        $fields = $result->fetch_fields();
        // Cabeceras
        foreach ($fields as $field) {
            $header = ucwords(str_replace('_', ' ', $field->name));
            echo "<th>$header</th>";
        }
        echo "</tr></thead><tbody>";
        
        // Volver al inicio del resultado para leer los datos
        $result->data_seek(0);
        
        // Filas
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td data-label='".ucwords(str_replace('_', ' ', $key))."'>";
                // Formatear fechas si es necesario
                if (strpos($key, 'fecha') !== false && !empty($value)) {
                    $date = new DateTime($value);
                    echo $date->format('d/m/Y');
                } else {
                    echo htmlspecialchars($value);
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='no-results'>No hay estudiantes registrados en proyectos</p>";
    }
    echo "</div>";
}

// Ejecutar consulta y manejar errores
try {
    $result_proyectos = $conn->query($query_proyectos);
} catch (Exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}

// Cerrar conexión DESPUÉS de usarla
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos de Servicio Comunitario</title>
    <link rel="stylesheet" type="text/css" href="../usuarios_list_stylesss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jsPDF y AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script>window.jsPDF = window.jspdf.jsPDF;</script>
    <!-- Otras bibliotecas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
    // Función para exportar la tabla a PDF
    function exportTableToPDF(tableId, title = 'Proyectos de Servicio Comunitario') {
        try {
            const table = document.getElementById(tableId);
            if (!table) {
                console.error('No se encontró la tabla para exportar');
                alert('No se pudo encontrar la tabla para exportar');
                return;
            }

            // Crear un nuevo documento PDF
            const doc = new jsPDF({
                orientation: 'p', // vertical
                unit: 'mm'
            });
            
            // Agregar logo
            const img = new Image();
            img.src = '../../css/logo1.jpg';
            
            // Tamaño y posición del logo
            const logoWidth = 25;
            const logoHeight = 25;
            const logoX = 15;
            const logoY = 10;
            
            // Agregar logo
            if (img.complete) {
                doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
            } else {
                img.onload = function() {
                    doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
                };
            }
            
            // Configuración de texto
            doc.setFont('helvetica', 'bold');
            
            // Texto del encabezado
            const pageWidth = doc.internal.pageSize.width;
            const headerTexts = [
                'REPÚBLICA BOLIVARIANA DE VENEZUELA',
                'MINISTERIO DEL PODER POPULAR PARA LA DEFENSA',
                'UNIVERSIDAD NACIONAL EXPERIMENTAL POLITÉCNICA',
                'DE LA FUERZA ARMADA NACIONAL BOLIVARIANA',
                'VICERRECTORADO DE ASUNTOS SOCIALES',
                'DIRECCIÓN DE EXTENSIÓN',
                'COORDINACIÓN DE SERVICIO COMUNITARIO'
            ];
            
            // Posición inicial del texto
            let currentY = logoY + 5;
            
            // Agregar cada línea del encabezado
            headerTexts.forEach((text, index) => {
                doc.setFontSize(10);
                const textWidth = doc.getStringUnitWidth(text) * doc.getFontSize() / doc.internal.scaleFactor;
                doc.text(text, logoX + logoWidth + 5, currentY);
                currentY += 5;
            });
            
            // Agregar título principal
            currentY += 5;
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            const titleText = title.toUpperCase();
            const titleWidth = doc.getStringUnitWidth(titleText) * 14 / doc.internal.scaleFactor;
            doc.text(titleText, (pageWidth - titleWidth) / 2, currentY);
            
            // Agregar fecha
            currentY += 10;
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            const dateText = `Ciudad Bolívar, ${new Date().toLocaleDateString('es-VE', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            })}`;
            doc.text(dateText, 15, currentY);
            
            // Obtener encabezados de la tabla
            const headers = [];
            const headerRow = table.querySelector('thead tr');
            if (headerRow) {
                headerRow.querySelectorAll('th').forEach(th => {
                    const text = th.textContent.trim();
                    // Excluir columnas de acciones y botones
                    if (!th.classList.contains('acciones-th') && 
                        !th.querySelector('button, a, .btn') && 
                        text !== 'ACCIONES') {
                        headers.push({
                            header: text,
                            dataKey: th.getAttribute('data-field') || text.toLowerCase().replace(/\s+/g, '_')
                        });
                    }
                });
            }
            
            // Obtener datos de la tabla
            const tableData = [];
            const dataRows = table.querySelectorAll('tbody tr');
            
            dataRows.forEach(row => {
                const rowData = {};
                const cells = row.querySelectorAll('td');
                
                headers.forEach((header, index) => {
                    if (cells[index]) {
                        const cell = cells[index].cloneNode(true);
                        // Eliminar botones y otros elementos no deseados
                        cell.querySelectorAll('button, a, .btn').forEach(btn => btn.remove());
                        
                        if (header.dataKey.includes('fecha') && cells[index].hasAttribute('data-date')) {
                            rowData[header.dataKey] = cells[index].getAttribute('data-date');
                        } else {
                            rowData[header.dataKey] = cell.textContent.trim();
                        }
                    }
                });
                
                if (Object.keys(rowData).length > 0) {
                    tableData.push(rowData);
                }
            });

            // Configuración de la tabla
            const tableConfig = {
                head: [headers.map(h => h.header)],
                body: tableData.map(row => headers.map(h => row[h.dataKey] || '')),
                startY: currentY + 10,
                headStyles: {
                    fillColor: [0, 56, 101], // Azul UNEFA (#003865)
                    textColor: 255,
                    fontStyle: 'bold',
                    fontSize: 9,
                    halign: 'center',
                    lineWidth: 0.1,
                    lineColor: [0, 56, 101]
                },
                bodyStyles: {
                    fontSize: 9,
                    cellPadding: 3,
                    lineWidth: 0.1,
                    lineColor: [0, 0, 0],
                    textColor: [0, 0, 0]
                },
                alternateRowStyles: {
                    fillColor: [255, 255, 255]
                },
                margin: { 
                    left: 15,
                    right: 15
                },
                styles: {
                    fontSize: 9,
                    cellPadding: 3,
                    overflow: 'linebreak',
                    lineWidth: 0.1,
                    lineColor: [0, 0, 0],
                    valign: 'middle',
                    textColor: [0, 0, 0],
                    cellWidth: 'auto'
                },
                columnStyles: {
                    0: { cellWidth: 'auto' },
                    1: { cellWidth: 'auto' }
                },
                didDrawPage: function(data) {
                    // Pie de página
                    const pageSize = doc.internal.pageSize;
                    const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
                    const pageNumber = doc.internal.getNumberOfPages();
                    
                    // Línea horizontal
                    doc.setDrawColor(0, 0, 0);
                    doc.setLineWidth(0.5);
                    doc.line(15, pageHeight - 20, pageSize.width - 15, pageHeight - 20);
                    
                    // Texto del pie de página
                    doc.setFontSize(8);
                    doc.setFont('helvetica', 'normal');
                    doc.text('UNEFA - Núcleo Bolívar - Extensión Ciudad Bolívar', pageSize.width / 2, pageHeight - 15, { align: 'center' });
                    doc.text(`Página ${pageNumber}`, pageSize.width - 20, pageHeight - 15, { align: 'right' });
                }
            };

            // Agregar la tabla al documento
            doc.autoTable(tableConfig);
            
            // Guardar el PDF
            const fileName = `${title.replace(/[^\w\s]/gi, '').replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(fileName);

        } catch (error) {
            console.error('Error al exportar a PDF:', error);
            alert('Error al exportar a PDF: ' + (error.message || 'Error desconocido'));
        }
    }

    // Función para exportar a CSV
    function exportTableToCSV(tableId, title = 'Proyectos de Servicio Comunitario') {
        try {
            const table = document.getElementById(tableId);
            if (!table) {
                alert('No hay datos para exportar');
                return;
            }
            
            // Crear CSV con encabezado UNEFA
            let csv = [
                'UNIVERSIDAD NACIONAL EXPERIMENTAL',
                'POLITÉCNICA DE LA FUERZA ARMADA NACIONAL',
                'UNEFA - NÚCLEO BOLIVAR - EXTENSIÓN CIUDAD BOLÍVAR',
                '',
                title.toUpperCase(),
                `Generado el: ${new Date().toLocaleDateString('es-VE', {day: '2-digit', month: '2-digit', year: 'numeric'})}`,
                '' // Espacio en blanco antes de los encabezados
            ];
            
            const rows = table.querySelectorAll('tr');
            if (rows.length <= 1) {
                alert('No hay datos para exportar');
                return;
            }
            
            // Obtener encabezados
            const headerRow = [];
            const headerCells = rows[0].querySelectorAll('th');
            
            // Función para limpiar texto para CSV
            const cleanCSVText = (text) => {
                if (text === null || text === undefined) return '';
                text = String(text).trim();
                // Reemplazar saltos de línea por espacio
                text = text.replace(/\r?\n|\r/g, ' ');
                // Eliminar espacios múltiples
                text = text.replace(/\s+/g, ' ');
                // Si el texto contiene comas, punto y coma, tabulaciones o comillas, rodearlo con comillas
                if (text.includes(',') || text.includes(';') || text.includes('\t') || text.includes('"') || text.includes('\n')) {
                    // Escapar comillas dobles duplicándolas
                    text = text.replace(/"/g, '""');
                    return `"${text}"`;
                }
                return text;
            };
            
            // Procesar encabezados (excluyendo columnas de acciones)
            const headersToInclude = [];
            headerCells.forEach((th, index) => {
                const text = th.textContent.trim();
                // Excluir columnas de acciones y botones
                if (!th.classList.contains('acciones-th') && 
                    !th.querySelector('button, a, .btn') && 
                    text !== 'ACCIONES') {
                    headersToInclude.push(index);
                    headerRow.push(cleanCSVText(text));
                }
            });
            
            // Agregar encabezados al CSV
            csv.push(headerRow.join(';'));
            
            // Procesar filas de datos
            for (let i = 1; i < rows.length; i++) {
                const rowData = [];
                const cols = rows[i].querySelectorAll('td');
                
                // Procesar solo las celdas que corresponden a los encabezados incluidos
                headersToInclude.forEach(colIndex => {
                    if (cols[colIndex]) {
                        const cellContent = cols[colIndex].cloneNode(true);
                        // Eliminar botones u otros elementos no deseados
                        cellContent.querySelectorAll('button, a, .btn').forEach(btn => btn.remove());
                        
                        // Verificar si es una celda de fecha
                        if (cellContent.hasAttribute('data-date')) {
                            // Usar el atributo data-date si está disponible
                            const dateValue = cellContent.getAttribute('data-date');
                            rowData.push(cleanCSVText(dateValue));
                        } else {
                            rowData.push(cleanCSVText(cellContent.textContent));
                        }
                    } else {
                        rowData.push('');
                    }
                });
                
                if (rowData.length > 0) {
                    csv.push(rowData.join(';'));
                }
            }
            
            // Crear el contenido CSV
            const csvContent = csv.join('\r\n');
            
            // Crear un enlace de descarga
            const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            
            // Configurar el enlace
            link.setAttribute('href', url);
            const fileName = `${title.replace(/[^\w\s]/gi, '').replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
            link.setAttribute('download', fileName);
            link.style.visibility = 'hidden';
            
            // Agregar al documento y hacer clic
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
        } catch (error) {
            console.error('Error al exportar a CSV:', error);
            alert('Error al exportar a CSV: ' + (error.message || 'Error desconocido'));
        }
    }
    </script>
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
            <a href="#" class="menu-link active">
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
                    <a href="../listas/lista_proyectos_servicio_comunitario.php" class="submenu-link active">
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

<!-- Contenido Principal -->
<div class="main-content" style="padding: 20px;">
    <div class="header-container">
        <h2>Proyectos de Servicio Comunitario</h2>
        <div class="export-buttons-container">
            <button class="export-btn btn-csv" onclick="exportTableToCSV('tablaProyectos', 'Proyectos_Servicio_Comunitario')">
                <i class="fas fa-file-csv"></i> Exportar a CSV
            </button>
            <button class="export-btn btn-pdf" onclick="exportTableToPDF('tablaProyectos', 'Proyectos_Servicio_Comunitario')">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
        </div>
    </div>
    
    <?php
    mostrarTablaProyectos($result_proyectos, "Listado de Estudiantes en Proyectos");
    ?>
</div>

</body>
</html>