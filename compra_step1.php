<?php
session_start();
$_SESSION['compra'] = $_SESSION['compra'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Comprar Pasaje - Paso 1</title>
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
  <h2>Paso 1 de 4: Seleccionar servicio</h2>
  <form method="post" action="compra_step2.php">
    <div class="ticket-types">
      <label class="ticket-type">
        <input type="radio" name="tipo-servicio" value="general" required>
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBvPxHzYu59jogLPD6LfiBQJ_LWAZUYPOYBOWcT1g4_t7yLJJilXPa1TCN4JGYpAC4fp8&usqp=CAU" alt="General">
        <h3>General</h3>
        <p>Asientos cómodos y seguros para todos los pasajeros.</p>
      </label>
      <label class="ticket-type">
        <input type="radio" name="tipo-servicio" value="premium">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQDazu9XrCQTTmfQmpDSS8RY27vUXqB8XVXjpcB8tJd4m5mKHUFV-UAHeJ5JJ8bFFgfP0w&usqp=CAU" alt="Premium">
        <h3>Premium</h3>
        <p>Mayor espacio, servicios adicionales y confort superior.</p>
      </label>
      <label class="ticket-type">
        <input type="radio" name="tipo-servicio" value="presidencial">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS_6XJhTcoOzZGa4jkXEb_CyBjtPwa26NP4wQw4HX_qTmr2RkkL1ffS-XZu8n4i9MASoXo&usqp=CAU" alt="Presidencial">
        <h3>Presidencial</h3>
        <p>La máxima comodidad y exclusividad en tu viaje.</p>
      </label>
    </div>
    <br>
    <button type="submit" class="btn-primario">Siguiente →</button>
  </form>
</div>
</main>
<footer>&copy; 2025 Transportes Angel Divino</footer>
</body>
</html>
