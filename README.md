# Práctica SQLite con PHP

Aplicación web básica hecha con PHP y SQLite para registrar tareas.

## Requisitos

- XAMPP o cualquier entorno con PHP 8+

## Cómo ejecutar

1. Clona este repositorio dentro de tu carpeta `htdocs`.
2. Abre el proyecto en el navegador.
3. Usa la ruta:

```text
http://localhost/projects/practica_sqlite
```

## Qué hace el proyecto

- Crea automáticamente el archivo `database.db` si no existe.
- Crea la tabla `tareas` si todavía no está creada.
- Guarda tareas usando `POST`, `prepare` y `execute`.
- Muestra en pantalla la lista de tareas registradas.

## Estructura

```text
practica_sqlite/
├── index.php
├── README.md
└── database.db (se crea automáticamente)
```
