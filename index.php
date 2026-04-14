<?php
declare(strict_types=1);

$databasePath = __DIR__ . '/database.db';
$db = new PDO('sqlite:' . $databasePath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec(
    'CREATE TABLE IF NOT EXISTS tareas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        descripcion TEXT
    )'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre !== '') {
        $stmt = $db->prepare('INSERT INTO tareas (nombre, descripcion) VALUES (:nombre, :descripcion)');
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
        ]);
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $db->query('SELECT id, nombre, descripcion FROM tareas ORDER BY id DESC');
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica SQLite con PHP</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4efe7;
            --card: #fffaf4;
            --text: #2d241f;
            --accent: #c66b3d;
            --border: #dfcfbf;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top right, rgba(198, 107, 61, 0.18), transparent 28%),
                linear-gradient(180deg, #f8f2ea 0%, var(--bg) 100%);
            color: var(--text);
        }

        .contenedor {
            width: min(900px, calc(100% - 32px));
            margin: 40px auto;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 14px 32px rgba(59, 40, 28, 0.08);
        }

        h1,
        h2 {
            margin-top: 0;
        }

        p {
            line-height: 1.5;
        }

        form {
            display: grid;
            gap: 14px;
        }

        label {
            font-weight: 700;
        }

        input,
        textarea,
        button {
            font: inherit;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            color: var(--text);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button {
            width: fit-content;
            padding: 12px 22px;
            border: 0;
            border-radius: 999px;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee0d4;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f7e7d7;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .vacio {
            padding: 16px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            background: #fff;
        }

        @media (max-width: 640px) {
            .contenedor {
                margin: 24px auto;
            }

            .panel {
                padding: 18px;
            }

            table,
            thead,
            tbody,
            tr,
            th,
            td {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 12px;
                border: 1px solid var(--border);
                border-radius: 12px;
                overflow: hidden;
            }

            td {
                border-bottom: 1px solid #eee0d4;
            }

            td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 6px;
                font-weight: 700;
            }

            tbody tr:last-child td:last-child {
                border-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <main class="contenedor">
        <section class="panel">
            <h1>Registro de tareas con SQLite</h1>
            <p>Esta aplicación guarda tareas en una base de datos local llamada <strong>database.db</strong> usando PHP y SQLite.</p>

            <form method="POST">
                <div>
                    <label for="nombre">Nombre de la tarea</label>
                    <input id="nombre" type="text" name="nombre" placeholder="Escribe el nombre de la tarea" required>
                </div>

                <div>
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Escribe una breve descripción"></textarea>
                </div>

                <button type="submit">Guardar</button>
            </form>
        </section>

        <section class="panel" style="margin-top: 20px;">
            <h2>Tareas registradas</h2>

            <?php if ($tareas === []): ?>
                <div class="vacio">Aún no hay tareas guardadas. Agrega la primera desde el formulario.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas as $tarea): ?>
                            <tr>
                                <td data-label="ID"><?= htmlspecialchars((string) $tarea['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td data-label="Nombre"><?= htmlspecialchars($tarea['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td data-label="Descripción"><?= nl2br(htmlspecialchars($tarea['descripcion'] ?? '', ENT_QUOTES, 'UTF-8')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
