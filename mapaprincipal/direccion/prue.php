<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: http://localhost/nuevo/contrase%C3%B1a/indexlogin.php");
    exit();
}
?>
<?php
include("conexion1.php");

// Comprobamos si el usuario tiene el rol de Director
if ($_SESSION['rol'] != 'Director') {
    header("Location: index.php");
    exit;
}


// Asignar rol a un usuario
// Asignar rol a un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_rol'])) {
  $id_usuario = $_POST['id_usuario'];
  $id_rol = $_POST['id_rol'];
  $nuevo_estado = $_POST['modificar_estado'];

  $errores = [];

  // Verifica si se seleccionó un rol
  if ($id_rol == '') {
      $errores[] = "Debe seleccionar un rol.";
  }

  // Verifica si se seleccionó un estado
  if ($nuevo_estado == '') {
      $errores[] = "Debe seleccionar un estado.";
  }

  if (empty($errores)) {
      $sql = "UPDATE usuarios SET id_rol = $id_rol, activo = '$nuevo_estado' WHERE id_usuario = $id_usuario";
      if ($conexion->query($sql)) {
          echo "<div class='alert alert-success'>Rol y estado actualizados correctamente.</div>";
      } else {
          echo "<div class='alert alert-danger'>Error al actualizar: " . $conexion->error . "</div>";
      }
  } else {
      foreach ($errores as $error) {
          echo "<div class='alert alert-danger'>$error</div>";
      }
  }
}

// Obtener lista de usuarios con sus roles y estado activo
$sql = "SELECT u.id_usuario, u.nombre, r.nombre_rol, u.activo 
      FROM usuarios u 
      LEFT JOIN roles r ON u.id_rol = r.id_rol";
$resultado = $conexion->query($sql);

// Obtener lista de roles disponibles
$sql_roles = "SELECT * FROM roles";
$roles_result = $conexion->query($sql_roles);

// Preparar datos de usuarios para JavaScript
$usuarios = [];
$resultado->data_seek(0);
while ($row = $resultado->fetch_assoc()) {
  $usuarios[] = $row;
}
// Desactivar usuario (cambiar estado a 'NO')
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario_eliminar'])) {
  $id_usuario = intval($_POST['id_usuario_eliminar']);

  $sql = "UPDATE usuarios SET activo = 'NO' WHERE id_usuario = $id_usuario";
  if ($conexion->query($sql)) {
      echo "<div class='alert alert-success'>Usuario desactivado correctamente.</div>";
  } else {
      echo "<div class='alert alert-danger'>Error al desactivar el usuario: " . $conexion->error . "</div>";
  }
}

// Buscar por nombre de equipo si se ha enviado desde el formulario
$busqueda = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';

// Consultar equipos entregados
$sqlEntregados = "SELECT * FROM estado_entregas WHERE entregado = 'Sí'";
if ($busqueda !== '') {
    $sqlEntregados .= " AND nombre_equipo LIKE '%$busqueda%'";
}
$resultadoEntregados = $conexion->query($sqlEntregados);

// Consultar equipos pendientes
$sqlPendientes = "SELECT * FROM estado_entregas WHERE entregado = 'No'";
if ($busqueda !== '') {
    $sqlPendientes .= " AND nombre_equipo LIKE '%$busqueda%'";
}
$resultadoPendientes = $conexion->query($sqlPendientes);

// Procesar actualización de estado si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_movimiento'])) {
    $id = $_POST['id_movimiento'];
    $nuevoEstado = $conexion->real_escape_string($_POST['nuevo_estado']);
    $observacion = $conexion->real_escape_string($_POST['observacion']);

    $conexion->query("UPDATE estado_entregas SET entregado='Sí', observacion='$observacion', fecha_actualizacion=NOW() WHERE id_movimiento=$id");

    // Actualizar fecha_entrega en tabla movimientos
    $conexion->query("UPDATE movimientos SET fecha_entrega=NOW() WHERE id_movimiento=$id");

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
/* From Uiverse.io by Shoh2008 */ 
.container {
  display: flex;
  align-items: center;
  justify-content: center;
  position:relative;
  border-radius: 10px;
}

.card {
  position: relative;
  background: #333;
  width: auto;
  height: auto;
  border-radius: 10px;
  padding: 2rem;
  color: #aaa;
  box-shadow: 0 .25rem .25rem rgba(0,0,0,0.2),
    0 0 1rem rgba(0,0,0,0.2);
  overflow: hidden;
}

.card__image-container {
  margin: -2rem -2rem 1rem -2rem;
}

.card__line {
  opacity: 0;
  animation: LineFadeIn .8s .8s forwards ease-in;
}

.card__image {
  opacity: 0;
  animation: ImageFadeIn .8s 1.4s forwards;
}
.card__svg{
    position:absolute;
}
.card__title {
  color: white;
  margin-top: 35px;
  margin-bottom: 10px;
  font-weight: 800;
  letter-spacing: 0.01em;
}

.card__content {
  margin-top: -1rem;
  opacity: 0;
  animation: ContentFadeIn .8s 1.6s forwards;
}

@keyframes LineFadeIn {
  0% {
    opacity: 0;
    d: path("M 0 300 Q 0 300 0 300 Q 0 300 0 300 C 0 300 0 300 0 300 Q 0 300 0 300 ");
    stroke: #fff;
  }

  50% {
    opacity: 1;
    d: path("M 0 300 Q 50 300 100 300 Q 250 300 350 300 C 350 300 500 300 650 300 Q 750 300 800 300");
    stroke: #888BFF;
  }

  100% {
    opacity: 1;
    d: path("M -2 100 Q 50 200 100 250 Q 250 400 350 300 C 400 250 550 150 650 300 Q 750 450 802 400");
    stroke: #545581;
  }
}

@keyframes ContentFadeIn {
  0% {
    transform: translateY(-1rem);
    opacity: 0;
  }

  100% {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes ImageFadeIn {
  0% {
    transform: translate(-.5rem, -.5rem) scale(1.05);
    opacity: 0;
    filter: blur(2px);
  }

  50% {
    opacity: 1;
    filter: blur(2px);
  }

  100% {
    transform: translateY(0) scale(1.0);
    opacity: 1;
    filter: blur(0);
  }
}
/*Section input*/
.search-field {
  position: relative;
  width: 100%;
  height: 100%;
  left: -5px;
  border: 0;
  border-color:white;
}

.input {
  width: calc(100% - 29px);
  height: 100%;
  border: 0;
  border-color: transparent;
  font-size: 1rem;
  padding-right: 0px;
  color: var(--input-line);
  background: var(--input-bg-color);
  border-right: 2px solid var(--input-border-color);
  outline: none;
}

.input::-webkit-input-placeholder {
  color: var(--input-text-color);
}

.input::-moz-input-placeholder {
  color: var(--input-text-color);
}

.input::-ms-input-placeholder {
  color: var(--input-text-color);
}

.input:focus::-webkit-input-placeholder {
  color: var(--input-text-hover-color);
}

.input:focus::-moz-input-placeholder {
  color: var(--input-text-hover-color);
}

.input:focus::-ms-input-placeholder {
  color: var(--input-text-hover-color);
}

/*Search button*/
.search-box-icon {
  width: 52px;
  height: 35px;
  position: absolute;
  top: -6px;
  right: -21px;
  background: transparent;
  border-bottom-right-radius: var(--border-radius);
  border-top-right-radius: var(--border-radius);
  transition: var(--transition-cubic-bezier);
}

.search-box-icon:hover {
  background: var(--input-border-color);
}

.btn-icon-content {
  width: 52px;
  height: 35px;
  top: -6px;
  right: -21px;
  background: transparent;
  border: none;
  cursor: pointer;
  border-bottom-right-radius: var(--border-radius);
  border-top-right-radius: var(--border-radius);
  transition: var(--transition-cubic-bezier);
  opacity: .4;
}

.btn-icon-content:hover {
  opacity: .8;
}

.search-icon {
  width: 21px;
  height: 21px;
  position: absolute;
  top: 7px;
  right: 15px;
}
    </style>
</head>
<body>
    <div class="contenidoHisto" style="width:50%; height:100%; top:100px;">
<!-- From Uiverse.io by Shoh2008 -->
<center><em class="text-center text-white" style="color:white; position:relative; width:100%; color:black; font-size:30px;">Historial de Movimientos</em></center> 
<div class="container" >
  <div class="card">
     <div class="card__image-container">
    </div>
      
      <svg class="card__svg" viewBox="0 0 800 500">

        <path d="M 0 100 Q 50 200 100 250 Q 250 400 350 300 C 400 250 550 150 650 300 Q 750 450 800 400 L 800 500 L 0 500" stroke="transparent" fill="#333"></path>
        <path class="card__line" d="M 0 100 Q 50 200 100 250 Q 250 400 350 300 C 400 250 550 150 650 300 Q 750 450 800 400" stroke="pink" stroke-width="3" fill="transparent"></path>
      </svg>
    
     <div class="card__content">
     <form method="get" >
<!-- From Uiverse.io by Li-Deheng --> 
<div class="search" style="position:relative; ">
  <div class="search-box">
    <div class="search-field">
      <input placeholder="Search..." class="input" type="text" name="buscar" value="<?= htmlspecialchars($busqueda) ?>">
      <div class="search-box-icon" type="subtmit">
        <button class="btn-icon-content">
          <i class="search-icon" >
            <svg xmlns="://www.w3.org/2000/svg" version="1.1" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" fill="#fff"></path></svg>
          </i>
        </button>
      </div>
    </div>
  </div>
</div>
</form>
       

       <div class="contenedor" style=" position: relative;  ">

<style>
  .contenedor_de_panels{
   position:relative;
    width: 100%;
    height: 100%;
    display:flex; 
    overflow-x: hidden; 
    overflow-y: scroll; 
    scrollbar-width: none; 
    -ms-overflow-style: none; 

  }
</style>
<div class="contenedor_de_panels" style="display:flex;  justify-content: center; margin-top:10px;">
    <div class="panel" style=" margin:0 40px; ">
        <em>Equipos Entregados ✅</em>
        <?php if ($resultadoEntregados->num_rows > 0): ?>
            <?php while($fila = $resultadoEntregados->fetch_assoc()): ?>
                <div class="equipo">
                    <strong>Equipo:</strong> <?= htmlspecialchars($fila['nombre_equipo']) ?><br>
                    <strong>ID Movimiento:</strong> <?= $fila['id_movimiento'] ?><br>
                    <strong>Fecha Entrega:</strong> <?= $fila['fecha_actualizacion'] ?><br>
                    <strong>Observación:</strong> <?= $fila['observacion'] ?: 'Sin observaciones' ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay equipos entregados.</p>
        <?php endif; ?>
    </div>

    <div class="panel">
        <em>Equipos Pendientes ⏳</em>
        <?php if ($resultadoPendientes->num_rows > 0): ?>
            <?php while($fila = $resultadoPendientes->fetch_assoc()): ?>
                <div class="equipo">
                    <strong>Equipo:</strong> <?= htmlspecialchars($fila['nombre_equipo']) ?><br>
                    <strong>ID Movimiento:</strong> <?= $fila['id_movimiento'] ?><br>
                    <strong>Última Actualización:</strong> <?= $fila['fecha_actualizacion'] ?><br>
                    <strong>Observación:</strong> <?= $fila['observacion'] ?: 'Sin observaciones' ?><br>

                    <form method="post">
                        <input type="hidden" name="id_movimiento" value="<?= $fila['id_movimiento'] ?>">
                        <label>Estado:</label>
                        <select name="nuevo_estado" required>
                            <option value="Sí">Entregado</option>
                        </select><br>
                        <label>Descripción:</label>
                        <input type="text" name="observacion" placeholder="Ingrese descripción" required><br>
                        <button type="submit">Marcar como entregado</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Todos los equipos han sido entregados 🎉</p>
        <?php endif; ?>
    </div>
</div>
     <p>Soluta dolor praesentium at quod autem omnis, amet earum nesciunt porro.</p>
    </div>
  </div>
</div>
</div>
</div>
</body>
</html>