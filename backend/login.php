<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        header("Location: foro.php");
    } else {
        echo "Nombre o contraseña incorrectos";
    }
}
?>
