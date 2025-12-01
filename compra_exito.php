<?php
session_start();
require __DIR__ . '/inc/db.php';

$pasaje_id = intval($_GET['pasaje_id'] ?? 0);
if($pasaje_id<=0){ echo "ID inválido"; exit; }

$stmt = $mysqli->prepare("
SELECT p.id,p.cantidad,p.total,p.estado,c.nombre,c.email,c.telefono,
v.origen,v.destino,v.fecha,v.horario
FROM pasajes p
JOIN clientes c ON p.cliente_id=c.id
JOIN viajes v ON p.viaje_id=v.id
WHERE p.id=?");
$stmt->bind_param('i',$pasaje_id);
$stmt->execute();
$pasaje = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $mysqli->prepare("SELECT asiento_num FROM pasajes_asientos WHERE pasaje_id=? ORDER BY asiento_num");
$stmt->bind_param('i',$pasaje_id);
$stmt->execute();
$res=$stmt->get_result();
$asientos=[];
while($r=$res->fetch_assoc()) $asientos[]=$r['asiento_num'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Compra Exitosa</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<header class="hero">
        <div class="hero-overlay">
            <h1>Transportes Angel Divino</h1>
        </div>
    </header>
<main>
<div class="compra-container">
<h2>✓ ¡Compra exitosa!</h2>
<p>Gracias <?php echo htmlspecialchars($pasaje['nombre']); ?>, su reserva se ha guardado.</p>
<ul>
<li>Ruta: <?php echo $pasaje['origen']." → ".$pasaje['destino']; ?></li>
<li>Fecha y hora: <?php echo $pasaje['fecha']." ".$pasaje['horario']; ?></li>
<li>Numero de Asiento: <?php echo implode(', ',$asientos); ?></li>
<li>Cantidad: <?php echo $pasaje['cantidad']; ?></li>
<li>Total: S/.<?php echo number_format($pasaje['total'],2); ?></li>
<li>Estado: <?php echo $pasaje['estado']; ?></li>
</ul>
<p>Se enviará un correo de confirmación a: <?php echo $pasaje['email']; ?></p>
<a href="index.html" class="btn-volver">Volver al inicio</a>
</div>
</main>
<footer>&copy; 2025 Transportes Angel Divino</footer>
</body>
</html>
