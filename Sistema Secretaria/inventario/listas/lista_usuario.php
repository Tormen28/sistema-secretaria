<?php
error_reporting(0);
$search = $_POST['search'];
// Conexión a la base de datos
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

// Consulta SQL para obtener todos los registros
$sql = "SELECT * FROM usuarios WHERE Cedula LIKE '$search%' OR Nombre_Completo LIKE '%$search%' OR Nombre_Usuario LIKE '%$search%' ORDER BY Cedula DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>RRHH - Lista de Usuarios</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../usuarios_list_stylesss.css">

  <style>
    .content-wrapper {
      padding: 25px;
      max-width: 1200px;
      margin: 0 auto;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .user-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-top: 20px;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      font-size: 14px;
    }
    
    .user-table th {
      padding: 15px;
      text-align: left;
      color: #6c757d;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #e9ecef;
      background-color: #f8f9fa;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    .user-table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .user-table tbody tr:last-child td {
      border-bottom: none;
    }

    /* Estilo para filas impares */
    .user-table tbody tr:nth-child(odd) {
      background-color: #fcfcfc;
    }

    .user-table tbody tr:hover {
      background-color: #f1f3f5;
    }
    
    h2 {
      color: #2c3e50;
      margin: 0;
      padding: 0;
      font-size: 24px;
    }
    
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f2f5;
    }
    
    .search-input {
      padding: 8px 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      width: 250px;
      transition: all 0.3s ease;
    }
    
    .search-input:focus {
      outline: none;
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .rol-select {
      padding: 6px 12px;
      border-radius: 4px;
      border: 1px solid #ced4da;
      background-color: #fff;
      font-size: 14px;
      color: #495057;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
      cursor: pointer;
      min-width: 120px;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 8px center;
      background-size: 16px;
      padding-right: 30px;
    }
    
    .rol-select:focus {
      border-color: #80bdff;
      outline: 0;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .rol-select:disabled {
      background-color: #e9ecef;
      opacity: 0.8;
      cursor: not-allowed;
    }
    
    /* Estilo para el estado de administrador */
    .rol-select[value='admin'] {
      background-color: #e8f4fd;
      border-color: #b8daff;
      color: #004085;
    }
    
    /* Estilo para el estado de visitante */
    .rol-select[value='viewer'] {
      background-color: #e2e3e5;
      border-color: #d3d6d8;
      color: #383d41;
    }
    
    .export-buttons {
      margin: 15px 0;
      display: flex;
      gap: 10px;
    }
    
    .export-btn {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    
    .export-btn:hover {
      background-color: #45a049;
    }
    
    .acciones-td {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
    }
    .input-eliminar, .input-editar {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.2s ease-in-out;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .input-eliminar {
      background-color: #dc3545;
      color: white;
    }
    .input-eliminar:hover {
      background-color: #c82333;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .input-editar {
      background-color: #28a745;
      color: white;
    }
    .input-editar:hover {
      background-color: #218838;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    #notasModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.7);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      max-width: 80%;
      max-height: 80%;
      overflow-y: auto;
      position: relative;
    }
    .modal-close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #333;
    }
  </style>
</head>
<body>
  <nav class="logo-nav">
    <a href="../index.php" class="logo-link">Secretaria</a>
    <div class="logo-placeholder"></div>
  </nav>

  <nav class="menu">
    <ul class="menu-list">
      <li class="menu-item has-submenu">
        <a href="#" class="menu-link">Estudiantes<span class="submenu-icon">&#9660;</span></a>
        <ul class="submenu">
          <li class="submenu-item">
            <a href="../formularios/form_estudiantes.php" class="submenu-link">Registrar Estudiantes</a>
          </li>
          <li class="submenu-item">
            <a href="../listas/lista_ADS.php" class="submenu-link">Lista de Análisis y Diseño de Sistemas</a>
          </li>
          <li class="submenu-item">
            <a href="../listas/lista_enfermeria.php" class="submenu-link">Lista de Enfermeria</a>
          </li>
          <li class="submenu-item">
            <a href="../listas/lista_ingenieria_civil.php" class="submenu-link">Lista de Ingenieria Civil</a>
          </li>
          <li class="submenu-item">
            <a href="../listas/lista_desastres.php" class="submenu-link">Lista de Administración de Desastres</a>
          </li>
          <li class="submenu-item">
            <a href="../listas/lista_economia.php" class="submenu-link">Lista de Económia Social</a>
          </li>
        </ul>
      </li>
      
      <li class="menu-item has-submenu">
        <a href="#" class="menu-link">Servicio Comunitario<span class="submenu-icon">&#9660;</span></a>
        <ul class="submenu">
          <li class="submenu-item">
            <a href="../formularios/form_servicio_c.php" class="submenu-link">Registrar</a>
          </li>
          <li class="submenu-item">
            <a href="lista_obreros.php" class="submenu-link">Lista</a>
          </li>
        </ul>
      </li>
      
      <li class="menu-item has-submenu">
        <a href="#" class="menu-link">Pasantias<span class="submenu-icon">&#9660;</span></a>
        <ul class="submenu">
          <li class="submenu-item">
            <a href="../formularios/form_pasantias.php" class="submenu-link">Registrar</a>
          </li>
          <li class="submenu-item">
            <a href="lista_administrativos.php" class="submenu-link">Lista</a>
          </li>
        </ul>
      </li>

      <li class="menu-item has-submenu">
        <a href="#" class="menu-link">Materia<span class="submenu-icon">&#9660;</span></a>
        <ul class="submenu">
          <li class="submenu-item">
            <a href="../formularios/form_materia.php" class="submenu-link">Registrar</a>
          </li>
          <li class="submenu-item">
            <a href="#" class="submenu-link">Lista</a>
          </li>
        </ul>
      </li>
      
      <li class="menu-item">
        <form method="post" class="search-form">
          <input type="search" id="search" name="search" placeholder="Buscar" class="menu-search" value="<?php echo htmlspecialchars($search ?? ''); ?>">
          <script>
            const input = document.getElementById("search");
            input.addEventListener("input", function(event) {
              let value = event.target.value.replaceAll(".", "");
              value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              event.target.value = value;
            });
          </script>
        </form>
      </li>
      
      <li class="menu-item">
        <a href="../../login/cerrarsesion.php" class="menu-link">Salir</a>
      </li>
    </ul>
  </nav>

<div class="content-wrapper">
  <div class="table-header">
    <h2>LISTA DE USUARIOS</h2>
    <div class="table-actions">
      <input type="text" id="searchInput" placeholder="Buscar usuarios..." class="search-input">
    </div>
  </div>
  
  <table id='informes' class='user-table'>
  <thead>
  <tr>
      <th>NOMBRE COMPLETO</th>
      <th>CÉDULA</th>
      <th>USUARIO</th>
      <th>CORREO ELECTRÓNICO</th>
      <th>ROL</th>
      <th>ACCIONES</th>
  </tr>
  </thead>
  <tbody>
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . htmlspecialchars($row["Nombre_Completo"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["Cedula"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["Nombre_Usuario"]) . "</td>";
      echo "<td>" . htmlspecialchars($row["Correo_Electronico"]) . "</td>";
      echo "<td>
        <select class='rol-select' data-user-id='" . $row['id'] . "' onchange='updateUserRole(this)'>
          <option value='admin'" . ($row['rol'] === 'admin' ? ' selected' : '') . ">Administrador</option>
          <option value='viewer'" . ($row['rol'] === 'viewer' ? ' selected' : '') . ">Visitante</option>
        </select>
      </td>";
      echo "<td class='acciones-td'>";
      
      // Botón de eliminar con confirmación
      echo "<form method='post' action='../eliminar/eliminar_usuario.php' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>";
      echo "<input type='hidden' name='cedula' value='".$row['Cedula']."'>";
      echo "<button type='submit' class='input-eliminar'>Eliminar</button>";
      echo "</form>";
      echo "</td></tr>";
    
      
      echo "</div></td>";
      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='6' style='text-align: center;'>No se encontraron registros</td></tr>";
  }
  
  // Cerrar la conexión a la base de datos
  $conn->close();
  ?>
  </tbody>
  </table>
  
  <script>
  // Función para buscar en la tabla
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const input = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-table tbody tr');
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(input) ? '' : 'none';
    });
  });
  
  function updateUserRole(selectElement) {
    const userId = selectElement.getAttribute('data-user-id');
    const newRole = selectElement.value;
    const parentTd = selectElement.closest('td');
    
    if (!userId || !parentTd) {
      console.error('No se pudo obtener el ID del usuario o el elemento padre');
      alert('Error: No se pudo identificar al usuario');
      return;
    }
    
    // Guardar el valor original
    if (!selectElement.hasAttribute('data-original-value')) {
      selectElement.setAttribute('data-original-value', selectElement.value);
    }
    
    // Deshabilitar el select
    selectElement.disabled = true;
    
    // Mostrar indicador de carga
    const loadingSpan = document.createElement('span');
    loadingSpan.textContent = 'Actualizando...';
    loadingSpan.style.color = '#666';
    loadingSpan.style.fontStyle = 'italic';
    
    // Reemplazar el select con el mensaje de carga
    parentTd.innerHTML = '';
    parentTd.appendChild(loadingSpan);
    
    // Enviar la petición
    fetch('../actualizar_rol.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin',
      body: `user_id=${encodeURIComponent(userId)}&new_role=${encodeURIComponent(newRole)}`
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error en la red: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Mostrar mensaje de éxito
        loadingSpan.textContent = '¡Éxito!';
        loadingSpan.style.color = 'green';
        loadingSpan.style.fontWeight = 'bold';
        
        // Restaurar el select después de 1.5 segundos
        setTimeout(() => {
          // Crear un nuevo select
          const newSelect = document.createElement('select');
          newSelect.className = 'rol-select';
          newSelect.setAttribute('data-user-id', userId);
          newSelect.onchange = function() { updateUserRole(this); };
          
          // Agregar opciones
          const adminOption = document.createElement('option');
          adminOption.value = 'admin';
          adminOption.textContent = 'Administrador';
          adminOption.selected = (newRole === 'admin');
          
          const viewerOption = document.createElement('option');
          viewerOption.value = 'viewer';
          viewerOption.textContent = 'Visitante';
          viewerOption.selected = (newRole === 'viewer');
          
          newSelect.appendChild(adminOption);
          newSelect.appendChild(viewerOption);
          
          // Reemplazar el mensaje con el nuevo select
          parentTd.innerHTML = '';
          parentTd.appendChild(newSelect);
        }, 1500);
      } else {
        throw new Error(data.message || 'Error desconocido al actualizar el rol');
      }
    })
    .catch(error => {
      console.error('Error al actualizar el rol:', error);
      
      // Mostrar mensaje de error
      loadingSpan.textContent = 'Error. Intente de nuevo';
      loadingSpan.style.color = 'red';
      
      // Restaurar el select después de 2 segundos
      setTimeout(() => {
        const newSelect = document.createElement('select');
        newSelect.className = 'rol-select';
        newSelect.setAttribute('data-user-id', userId);
        newSelect.onchange = function() { updateUserRole(this); };
        
        // Agregar opciones con el valor original
        const originalValue = selectElement.getAttribute('data-original-value') || 'viewer';
        
        const adminOption = document.createElement('option');
        adminOption.value = 'admin';
        adminOption.textContent = 'Administrador';
        adminOption.selected = (originalValue === 'admin');
        
        const viewerOption = document.createElement('option');
        viewerOption.value = 'viewer';
        viewerOption.textContent = 'Visitante';
        viewerOption.selected = (originalValue === 'viewer');
        
        newSelect.appendChild(adminOption);
        newSelect.appendChild(viewerOption);
        
        // Reemplazar el mensaje con el nuevo select
        parentTd.innerHTML = '';
        parentTd.appendChild(newSelect);
      }, 2000);
    });
  }
  </script>
  
  <button type="button" class="btn-regresar" onclick="window.location.href='../'">Regresar</button>
</div>

<!-- Modal para notas (puedes personalizar según necesites) -->
<div id="notasModal">
  <span class="modal-close" onclick="cerrarModal()">X</span>
  <div id="notasContainer"></div>
</div>

<script>
// Mostrar mensaje si se eliminó un usuario
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('eliminado')) {
        alert('¡Usuario eliminado exitosamente!');
        // Limpiar parámetros de la URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if(urlParams.has('error')) {
        alert('Error al eliminar el usuario');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
};
</script>

</body>
</html>