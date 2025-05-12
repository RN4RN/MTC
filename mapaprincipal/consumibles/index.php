<?php require 'conexion.php'; ?>
<?php
$sql = "SELECT id_equipo, nombre_equipo, descripcion, tipo_equipo, cantidad_total, cantidad_disponible, serie, estado, estacion, marca, modelo, tip_equip FROM equipos WHERE tipo_equipo = 'consumible'";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESTACIONES</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../volver.css">
  <style>
    .contenedor_equipos {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
    }
    .antenas {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      overflow-y: auto;
      width: 100%;
      height: auto;
      margin-top: 150px;
      position: relative;
      box-sizing: border-box;
      justify-content: center;
    }
    .card {
      position: relative;
      padding: 10px;
      width: 200px;
      height: 400px;
      border-radius: 20px;
      background: #212121;
      box-shadow: 5px 5px 8px #1b1b1b, -5px -5px 8px #272727;
      transition: 0.4s;
    }
    .card:hover {
      translate: 0 -10px;
    }
    .card-title {
      font-size: 15px;
      font-weight: 600;
      color: #b2eccf;
      margin: 5px 0 0 5px;
    }
    .card-image img {
      width: 100%;
      min-height: 170px;
      border-radius: 15px;
      background: #313131;
      box-shadow: inset 5px 5px 3px #2f2f2f, inset -5px -5px 3px #333333;
    }
    .card-body {
      margin: 13px 0 0 10px;
      color: rgb(184, 184, 184);
      font-size: 15px;
    }
    .foter {
      float: right;
      margin: 28px 0 0 18px;
      font-size: 13px;
      color: #b3b3b3;
    }
    .by-name {
      font-weight: 700;
    }
    .botton {
      height: 30px;
      width: 50px;
      background-color: #495057;
      border-radius: 20px;
    }
    .botton:hover {
      background-color: rgb(0, 204, 255);
      height: 40px;
      width: 60px;
    }
    .te {
      margin-top: 50px;
      width: 100%;
      height: 20%;
      color: white;
      position: absolute;
    }
  </style>
</head>
<div class="container"></div>
<body>
<div class="totalcont">
  <div class="barra">
      <!-- From Uiverse.io by xopc333 --> 
  <a href="http://localhost/nuevo/mapaprincipal/index.php"><button class="button"> 
  <div class="button-box">
    <span class="button-elem">
      <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
        ></path>
      </svg>
    </span>
    <span class="button-elem">
      <svg viewBox="0 0 46 40">
        <path
          d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
        ></path>
      </svg>
    </span>
  </div>
</button></a>
    <div class="search">
      <input placeholder="Buscar..." type="text">
      <button type="submit">Go</button>
    </div>
    <label class="bar" for="check">
      <input type="checkbox" id="check">
      <span class="top"></span>
      <span class="middle"></span>
      <span class="bottom"></span>
    </label>
    <div class="perfil">
      <img src="https://i.postimg.cc/T3ZGBW81/ingenieria-electronica-uch-universidad-560x416.png" alt="Perfil de Usuario">
    </div>
    <svg class="calendar" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M19 3h-1V2a1 1 0 1 0-2 0v1H8V2a1 1 0 1 0-2 0v1H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13zM9 13.5l1.5 1.5 3.5-3.5-1.5-1.5-2 2-1-1-1.5 1.5 2 2z"/>
    </svg>
  </div>

  <center>
    <div class="te">
      <h1>EQUIPOS CONSUMIBLES</h1>
      <p>Aquí podrás encontrar una variedad de equipos consumibles utilizados en las actividades operativas diarias. Estos elementos se caracterizan por tener una vida útil limitada, ya que están diseñados para ser utilizados y reemplazados con frecuencia.</p>
    </div>
  </center>

  <div class="contenedor_equipos">
    <div class="antenas">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card">
            <div class="card-image">
              <img src="" alt="Equipo">
            </div>
            <div class="card-title"><?= htmlspecialchars($row['nombre_equipo']) ?></div>
            <div class="card-body">
              <p><strong>Estación:</strong> <?= $row['estacion'] ?><br>
              <strong>Serie:</strong> <?= $row['serie'] ?><br>
              <strong>Marca:</strong> <?= $row['marca'] ?><br>
              <strong>Disponible:</strong> <?= $row['cantidad_disponible'] ?><br>
              <strong>Total:</strong> <?= $row['cantidad_total'] ?><br></p>
            </div>
            <center>
              <button class="botton" id="btnAbrir" 
                data-nombre="<?= htmlspecialchars($row['nombre_equipo']) ?>" 
                data-estado="<?= htmlspecialchars($row['estado']) ?>"
                data-modelo="<?= htmlspecialchars($row['modelo']) ?>"
                data-tipos="<?= htmlspecialchars($row['tip_equip']) ?>" 
                data-tip="<?= htmlspecialchars($row['tipo_equipo']) ?>"
                data-descripcion="<?= htmlspecialchars($row['descripcion']) ?>"  
                onclick="mostrarDescripcion(this)">Ver</button>
            </center>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No hay equipos consumibles registrados.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Ventana lateral -->
  <div id="ventanaDerecha" style="background-color:rgba(39, 39, 39, 0.94); filter: blur(4);">
    <button onclick="cerrarVentana()" style="float:right; border-radius: 20px;" id="btnCerrar">Cerrar</button>
    <h2 id="tituloEquipo">Nombre del equipo</h2>
    <p id="estado" style="color: white;"></p>
    <p id="modelo" style="color: white;"></p>
    <p id="tipo" style="color: white;"></p>
    <p id="tip" style="color: white;"></p>
    <p id="descripcionEquipo" style="color:white;"></p>
  </div>

  <footer class="footer">
    © 2025 Todos los derechos reservados | <a href="#">RNcorp</a> | <a href="#">Términos de uso</a>
  </footer>
</div>

<script>
  function mostrarDescripcion(boton) {
    const nombre = boton.getAttribute('data-nombre');
    const estado = boton.getAttribute('data-estado');
    const descripcion = boton.getAttribute('data-descripcion');
    const modelo = boton.getAttribute('data-modelo');
    const tipos = boton.getAttribute('data-tipos');
    const tip = boton.getAttribute('data-tip');

    document.getElementById('tituloEquipo').textContent = nombre;
    document.getElementById('estado').textContent = "Estado: " + estado;
    document.getElementById('descripcionEquipo').textContent = descripcion;
    document.getElementById('modelo').textContent = "Modelo: " + modelo;
    document.getElementById('tipo').textContent = "Tipo: " + tipos;
    document.getElementById('tip').textContent = "Categoría: " + tip;

    document.getElementById('ventanaDerecha').classList.add('mostrar');
  }

  function cerrarVentana() {
    document.getElementById('ventanaDerecha').classList.remove('mostrar');
  }
</script>
</body>
</html>