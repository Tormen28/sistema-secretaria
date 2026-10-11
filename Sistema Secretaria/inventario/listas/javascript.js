// shared-export.js
function exportTableToPDFLite(tableId, title = 'Lista de Estudiantes') {
    try {
        const table = document.getElementById(tableId);
        if (!table) {
            console.error('No se encontró la tabla para exportar');
            return;
        }

        // Clonar la tabla para modificar su contenido
        const exportTable = table.cloneNode(true);
        const rows = exportTable.querySelectorAll('tr');
        
        // Eliminar columnas de acción
        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            // Eliminar la última celda si contiene botones o es una columna de acciones
            if (cells.length > 0) {
                const lastCell = cells[cells.length - 1];
                if (lastCell.querySelector('button, a, .btn, .acciones') || 
                    lastCell.classList.contains('acciones-td') ||
                    lastCell.textContent.trim() === '') {
                    row.removeChild(lastCell);
                }
            }
        });

        // Inicializar jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'l', // horizontal
            unit: 'mm'
        });

        // Título del documento
        doc.setFontSize(16);
        doc.text(title, 14, 10);
        doc.setFontSize(10);
        doc.text(`Generado el: ${new Date().toLocaleDateString()}`, 14, 15);

        // Convertir la tabla a un array de arrays
        const tableData = [];
        const headers = [];
        
        // Obtener encabezados
        const headerRow = exportTable.querySelector('thead tr');
        if (headerRow) {
            headerRow.querySelectorAll('th').forEach(th => {
                headers.push(th.textContent.trim());
            });
            tableData.push(headers);
        }

        // Obtener datos de la tabla
        const dataRows = exportTable.querySelectorAll('tbody tr');
        dataRows.forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach(cell => {
                rowData.push(cell.textContent.trim());
            });
            if (rowData.length > 0) {
                tableData.push(rowData);
            }
        });

        // Configuración de la tabla
        const tableConfig = {
            head: [headers],
            body: tableData.slice(1), // Excluir encabezados del cuerpo
            startY: 20, // Posición Y para empezar la tabla
            headStyles: {
                fillColor: [200, 200, 200],
                textColor: 0,
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            },
            margin: { top: 25 },
            styles: {
                fontSize: 8,
                cellPadding: 2,
                overflow: 'linebreak',
                lineWidth: 0.1
            },
            columnStyles: {
                0: { cellWidth: 'auto' },
                1: { cellWidth: 'auto' },
                // Ajustar el ancho de las columnas según sea necesario
            }
        };

        // Agregar la tabla al documento
        doc.autoTable(tableConfig);

        // Guardar el PDF
        const fileName = `${title.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);

    } catch (error) {
        console.error('Error al exportar a PDF:', error);
        alert('Error al exportar a PDF: ' + error.message);
    }
}