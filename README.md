# Sistema Secretaría — gestión de expedientes e inventario

Sistema PHP para secretaría: registro y control de expedientes, usuarios con login/recuperación de clave y un módulo de inventario. Incluye las plantillas institucionales usadas en la práctica profesional.

## Estructura

```
Sistema Secretaria/
├── Sistema Secretaria/
│   ├── index.php · login/ · registro.php · recuperar_clave/
│   ├── includes/ · css/
│   ├── inventario/
│   ├── secretaria.sql                # esquema de base de datos
│   └── planillas y constancias (.docx / .pdf)
└── inventario/
```

> Los documentos `.docx`/`.pdf` de esta carpeta contienen datos de personas (planillas y constancias de estudiantes). El repositorio es **privado**; no publicar ni compartir.

## Puesta en marcha

1. Importar `secretaria.sql`.
2. Configurar la conexión en `includes/`.
3. Servir con Apache/PHP.

## Contexto

Sistema de secretaría desarrollado como práctica profesional: expedientes, usuarios e inventario en **PHP + MySQL**. El repositorio trae el código y el **esquema** de la base de datos, sin datos de personas (los respaldos y planillas quedan fuera).
