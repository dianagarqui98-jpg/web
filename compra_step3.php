<?php
session_start();
require __DIR__ . '/inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['origen']) || empty($_POST['destino']) || empty($_POST['fecha'])) {
    header('Location: compra_step2.php'); exit;
}

$_SESSION['compra']['origen'] = $_POST['origen'];
$_SESSION['compra']['destino'] = $_POST['destino'];
$_SESSION['compra']['fecha'] = $_POST['fecha'];

$origen = $_SESSION['compra']['origen'];
$destino = $_SESSION['compra']['destino'];
$fecha = $_SESSION['compra']['fecha'];

$stmt = $mysqli->prepare("
SELECT v.id, v.horario, v.precio, b.nombre AS bus
FROM viajes v
JOIN buses b ON v.bus_id = b.id
WHERE v.origen = ? AND v.destino = ? AND v.fecha = ?
ORDER BY v.horario
");
$stmt->bind_param('sss', $origen, $destino, $fecha);
$stmt->execute();
$res = $stmt->get_result();
$viajes = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Comprar Pasaje - Paso 3</title>
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
<div class="compra-container">
<h2>Paso 3 de 4: Selecciona el horario</h2>

<form method="post" action="compra_step4.php">

    <input type="hidden" name="origen" value="<?php echo $origen; ?>">
    <input type="hidden" name="destino" value="<?php echo $destino; ?>">
    <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">

    <div class="compra-apartado">
        <h3>Horarios disponibles</h3>

        <?php if(empty($viajes)): ?>
            <p>No hay horarios disponibles en esta fecha.</p>
            <a href="compra_step2.php" class="btn-volver">← Volver</a>

        <?php else: ?>
        <div class="horarios-disponibles">

            <?php foreach($viajes as $v): ?>
                <label class="horario-btn">
                    <input type="radio" name="viaje_id" value="<?php echo $v['id']; ?>" required>
                    <span>
                        <?php echo $v['horario']; ?> — S/ <?php echo number_format($v['precio'],2); ?>
                    </span>
                </label>
            <?php endforeach; ?>

        </div>

        <div class="botones-flex">
              <a href="compra_step2.php" class="btn-volver">← Volver</a>
              <button type="submit" class="btn-primario">Siguiente →</button>
        </div>

        <?php endif; ?>
    </div>
</form>

</div>
</main>

<footer>&copy; 2025 Transportes Angel Divino</footer>

</body>
</html>