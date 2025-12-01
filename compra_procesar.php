<?php
session_start();
require __DIR__ . '/inc/db.php';

$viaje_id  = intval($_POST['viaje_id'] ?? 0);
$nombre    = trim($_POST['nombre'] ?? '');
$email     = trim($_POST['email'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$dni       = trim($_POST['dni'] ?? '');

$asientos        = $_POST['asientos'] ?? [];
$cantidad        = intval($_POST['cantidad_asientos'] ?? 0);
$total           = floatval($_POST['total_pagar'] ?? 0);
$precio_unitario = floatval($_POST['precio_unitario'] ?? 0);

// Validaciones básicas
if ($viaje_id <= 0 || !$nombre || !$email || empty($asientos)) {
    die("Error: Datos incompletos.");
}

// Validar coherencia
if ($cantidad !== count($asientos) || $cantidad <= 0 || $total <= 0 || $precio_unitario <= 0) {
    die("Error: Datos inconsistentes.");
}

// Insertar cliente
$stmt = $mysqli->prepare("INSERT INTO clientes(nombre, email, telefono, dni) VALUES(?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $email, $telefono, $dni);
$stmt->execute();
$cliente_id = $stmt->insert_id;
$stmt->close();

// Insertar pasaje
$estado = "confirmado";
$stmt = $mysqli->prepare("INSERT INTO pasajes(cliente_id, viaje_id, cantidad, total, estado) VALUES(?, ?, ?, ?, ?)");
$stmt->bind_param("iiids", $cliente_id, $viaje_id, $cantidad, $total, $estado);
$stmt->execute();
$pasaje_id = $stmt->insert_id;
$stmt->close();

// Asientos comprados
foreach ($asientos as $a) {

    $asiento = intval($a);

    // Registrar asiento
    $stmt = $mysqli->prepare("INSERT INTO pasajes_asientos(pasaje_id, asiento_num) VALUES(?, ?)");
    $stmt->bind_param("ii", $pasaje_id, $asiento);
    $stmt->execute();
    $stmt->close();

    // Marcarlo como ocupado
    $stmt = $mysqli->prepare("UPDATE asiento_disponibilidad SET ocupado = 1 WHERE viaje_id = ? AND asiento_num = ?");
    $stmt->bind_param("ii", $viaje_id, $asiento);
    $stmt->execute();
    $stmt->close();
}

header("Location: compra_exito.php?pasaje_id=" . $pasaje_id);
exit;