<?php

    

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

//Metodo para iniciar sesion

class inicio_sesion extends conexion {
    public $id;

    public function inicioSession($nombreUsuario__Correo, $contraseña) {
        // Consulta que incluye el rol
        $stmt = $this->conexion->prepare(
            "SELECT id, Contraseña, rol FROM usuarios 
            WHERE Nombre_Usuario = ? OR Correo_Electronico = ?"
        );
        
        if (!$stmt) {
            return 100; // Error en la consulta
        }

        $stmt->bind_param("ss", $nombreUsuario__Correo, $nombreUsuario__Correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar contraseña hasheada
            if (password_verify($contraseña, $usuario['Contraseña'])) {
                $this->id = $usuario['id'];
                
                // Iniciar sesión si no está iniciada
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Guardar el rol en la sesión
                $_SESSION['user_role'] = $usuario['rol'];
                
                return 1; // Éxito
            } else {
                return 10; // Contraseña incorrecta
            }
        }
        
        $stmt->close();
        return 100; // Usuario no existe
    }
}


  


class Registration extends conexion {
    public function registrarse($nombrecomplete, $nombreUsuario, $cedula, $correo, $contraseña, $confirmar_contraseña) {
        // Verificar usuario existente (usando prepared statements)
        $stmt = $this->conexion->prepare("
            SELECT * FROM usuarios 
            WHERE Nombre_Usuario = ? 
            OR Correo_Electronico = ?
        ");
        $stmt->bind_param("ss", $nombreUsuario, $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return 10; // Usuario ya existe
        } else {
            if ($contraseña == $confirmar_contraseña) {
                // Insertar datos (sin incluir el ID)
                $stmt = $this->conexion->prepare("
                    INSERT INTO usuarios 
                    (Nombre_Completo, Nombre_Usuario, Cedula, Correo_Electronico, Contraseña, rol) 
                    VALUES (?, ?, ?, ?, ?, 'viewer')
                ");

                // Hashear la contraseña
                $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);

                $stmt->bind_param("sssss", 
                    $nombrecomplete,
                    $nombreUsuario,
                    $cedula,
                    $correo,
                    $hashed_password // Contraseña hasheada
                );

                if ($stmt->execute()) {
                    return 1; // Registro exitoso
                } else {
                    return 100; // Error en la inserción
                }
            } else {
                return 100; // Contraseñas no coinciden
            }
        }
    }
        
    public function preguntas($pregunta1, $respuesta1, $pregunta2, $respuesta2, $pregunta3, $respuesta3)
    {
        if ($pregunta1) {
            // Sentencia preparada
            $sql = "INSERT INTO preguntas_seguridad (
                    pregunta1, 
                    respuesta1, 
                    pregunta2, 
                    respuesta2, 
                    pregunta3, 
                    respuesta3
                ) VALUES (?, ?, ?, ?, ?, ?)";
    
            $stmt = mysqli_prepare($this->conexion, $sql);
            
            // Vincular parámetros
            mysqli_stmt_bind_param(
                $stmt,
                'ssssss',
                $pregunta1,
                $respuesta1,  // Ahora en texto plano
                $pregunta2,
                $respuesta2,  // Ahora en texto plano
                $pregunta3,
                $respuesta3   // Ahora en texto plano
            );
    
            // Ejecutar y verificar resultado
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return 1;
            } else {
                mysqli_stmt_close($stmt);
                return 100; // Error en la ejecución
            }
        } else {
            return 100; // Pregunta1 vacía
        }
    }
    }



class select extends conexion
{

        public function selectuserbyuser($id)
        {
            $resultado = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE id = '$id'" );

            return mysqli_fetch_array($resultado);
        }


} 



?>