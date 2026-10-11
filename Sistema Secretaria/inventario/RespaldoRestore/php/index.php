<header>
	<script src="../plugins/sweetalert/dist/sweetalert2.all.min.js"></script>
</header>
<?php
if (isset($_GET['exito'])) {

	if ($_GET['exito'] == 1) {
		echo '
		<script > 
	
	
		Swal.fire({
								icon: "success",
								title: "¡Restauracion exitosa!",
								
								showConfirmButton: true,
								confirmButtonText: "Cerrar"
							}).then(function(result) {
								if (result.value) {
									window.location = "index.php";
								}
							});
	
	
	</script>
		
		';
	}
	if ($_GET['exito'] == 2) {
		echo '
		<script > 
	
	
		Swal.fire({
								icon: "success",
								title: "¡Creacion del respaldo excitosa!",
								
								showConfirmButton: true,
								confirmButtonText: "Cerrar"
							}).then(function(result) {
								if (result.value) {
									window.location = "index.php";
								}
							});
	
	
	</script>
		
		';
	}
}


?>



<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Script php backup and restore Mysqli</title>
	<link rel="stylesheet" href="../style.css">

</head>

<body>
	
<div class="caja__formulario--fields">
	
	<form action="./Restore.php" method="POST">
		<label>Selecciona un punto de restauración</label><br>
		<select name="restorePoint" required>
			<option value="" disabled="" selected="">Selecciona un punto de restauración</option>
			<?php
			include_once './Connet.php';
			$ruta = BACKUP_PATH;
			if (is_dir($ruta)) {
				if ($aux = opendir($ruta)) {
					while (($archivo = readdir($aux)) !== false) {
						if ($archivo != "." && $archivo != "..") {
							$nombrearchivo = str_replace(".sql", "", $archivo);
							$nombrearchivo = str_replace("-", ":", $nombrearchivo);
							$ruta_completa = $ruta . $archivo;
							if (is_dir($ruta_completa)) {
							} else {
								echo '<option value="' . $ruta_completa . '">' . $nombrearchivo . '</option>';
							}
						}
					}
					closedir($aux);
				}
			} else {
				echo $ruta . " No es ruta válida";
			}
			?>
			
		</select>
		<div class="caja__formulario--botones">
		<button type="submit">Restaurar</button>
		<a href="./Backup.php" class="submenu-link">Realizar copia de seguridad</a>
		<button type="submit" name="regresar" onclick="window.location.href='../../'">Regresar</button>
		</div>
	</div>
	
	</form>
	
</body>

</html>