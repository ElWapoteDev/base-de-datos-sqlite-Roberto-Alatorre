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
    <title>Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", system-ui, sans-serif;
            color: #fff;
            background: #000;
        }
        .card-ios {
            background: #1c1c1e;
            border-radius: 14px;
            border: none;
        }
        .form-control {
            background: #2c2c2e;
            border: 1px solid #3a3a3c;
            color: #fff;
            border-radius: 10px;
        }
        .form-control:focus {
            background: #2c2c2e;
            border-color: #0a84ff;
            box-shadow: none;
            color: #fff;
        }
        .form-control::placeholder { color: #636366; }
        .btn-primary {
            background: #0a84ff;
            border: none;
            border-radius: 10px;
        }
        .btn-primary:hover { background: #0070e0; }
        .list-group-item {
            background: transparent;
            border-bottom: 1px solid #38383a;
            color: #fff;
            padding: 14px 20px;
        }
        .list-group-item:last-child { border-bottom: 0; }
        .text-desc { color: rgba(235,235,245,.6); }
        .empty { color: #636366; }
    </style>
</head>
<body>
<div class="container py-5" style="max-width:640px">
    <h1 class="text-center mb-4 fw-light" style="letter-spacing:-.5px">Tareas</h1>

    <div class="card-ios p-4 mb-3">
        <form method="POST">
            <div class="mb-3">
                <input class="form-control" name="nombre" type="text" placeholder="Nombre" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="descripcion" placeholder="Descripción" style="height:90px"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Guardar</button>
        </form>
    </div>

    <div class="card-ios">
        <?php if ($tareas === []): ?>
            <p class="empty text-center py-4 mb-0">Sin tareas</p>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($tareas as $t): ?>
                    <div class="list-group-item">
                        <div class="fw-medium"><?= htmlspecialchars($t['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php if (!empty($t['descripcion'])): ?>
                            <small class="text-desc"><?= htmlspecialchars($t['descripcion'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
