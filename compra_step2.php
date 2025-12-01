<?php
session_start();
require __DIR__ . '/inc/db.php';

// Obtener ciudades desde la BD
$ciudades = [];
$res = $mysqli->query("SELECT DISTINCT origen AS ciudad FROM viajes UNION SELECT DISTINCT destino FROM viajes");

if ($res && $res->num_rows) {
    while ($r = $res->fetch_assoc()) {
        $ciudades[] = $r['ciudad'];
    }
    $res->close();
}

// Validar paso anterior
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['tipo-servicio'])) {
    header('Location: compra_step1.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['compra']['tipo-servicio'] = $_POST['tipo-servicio'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Comprar Pasaje - Paso 2</title>
<link rel="stylesheet" href="estilos.css">
</head>

<body>

<header class="hero">
    <div class="hero-overlay">
        <h1>Transportes Angel Divino</h1>
        <nav>
            <a href="index.html">Información</a>
            <a href="compra_step1.php">Comprar Pasajes</a>
            <a href="contacto.html">Contacto</a>
        </nav>
    </div>
</header>

<main>
    <section class="compra-paso">
        <h2>Paso 2 de 4: Datos del viaje</h2>

        <form method="POST" action="compra_step3.php" class="form-pasajes">

            <div class="form-group">
                <label>Origen</label>
                <select name="origen" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($ciudades as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Destino</label>
                <select name="destino" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($ciudades as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de Viaje</label>
                <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
            </div>
            
          <div class="botones-flex">
              <a href="compra_step1.php" class="btn-volver">← Volver</a>
              <button type="submit" class="btn-primario">Siguiente →</button>
          </div>

        </form>
    </section>
</main>

<footer>
    &copy; 2025 Transportes Angel Divino
</footer>

</body>
</html>
