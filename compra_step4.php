<?php
session_start();
require __DIR__ . '/inc/db.php';

// ==========================
// Obtener datos de viaje
// ==========================
$viaje_id = intval($_POST['viaje_id'] ?? $_SESSION['compra']['viaje_id'] ?? 0);

if ($viaje_id <= 0) {
    header('Location: compra_step3.php');
    exit;
}

$_SESSION['compra']['viaje_id'] = $viaje_id;

// Consultar datos del viaje y bus
$stmt = $mysqli->prepare("
    SELECT v.precio, v.bus_id, b.capacidad, COALESCE(b.cantidad_piso,1) AS cantidad_piso
    FROM viajes v
    JOIN buses b ON v.bus_id = b.id
    WHERE v.id = ?
");
$stmt->bind_param('i', $viaje_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$info) {
    header('Location: compra_step3.php');
    exit;
}

$capacidad = intval($info['capacidad']);
$cantidadPisos = max(1, intval($info['cantidad_piso']));
$precio_unitario = floatval($info['precio']);

// ==========================
// Obtener asientos ocupados
// ==========================
$res = $mysqli->prepare("SELECT asiento_num FROM asiento_disponibilidad WHERE viaje_id = ? AND ocupado = 1");
$res->bind_param('i', $viaje_id);
$res->execute();
$resul = $res->get_result();

$ocupados = [];
while ($r = $resul->fetch_assoc()) {
    $ocupados[] = intval($r['asiento_num']);
}
$res->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Comprar Pasaje - Paso 4</title>
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

<h2>Paso 4 de 4: Seleccionar asientos y datos</h2>

<form method="POST" action="compra_procesar.php" id="formCompra">
    <input type="hidden" name="viaje_id" value="<?php echo htmlspecialchars($viaje_id); ?>">

    <!-- Inputs ocultos necesarios para JavaScript -->
    <input type="hidden" id="cantidad_asientos" name="cantidad_asientos" value="0">
    <input type="hidden" id="total_pagar" name="total_pagar" value="0">
    <input type="hidden" id="precio_unitario_input" name="precio_unitario" value="0">

    <!-- ==================== -->
    <!--   SECCIÓN ASIENTOS   -->
    <!-- ==================== -->
    <div class="compra-apartado">
        <h3>3. Elige tus asientos</h3>

        <div class="bus-silueta-grande">

            <?php if ($cantidadPisos === 1): ?>
                <div class="bus-piso">
                    <div class="piso-label">Único Piso</div>
                    <div class="bus-grid bus-grid-1">
                    <?php for ($i = 1; $i <= $capacidad; $i++):
                        $ocupado = in_array($i, $ocupados);
                    ?>
                        <label class="asiento <?php echo $ocupado ? 'ocupado' : 'disponible'; ?>">
                            <input type="checkbox" name="asientos[]" value="<?php echo $i; ?>" <?php echo $ocupado ? 'disabled' : ''; ?>>
                            <span><?php echo $i; ?></span>
                        </label>
                    <?php endfor; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php $mitad = intval(ceil($capacidad / 2)); ?>
                <!-- Primer piso -->
                <div class="bus-piso">
                    <div class="piso-label">1er Piso</div>
                    <div class="bus-grid bus-grid-1">
                    <?php for ($i = 1; $i <= $mitad; $i++):
                        $ocupado = in_array($i, $ocupados);
                    ?>
                        <label class="asiento <?php echo $ocupado ? 'ocupado' : 'disponible'; ?>">
                            <input type="checkbox" name="asientos[]" value="<?php echo $i; ?>" <?php echo $ocupado ? 'disabled' : ''; ?>>
                            <span><?php echo $i; ?></span>
                        </label>
                    <?php endfor; ?>
                    </div>
                </div>
                <!-- Segundo piso -->
                <div class="bus-piso">
                    <div class="piso-label">2do Piso</div>
                    <div class="bus-grid bus-grid-2">
                    <?php for ($i = $mitad + 1; $i <= $capacidad; $i++):
                        $ocupado = in_array($i, $ocupados);
                    ?>
                        <label class="asiento <?php echo $ocupado ? 'ocupado' : 'disponible'; ?>">
                            <input type="checkbox" name="asientos[]" value="<?php echo $i; ?>" <?php echo $ocupado ? 'disabled' : ''; ?>>
                            <span><?php echo $i; ?></span>
                        </label>
                    <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <small>Selecciona libremente los asientos que deseas.</small>
    </div>

    <!-- ====================== -->
    <!--  Cálculo dinámico      -->
    <!-- ====================== -->
    <p>
        Precio unitario: S/ <strong id="precioUnitario"><?php echo number_format($precio_unitario,2); ?></strong> |
        Cantidad: <strong id="cantidadAsientos">0</strong> |
        Total: S/ <strong id="totalPagar">0.00</strong>
    </p>

    <!-- ============================= -->
    <!--   FORMULARIO DATOS PASAJERO   -->
    <!-- ============================= -->
    <div class="compra-apartado">
        <h3>4. Datos del pasajero</h3>
        <div class="datos-pasajero">
            <label>Nombre completo:</label>
            <input type="text" name="nombre" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Teléfono:</label>
            <input type="tel" name="telefono">

            <label>DNI:</label>
            <input type="text" name="dni" required>
        </div>

        <div class="botones-flex">
            <a href="compra_step3.php" class="btn-volver">← Volver</a>
            <button type="submit" class="btn-primario">Confirmar compra</button>
        </div>
    </div>

</form>

</div>
</main>

<footer>&copy; 2025 Transportes Angel Divino</footer>

<!-- Script JavaScript -->
<script src="js/asientos.js"></script>
</body>
</html>