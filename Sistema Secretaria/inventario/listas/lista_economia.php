<?php
  error_reporting(0);
$search = $_POST['search'];
// Conexión a la base de datos (reemplazar con tus propias credenciales)
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "secretaria";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL segura usando sentencias preparadas
if (!empty($search)) {
    $sql = "SELECT * FROM economia WHERE cedula LIKE CONCAT(?, '%') ORDER BY apellidos ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM economia ORDER BY apellidos ASC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Secretaría - Economía</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../usuarios_list_stylesss.css">
  <!-- jsPDF y AutoTable -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <script>window.jsPDF = window.jspdf.jsPDF;</script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
   
  </style>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Otras bibliotecas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>


// Función para exportar la tabla a CSV
function exportTableToCSV(tableId, title = 'Lista de Estudiantes - Economía') {
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
        const fileName = `${title.replace(/[^\w\s]/gi, '').replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
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
function exportTableToPDF(tableId, title = 'Lista de Estudiantes - Economía') {
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
        img.src = '../css/logo1.jpg';
        
        // Tamaño del logo
        const logoWidth = 15;
        const logoHeight = 15;
        const logoX = (doc.internal.pageSize.width - 20) / 2 - 100; // Mover el logo más a la izquierda
        const logoY = 10;
        
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
        
        // Asegurar que 'GÉNERO' e 'INGRESO' aparezcan en líneas separadas en la tabla
        const tableElement = document.getElementById(tableId);
        if (tableElement) {
            const ths = tableElement.querySelectorAll('th');
            ths.forEach(th => {
                const text = th.textContent.trim();
                if (text.includes('GÉNERO') || text.includes('INGRESO')) {
                    th.style.whiteSpace = 'nowrap';
                    th.style.textAlign = 'center';
                }
            });
        }
        
        // Título de la tabla
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text(title, 14, 40);
        
        // Fecha de generación
        doc.setFontSize(10);
        doc.text(`Generado el: ${new Date().toLocaleDateString('es-VE', {day: '2-digit', month: '2-digit', year: 'numeric'})}`, 14, 45);
        
        // Línea separadora
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.2);
        doc.line(14, 47, 280, 47);

        // Obtener encabezados
        const headers = [];
        const headerRow = table.querySelector('thead tr');
        if (headerRow) {
            headerRow.querySelectorAll('th').forEach(th => {
                // Excluir columnas de acciones y botones
                const headerText = th.textContent.trim();
                if (!th.classList.contains('acciones-th') && 
                    !th.querySelector('button, a, .btn') && 
                    headerText !== 'ACCIONES') {
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
            startY: 55, // Ajustar para dar espacio al encabezado
            headStyles: {
                fillColor: [41, 128, 185], // Azul UNEFA
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                fontSize: 9,
                lineWidth: 0.1
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            },
            margin: { top: 50, right: 14, bottom: 20, left: 14 },
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
                1: { cellWidth: 'auto' },
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

        // Guardar el PDF
        const fileName = `${title.replace(/[^\w\s]/gi, '').replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);

    } catch (error) {
        console.error('Error al exportar a PDF:', error);
        alert('Error al exportar a PDF: ' + (error.message || 'Error desconocido'));
    }
}

function mostrarNotas(cedula) {
  document.getElementById('notasModal').dataset.currentCedula = cedula;
  fetch('notas.php?cedula=' + cedula)
    .then(response => response.text())
    .then(data => {
        document.getElementById('notasContainer').innerHTML = data;
        document.getElementById('notasModal').style.display = 'block';
    });
}

function cerrarModal() {
    document.getElementById('notasModal').style.display = 'none';
}
function habilitarEdicion(btn) {
    const row = btn.closest('tr');
    // Mostrar campos editables
    row.querySelectorAll('.edit-mode').forEach(el => el.style.display = '');
    row.querySelectorAll('.view-mode').forEach(el => el.style.display = 'none');
    // Botones
    row.querySelector('.btn-editar').style.display = 'none';
    row.querySelector('.btn-guardar').style.display = '';
    row.querySelector('.btn-cancelar').style.display = '';
}

function cancelarEdicion(btn) {
    const row = btn.closest('tr');
    // Restaurar valores originales
    const originalPeriodo = row.querySelector('[name="periodo"]').defaultValue;
    const originalDefinitiva = row.querySelector('[name="definitiva"]').defaultValue;
    
    row.querySelector('[name="periodo"]').value = originalPeriodo;
    row.querySelector('[name="definitiva"]').value = originalDefinitiva;
    
    // Ocultar campos
    row.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
    row.querySelectorAll('.view-mode').forEach(el => el.style.display = '');
    // Botones
    row.querySelector('.btn-editar').style.display = '';
    row.querySelector('.btn-guardar').style.display = 'none';
    row.querySelector('.btn-cancelar').style.display = 'none';
}

function guardarCambios(btn) {
    const row = btn.closest('tr');
    const id = row.dataset.id;
    const definitiva = parseFloat(row.querySelector('[name="definitiva"]').value);

    if(definitiva > 20) {
        alert('La nota no puede ser mayor a 20');
        return;
    }

    const datos = {
        id: id,
        periodo: row.querySelector('[name="periodo"]').value,
        definitiva: definitiva
    };

    fetch('actualizar_notas.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
    if(data.success) {
        alert('¡Edición completada con éxito!');
        window.location.reload();
    } else {
        alert('Error: ' + data.message);
    }
});
}

   

function eliminarNota(id) {
    if(confirm('¿Estás seguro de eliminar esta nota?')) {
        fetch('eliminar_nota.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.querySelector(`tr[data-id="${id}"]`).remove();
                alert('Nota eliminada');
            } else {
                alert('Error: ' + data.message);
            }
        });
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
        <!-- Barra de búsqueda -->
        <li class="menu-item menu-search-container">
            <form method="post" class="search-form">
                <input type="search" id="search" name="search" placeholder="Buscar por cédula..." class="menu-search" onclick="value='V-'">
                <i class="fas fa-search search-icon"></i>
            </form>
            <script>
                const input = document.getElementById("search");
                input.addEventListener("input", function(event) {
                    let value = event.target.value.replaceAll(".", "");
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    event.target.value = value;
                });
            </script>
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

  <div class="header-container">
    <h2>ECONOMÍA</h2>
    <div class="export-buttons-container">
        <button class="export-btn btn-csv" onclick="exportTableToCSV('tablaEstudiantes', 'Economia')">
            <i class="fas fa-file-csv"></i> Exportar a CSV
        </button>
        <button class="export-btn btn-pdf" onclick="exportTableToPDF('tablaEstudiantes', 'Economia')">
            <i class="fas fa-file-pdf"></i> Exportar a PDF
        </button>
    </div>
  </div>
  
  <div class="table-responsive">
    <table id="tablaEstudiantes" class="responsive-table">
      <thead>
        <tr>
          <th>CÉDULA</th>
          <th>NOMBRES</th>
          <th>APELLIDOS</th>
          <th>FECHA NAC.</th>
          <th>GÉNERO</th>
          <th>MILITAR</th>
          <th>RANGO</th>
          <th>TELÉFONO</th>
          <th>CORREO</th>
          <th>DIRECCIÓN</th>
          <th>INGRESO</th>
          <th>EGRESO</th>
          <th class="acciones-th">ACCIONES</th>
        </tr>
      </thead>
      <tbody>
  <?php
    // Mostrar los registros en la tabla
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td data-label="CÉDULA">' . htmlspecialchars($row["cedula"]) . '</td>';
        echo '<td data-label="NOMBRES">' . htmlspecialchars($row["nombre"]) . '</td>';
        echo '<td data-label="APELLIDOS">' . htmlspecialchars($row["apellidos"]) . '</td>';
        echo '<td data-label="FECHA NAC.">' . (!empty($row['fecha_nac']) ? date('d/m/Y', strtotime($row['fecha_nac'])) : '') . '</td>';
        echo '<td data-label="GÉNERO">' . htmlspecialchars($row["genero"]) . '</td>';
        echo '<td data-label="MILITAR">' . htmlspecialchars($row["militar"]) . '</td>';
        echo '<td data-label="RANGO">' . htmlspecialchars($row["rango"]) . '</td>';
        echo '<td data-label="TELÉFONO">' . htmlspecialchars($row["telefono"]) . '</td>';
        echo '<td data-label="CORREO">' . htmlspecialchars($row["correo"]) . '</td>';
        echo '<td data-label="DIRECCIÓN">' . htmlspecialchars($row["direccion"]) . '</td>';
        echo '<td data-label="INGRESO">' . (!empty($row['fecha_ing']) ? date('d/m/Y', strtotime($row['fecha_ing'])) : '') . '</td>';
        echo '<td data-label="EGRESO">' . (!empty($row['fecha_egr']) ? date('d/m/Y', strtotime($row['fecha_egr'])) : '') . '</td>';
        
        echo '<td data-label="ACCIONES" class="acciones-td">
          <div class="acciones-container">
            <button class="btn-accion btn-ver" onclick="mostrarNotas(\'' . htmlspecialchars($row["cedula"], ENT_QUOTES) . '\')">
              <i class="fas fa-eye"></i> <span>Ver Notas</span>
            </button>
            <a href="../editar/editar_estudiantes.php?cedula=' . htmlspecialchars($row["cedula"], ENT_QUOTES) . '&tabla=economia" class="btn-accion btn-editar">
              <i class="fas fa-edit"></i> <span>Editar</span>
            </a>
            <button class="btn-accion btn-eliminar" onclick="confirmarEliminacion(\'' . htmlspecialchars($row["cedula"], ENT_QUOTES) . '\', \'economia\')">
              <i class="fas fa-trash"></i> <span>Eliminar</span>
            </button>
            <button class="btn-accion btn-expediente" onclick="generarExpediente(\'' . htmlspecialchars($row["cedula"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["nombre"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["apellidos"], ENT_QUOTES) . '\', \'' . (!empty($row['fecha_ing']) ? date('d/m/Y', strtotime($row['fecha_ing'])) : '') . '\', \'' . htmlspecialchars($row["telefono"], ENT_QUOTES) . '\', \'' . htmlspecialchars($row["correo"], ENT_QUOTES) . '\')">
              <i class="fas fa-file-pdf"></i> <span>Expediente</span>
            </button>
          </div>
        </td>';
        echo '</tr>';
      }
    } else {
      echo '<tr><td colspan="13" style="text-align: center;">No se encontraron registros</td></tr>';
    }
    // Cerrar la conexión a la base de datos
    $conn->close();
  ?>
      </tbody>
    </table>
  </div>
  
  <div class="btn-regresar-container">
    <button type="button" class="btn-regresar" onclick="window.location.href='../'">
      <i class="fas fa-arrow-left"></i> Regresar
    </button>
  </div>
  
  <script>
  // Función para generar el expediente en PDF
function generarExpediente(cedula, nombre, apellidos, fechaIngreso, telefono, correo) {
    // Crear un nuevo documento PDF en orientación vertical
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // Agregar logo UNEFA
    const img = new Image();
    img.src = '../css/logo1.jpg';
    
    // Tamaño y posición del logo
    const logoWidth = 12;
    const logoHeight = 12;
    const logoX = 20; // Márgen izquierdo
    const logoY = 10; // Márgen superior más pequeño
    
    // Agregar logo si está cargado
    if (img.complete) {
        doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
    } else {
        img.onload = function() {
            doc.addImage(img, 'JPEG', logoX, logoY, logoWidth, logoHeight);
        };
    }
    
    // Configuración de texto centrado
    doc.setFontSize(10); // Tamaño de fuente más pequeño
    doc.setFont('helvetica', 'bold');
    
    // Textos del encabezado
    const headerTexts = [
        'UNIVERSIDAD NACIONAL EXPERIMENTAL',
        'POLITÉCNICA DE LA FUERZA ARMADA NACIONAL',
        'UNEFA - NÚCLEO BOLIVAR - EXTENSIÓN CIUDAD BOLÍVAR'
    ];
    
    // Calcular posiciones Y para cada línea de texto
    const startY = 10; // Comenzar más arriba
    const lineHeight = 5; // Menor espacio entre líneas
    
    // Agregar cada línea de texto centrada
    headerTexts.forEach((text, index) => {
        const textWidth = doc.getStringUnitWidth(text) * doc.getFontSize() / doc.internal.scaleFactor;
        const textX = (doc.internal.pageSize.width - textWidth) / 2;
        doc.text(text, textX, startY + (index * lineHeight));
    });
    
    // Línea divisoria debajo del encabezado
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.2);
    const headerBottomY = startY + (headerTexts.length * lineHeight) + 2;
    doc.line(20, headerBottomY, 190, headerBottomY);
    
    // Título del expediente
    doc.setFontSize(14); // Tamaño de fuente ligeramente más pequeño
    doc.setTextColor(40, 62, 80);
    const titleY = headerBottomY + 8;
    doc.text('EXPEDIENTE DEL ESTUDIANTE', 105, titleY, { align: 'center' });
    
    // Línea divisoria
    doc.setDrawColor(40, 62, 80);
    doc.setLineWidth(0.3);
    doc.line(20, titleY + 3, 190, titleY + 3);
    
    // Información del estudiante
    doc.setFontSize(11); // Tamaño de fuente ligeramente más pequeño
    doc.setTextColor(0, 0, 0);
    doc.setFont(undefined, 'bold');
    const datosY = titleY + 12;
    doc.text('DATOS PERSONALES', 20, datosY);
    
    // Línea divisoria
    doc.setDrawColor(200, 200, 200);
    doc.line(20, datosY + 3, 190, datosY + 3);
    
    // Ajustar la posición inicial del contenido
    let currentY = datosY + 10;
    
    // Función auxiliar para agregar campos
    const agregarCampo = (etiqueta, valor) => {
        doc.setFont(undefined, 'bold');
        doc.text(etiqueta + ':', 20, currentY);
        doc.setFont(undefined, 'normal');
        doc.text(valor || 'No especificado', 60, currentY);
        currentY += 7; // Espacio entre campos
        return currentY;
    };
    
    // Agregar campos
    agregarCampo('Cédula', cedula);
    agregarCampo('Nombres', nombre);
    agregarCampo('Apellidos', apellidos);
    agregarCampo('Fecha de Ingreso', fechaIngreso);
    agregarCampo('Teléfono', telefono);
    agregarCampo('Correo Electrónico', correo);
    agregarCampo('Carrera', 'ECONOMÍA');
    
    // Obtener la fecha actual
    const fechaActual = new Date().toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
    
    // Pie de página
    doc.setFontSize(9);
    doc.setTextColor(150, 150, 150);
    doc.text(`Generado el: ${fechaActual}`, 20, 280);
    doc.text('Sistema de Gestión de Estudiantes', 105, 280, { align: 'center' });
    doc.text('Página 1 de 1', 190, 280, { align: 'right', maxWidth: 30 });
    
    // Guardar el PDF
    doc.save(`Expediente_${cedula}.pdf`);
}

document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      
      if(urlParams.has('eliminado')) {
          alert('¡Registro eliminado exitosamente!');
          // Limpiar parámetros de la URL
          window.history.replaceState({}, document.title, window.location.pathname);
      }
      
      if(urlParams.has('error')) {
          alert('Error al eliminar el registro');
          window.history.replaceState({}, document.title, window.location.pathname);
      }
  });
  </script>
  
<!-- Modal de Notas -->
<div id="notasModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Notas del Estudiante</h3>
            <div class="export-buttons">
                <button onclick="exportNotasToCSV()" class="export-btn export-csv">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button onclick="exportNotasToPDF()" class="export-btn export-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <span class="close" onclick="cerrarModal('notasModal')">&times;</span>
            </div>
        </div>
        <div id="notasContainer"></div>
    </div>
</div>
<div id="modalBackdrop" class="modal-backdrop"></div>

<script>
// Función para confirmar y procesar la eliminación de un registro
function confirmarEliminacion(cedula, tabla) {
    if (confirm('¿Está seguro de que desea eliminar este registro?')) {
        // Mostrar indicador de carga
        const boton = event.target.closest('button');
        const originalHTML = boton.innerHTML;
        boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
        boton.disabled = true;

        // Redirigir a eliminar.php con los parámetros GET
        window.location.href = `eliminar.php?cedula=${encodeURIComponent(cedula)}&tabla=${encodeURIComponent(tabla)}`;
    }
}

// Cerrar el modal al hacer clic fuera del contenido
window.onclick = function(event) {
    const modal = document.getElementById('notasModal');
    if (event.target === modal) {
        cerrarModal('notasModal');
    }
}

// Función para exportar la tabla de notas a PDF
function exportNotasToPDF() {
    try {
        // Obtener el contenedor de notas
        const notasContainer = document.getElementById('notasContainer');
        if (!notasContainer) {
            alert('No se encontró el contenedor de notas');
            return;
        }

        // Obtener la tabla
        const table = notasContainer.querySelector('table');
        if (!table) {
            alert('No se encontró la tabla de notas');
            return;
        }


        // Crear un nuevo documento PDF
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Obtener datos del estudiante
        const notasModal = document.getElementById('notasModal');
        const studentCedula = notasModal?.dataset.currentCedula || '';
        
        // Obtener el nombre del estudiante del título
        const titleElement = document.querySelector('#notasContainer h3');
        let studentName = 'Estudiante';
        
        if (titleElement) {
            const titleText = titleElement.textContent.trim();
            // Extraer el nombre que está entre ':' y '('
            const match = titleText.match(/Notas del Estudiante:\s*([^(]+)/i);
            if (match && match[1]) {
                studentName = match[1].trim();
            }
            
            // Si no se pudo extraer, intentar otro patrón
            if (studentName === 'Estudiante' || studentName === '') {
                const parts = titleText.split(':');
                if (parts.length > 1) {
                    studentName = parts[1].split('(')[0].trim();
                }
            }
            
            // Si aún no hay nombre, usar la cédula
            if (!studentName || studentName === 'Estudiante') {
                studentName = studentCedula || 'Estudiante';
            }
        }
        
        // Configuración inicial
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text('HISTORIAL ACADÉMICO', 14, 20);
        
        // Información del estudiante
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        
        // Mostrar nombre del estudiante
        const studentInfo = studentName;
        
        // Calcular el ancho del texto
        const textWidth = doc.getStringUnitWidth(studentInfo) * doc.getFontSize() / doc.internal.scaleFactor;
        const pageWidth = doc.internal.pageSize.width - 30; // Margen de 15mm cada lado
        
        // Si el texto es muy ancho, reducimos el tamaño de fuente
        if (textWidth > pageWidth) {
            doc.setFontSize(9);
        }
        
        // Dibujar el texto
        doc.text(studentInfo, 14, 30, { maxWidth: pageWidth, align: 'left' });
        
        // Fecha de generación
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.text(`Generado el: ${new Date().toLocaleDateString('es-ES')}`, 14, 37);
        
        // Obtener los datos de la tabla
        const headers = [];
        const rows = [];
        
        // Obtener encabezados (excluyendo la última columna de acciones)
        const headerCells = table.querySelectorAll('th');
        headerCells.forEach((th, index) => {
            if (index < headerCells.length - 1) { // Excluir columna de acciones
                headers.push(th.textContent.trim());
            }
        });
        
        // Obtener filas de datos
        const dataRows = table.querySelectorAll('tr:not(:first-child)'); // Excluir la fila de encabezado
        
        dataRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 0) return; // Saltar si no es una fila de datos
            
            const rowData = [];
            
            // Procesar cada celda (excluyendo la última columna de acciones)
            for (let i = 0; i < cells.length - 1; i++) {
                const cell = cells[i];
                let cellContent;
                
                // Para la celda de nota (índice 3)
                if (i === 3) {
                    // Buscar primero en el input de edición, luego en el span de visualización, luego en el texto de la celda
                    const input = cell.querySelector('input[name="definitiva"]');
                    const span = cell.querySelector('.view-mode');
                    cellContent = (input?.value || span?.textContent || cell.textContent || '').toString().trim();
                } else {
                    // Para las celdas normales, obtener el texto directamente
                    cellContent = cell.textContent.trim();
                }
                // Reemplazar saltos de línea por espacios
                rowData.push(cellContent.replace(/\s+/g, ' '));
            }
            
            rows.push(rowData);
        });
        
        // Configurar estilos de la tabla
        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 45, // Espacio para el título y la información del estudiante
            margin: { top: 50, right: 14, bottom: 20, left: 14 },
            styles: {
                fontSize: 8,
            },
            headStyles: {
                fillColor: [41, 128, 185],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                lineWidth: 0.1,
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            },
            columnStyles: {
                0: { cellWidth: 25 }, // Ancho columna Periodo
                1: { cellWidth: 60 }, // Ancho columna Materia
                2: { cellWidth: 25 }, // Ancho columna Código
                3: { cellWidth: 25, halign: 'center' }, // Ancho columna Definitiva
            },
            didDrawPage: function(data) {
                // Número de página
                const pageCount = doc.internal.getNumberOfPages();
                const pageSize = doc.internal.pageSize;
                const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
                
                doc.setFontSize(8);
                doc.text(
                    `Página ${data.pageNumber} de ${pageCount}`,
                    data.settings.margin.left,
                    pageHeight - 10,
                    { align: 'left' }
                );
            }
        });
        
        // Guardar el PDF
        const fileName = `Notas_${studentName.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);
        
    } catch (error) {
        console.error('Error al exportar a PDF:', error);
        alert('Error al exportar a PDF: ' + (error.message || 'Error desconocido'));
    }
}
</script>

</body>
</html>