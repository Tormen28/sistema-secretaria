<?php

    session_start();
//conexion a la base de datos
    class conexion
    {
        public $host = 'localhost';
        public $usuario = 'root';
        public $contraseña = '';
        public $base = 'secretaria';
        public $conexion;

        public function __construct()
        {
            $this->conexion = mysqli_connect(
                $this->host,
                $this->usuario,
                $this->contraseña,
                $this->base
                );
        }

    }


    //registar estudiantes
    class Registration extends conexion
    {
        public function registrarNotas(
            $periodo,
            $nombre_materia,
            $codigo_materia,
            $cedula,
            $nombres,
            $apellidos,
            $carrera,
            $definitiva,
            $tipo_materia
        ) {
            // Validar campos requeridos
            if (empty($carrera) || empty($cedula) || empty($definitiva)) {
                return 100;
            }
    
            // Validar carrera
            $carrerasValidas = ['ads', 'enfermeria', 'ingenieria_civil', 'desastres', 'economia'];
            if (!in_array($carrera, $carrerasValidas)) {
                return 101; // Código error: carrera inválida
            }
    
            try {
                // Preparar consulta para notas
                $sql = "INSERT INTO notas (
                    periodo, 
                    nombre_materia, 
                    codigo, 
                    cedula, 
                    nombres, 
                    apellidos, 
                    carrera, 
                    definitiva,
                    tipo_materia
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
                $stmt = mysqli_prepare($this->conexion, $sql);
                
                if (!$stmt) {
                    throw new Exception("Error en preparación: " . mysqli_error($this->conexion));
                }
    
                // Limpiar y formatear datos
                $cedula = str_replace(['.', '-'], '', $cedula);
                $definitiva = (float) $definitiva;
    
                // Vincular parámetros
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssssssds',
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
    
                // Ejecutar consulta
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    return 1; // Éxito
                } else {
                    throw new Exception("Error en ejecución: " . mysqli_stmt_error($stmt));
                }
    
            } catch (Exception $e) {
                error_log($e->getMessage());
                return 102; // Error general de base de datos
            }
        }
    

        //registar estudiantes
        
        public function estudiantes($cedula, $nombre, $apellidos, $carrera, $fecha_nac, $genero, $militar, $rango, $telefono, $correo, $direccion, $fecha_ing, $fecha_egr)
        {
            if ($cedula && $carrera) {
                // Validar y mapear la carrera a la tabla correspondiente
                $tablasCarreras = [
                    'ads' => 'ads', 
                    'enfermeria' => 'enfermeria',
                    'ingenieria_civil' => 'ingenieria_civil',
                    'desastres' => 'desastres',
                    'economia' => 'economia'
                ];
        
                if (!array_key_exists($carrera, $tablasCarreras)) {
                    return 100; // Código de error para carrera inválida
                }
        
                $tabla = $tablasCarreras[$carrera];
        
                try {
                    // 1. Verificar si la cédula ya existe
                    $sqlCheck = "SELECT cedula FROM $tabla WHERE cedula = ?";
                    $stmtCheck = mysqli_prepare($this->conexion, $sqlCheck);
                    
                    if (!$stmtCheck) {
                        throw new Exception("Error en verificación: " . mysqli_error($this->conexion));
                    }
                    
                    mysqli_stmt_bind_param($stmtCheck, 's', $cedula);
                    mysqli_stmt_execute($stmtCheck);
                    mysqli_stmt_store_result($stmtCheck);
                    
                    if (mysqli_stmt_num_rows($stmtCheck) > 0) {
                        mysqli_stmt_close($stmtCheck);
                        return 10; // Cédula ya registrada
                    }
                    mysqli_stmt_close($stmtCheck);
        
                    // 2. Insertar nuevo registro si no existe
                    $sql = "INSERT INTO $tabla (
                        cedula, 
                        nombre, 
                        apellidos, 
                        carrera, 
                        fecha_nac, 
                        genero,
                        militar,
                        rango,
                        telefono, 
                        correo, 
                        direccion,
                        fecha_ing, 
                        fecha_egr
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
                    $stmt = mysqli_prepare($this->conexion, $sql);
                    
                    if (!$stmt) {
                        throw new Exception("Error en preparación: " . mysqli_error($this->conexion));
                    }
        
                    mysqli_stmt_bind_param(
                        $stmt,
                        'sssssssssssss',
                        $cedula,
                        $nombre,
                        $apellidos,
                        $carrera,
                        $fecha_nac,
                        $genero,
                        $militar, 
                        $rango,
                        $telefono,
                        $correo,
                        $direccion,
                        $fecha_ing,
                        $fecha_egr
                    );
        
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        return 1; // Éxito
                    } else {
                        throw new Exception("Error en ejecución: " . mysqli_stmt_error($stmt));
                    }
        
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    return 100; // Error general
                }
                
            } else {
                return 100; // Campos requeridos faltantes
            }
        }
    }