<?php
include('conexion.php'); // Asegúrate de que este archivo defina $conn correctamente

// Consultas para el Artículo 118
// ==============================
$query_causal_a = "
    SELECT cedula, nombres, apellidos, nombre_materia, COUNT(*) AS veces_reprobada
    FROM notas
    WHERE definitiva < 10
    GROUP BY cedula, nombre_materia
    HAVING COUNT(*) >= 3
";

$query_causal_b = "
    SELECT 
        cedula, 
        nombres,
        apellidos,
        periodo, 
        (SUM(CASE WHEN definitiva < 10 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) AS porcentaje_reprobado
    FROM notas
    GROUP BY cedula, periodo
    HAVING porcentaje_reprobado > 50
";

$query_causal_c = "
   SELECT notas.cedula, nombres, apellidos, COUNT(*) AS reprobaciones_practicas  
    FROM notas  
    JOIN (
        SELECT codigo, tipo_materia FROM pensum_ads
        UNION ALL
        SELECT codigo, tipo_materia FROM pensum_civil
        UNION ALL
        SELECT codigo, tipo_materia FROM pensum_enfermeria
        UNION ALL
        SELECT codigo, tipo_materia FROM pensum_economia
        UNION ALL
        SELECT codigo, tipo_materia FROM pensum_desastres
    ) AS todos_pensums 
    ON notas.codigo = todos_pensums.codigo  
    WHERE todos_pensums.tipo_materia = 'practica' 
      AND notas.definitiva < 10
    GROUP BY notas.cedula  
    HAVING COUNT(*) >= 2
";

// Función para mostrar tablas
function mostrarTabla($result, $titulo, $id_tabla) {
    echo "<div class='table-responsive'>";
    echo "<h3>$titulo</h3>";
    if ($result->num_rows > 0) {
        echo "<table id='$id_tabla' class='responsive-table'>";
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
                // Formatear números decimales si es porcentaje
                if ($key === 'porcentaje_reprobado') {
                    echo number_format($value, 2) . '%';
                } else {
                    echo htmlspecialchars($value);
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='no-results'>No hay registros que cumplan con estos criterios</p>";
    }
    echo "</div>";
}

// Ejecutar consultas y manejar errores
try {
    $result_a = $conn->query($query_causal_a);
    $result_b = $conn->query($query_causal_b);
    $result_c = $conn->query($query_causal_c);
} catch (Exception $e) {
    die("Error en las consultas: " . $e->getMessage());
}

// Cerrar conexión DESPUÉS de usarla
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículo 118 - Sistema UNEFA</title>
    <link rel="stylesheet" type="text/css" href="../usuarios_list_stylesss.css">
    <!-- jsPDF y AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script>window.jsPDF = window.jspdf.jsPDF;</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Otras bibliotecas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
   
    <script>
    // Función para exportar la tabla a CSV
    function exportTableToCSV(tableId, title = 'Reporte Artículo 118') {
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
                `Generado el: ${new Date().toLocaleDateString('es-VE', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'})}`,
                '' // Espacio en blanco antes de los encabezados
            ];
            
            const rows = table.querySelectorAll('tr');
            
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
                        rowData.push(cleanCSVText(cellContent.textContent));
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
            const fileName = `Articulo_118_${title.replace(/[^\w\s]/gi, '').replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
            link.setAttribute('download', fileName);
            link.style.visibility = 'hidden';
            
            // Agregar al documento y hacer clic
            document.body.appendChild(link);
            link.click();
            
            // Limpiar
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            
        } catch (error) {
            console.error('Error al exportar a CSV:', error);
            alert('Error al exportar a CSV: ' + (error.message || 'Error desconocido'));
        }
    }

    // Función para exportar la tabla a PDF
    function exportTableToPDF(tableId, title = 'Reporte Artículo 118') {
        try {
            const table = document.getElementById(tableId);
            if (!table) {
                console.error('No se encontró la tabla para exportar');
                alert('No se pudo encontrar la tabla para exportar');
                return;
            }

            // Crear un nuevo documento PDF
            const doc = new jsPDF({
                orientation: 'l', // horizontal
                unit: 'mm'
            });
            
            // Agregar logo y encabezado
            const img = new Image();
            img.src = '../../css/logo1.jpg';
            
            // Tamaño del logo
            const logoWidth = 15;
            const logoHeight = 15;
            const logoX = 14; // Margen izquierdo
            const logoY = 10; // Margen superior
            
            // Agregar logo
            if (img.complete) {
                doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
            } else {
                img.onload = function() {
                    doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
                };
            }
            
            // Configuración de texto centrado
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            
            // Calcular posición X para centrar el texto
            const pageWidth = doc.internal.pageSize.width;
            const text1 = 'UNIVERSIDAD NACIONAL EXPERIMENTAL';
            const text2 = 'POLITÉCNICA DE LA FUERZA ARMADA NACIONAL';
            const text3 = 'UNEFA - NÚCLEO BOLIVAR - EXTENSIÓN CIUDAD BOLÍVAR';
            
            // Centrar cada línea de texto
            const text1Width = doc.getStringUnitWidth(text1) * doc.getFontSize() / doc.internal.scaleFactor;
            const text2Width = doc.getStringUnitWidth(text2) * doc.getFontSize() / doc.internal.scaleFactor;
            const text3Width = doc.getStringUnitWidth(text3) * doc.getFontSize() / doc.internal.scaleFactor;
            
            const text1X = (pageWidth - text1Width) / 2;
            const text2X = (pageWidth - text2Width) / 2;
            const text3X = (pageWidth - text3Width) / 2;
            
            // Ajustar posición Y para el texto
            doc.text(text1, text1X, 15);
            doc.text(text2, text2X, 22);
            doc.text(text3, text3X, 29);
            
            // Título del reporte
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            const titleWidth = doc.getStringUnitWidth(title) * doc.getFontSize() / doc.internal.scaleFactor;
            doc.text(title, (pageWidth - titleWidth) / 2, 40);
            
            // Fecha de generación
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            const dateText = `Generado el: ${new Date().toLocaleDateString('es-VE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })}`;
            const dateWidth = doc.getStringUnitWidth(dateText) * doc.getFontSize() / doc.internal.scaleFactor;
            doc.text(dateText, (pageWidth - dateWidth) / 2, 45);

            // Línea separadora
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.2);
            doc.line(14, 50, pageWidth - 14, 50);

            // Obtener encabezados
            const headers = [];
            const headerRow = table.querySelector('thead tr');
            if (headerRow) {
                headerRow.querySelectorAll('th').forEach(th => {
                    // Excluir columnas de acciones
                    if (!th.classList.contains('acciones-th') && !th.querySelector('button, a, .btn')) {
                        const headerText = th.textContent.trim();
                        headers.push({
                            header: headerText,
                            dataKey: th.getAttribute('data-field') || headerText.toLowerCase().replace(/\s+/g, '_')
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
                
                // Procesar solo las celdas que corresponden a los encabezados
                headers.forEach((header, index) => {
                    const cell = cells[index];
                    if (cell) {
                        rowData[header.dataKey] = cell.textContent.trim();
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
                startY: 60, // Espacio para el encabezado
                headStyles: {
                    fillColor: [0, 56, 101], // Azul UNEFA
                    textColor: [255, 255, 255],
                    fontStyle: 'bold',
                    fontSize: 9,
                    halign: 'center'
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                margin: { 
                    top: 60, 
                    right: 14, 
                    bottom: 20, 
                    left: 14 
                },
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                    overflow: 'linebreak',
                    lineWidth: 0.1,
                    valign: 'middle',
                    textColor: [0, 0, 0],
                    font: 'helvetica'
                },
                columnStyles: {
                    0: { cellWidth: 'auto' },
                    1: { cellWidth: 'auto' }
                    // Ajustar ancho de columnas específicas si es necesario
                },
                didDrawPage: function(data) {
                    // Agregar número de página
                    const pageSize = doc.internal.pageSize;
                    const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
                    doc.setFontSize(8);
                    doc.text(
                        `Página ${data.pageNumber} de ${data.pageCount}`,
                        data.settings.margin.left,
                        pageHeight - 10,
                        { align: 'left' }
                    );
                }
            };

            // Agregar la tabla al documento
            doc.autoTable(tableConfig);
            
            // Guardar el PDF con nombre descriptivo
            const now = new Date();
            const formattedDate = `${now.getFullYear()}${String(now.getMonth() + 1).padStart(2, '0')}${String(now.getDate()).padStart(2, '0')}`;
            const fileName = `Articulo_118_${title.replace(/[^\w\s]/gi, '')}_${formattedDate}.pdf`;
            
            // Guardar el documento
            doc.save(fileName);

        } catch (error) {
            console.error('Error al exportar a PDF:', error);
            alert('Error al generar el archivo PDF. Por favor, intente nuevamente.');
            if (error.stack) {
                console.error('Stack trace:', error.stack);
            }
        }
}    </script>
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

<!-- Contenido Principal -->
<div class="main-content" style="padding: 20px;">
    <div class="header-container">
        <h2 style="color: #0056b3; margin: 0;">Aplicación del Artículo 118</h2>
    </div>
    
    <div class="export-section">
     
        <div class="export-buttons-container">
            <button class="export-btn btn-csv" onclick="exportTableToCSV('tablaCausalA', 'Articulo_118_Causal_A')">
                <i class="fas fa-file-csv"></i> Exportar a CSV
            </button>
            <button class="export-btn btn-pdf" onclick="exportTableToPDF('tablaCausalA', 'Articulo_118_Causal_A')">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
        </div>
        <?php mostrarTabla($result_a, "Causal A: 3 reprobaciones en la misma materia", "tablaCausalA"); ?>
    </div>
    
    <div class="export-section">
       
        <div class="export-buttons-container">
            <button class="export-btn btn-csv" onclick="exportTableToCSV('tablaCausalB', 'Articulo_118_Causal_B')">
                <i class="fas fa-file-csv"></i> Exportar a CSV
            </button>
            <button class="export-btn btn-pdf" onclick="exportTableToPDF('tablaCausalB', 'Articulo_118_Causal_B')">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
        </div>
        <?php mostrarTabla($result_b, "Causal B: >50% de materias reprobadas en un período", "tablaCausalB"); ?>
    </div>
    
    <div class="export-section">
      
        <div class="export-buttons-container">
            <button class="export-btn btn-csv" onclick="exportTableToCSV('tablaCausalC', 'Articulo_118_Causal_C')">
                <i class="fas fa-file-csv"></i> Exportar a CSV
            </button>
            <button class="export-btn btn-pdf" onclick="exportTableToPDF('tablaCausalC', 'Articulo_118_Causal_C')">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </button>
        </div>
        <?php mostrarTabla($result_c, "Causal C: 2 reprobaciones en prácticas", "tablaCausalC"); ?>
    </div>
</div>

</body>
</html>