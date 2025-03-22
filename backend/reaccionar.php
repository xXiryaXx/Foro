<?php
include 'conexion.php';
session_start();

$usuario_id = $_SESSION['usuario_id'];
$publicacion_id = $_POST['publicacion_id'];
$tipo_reaccion = $_POST['tipo'];

// Verificar si el usuario ya reaccionó a esta publicación
$sql = "SELECT id, tipo_reaccion FROM reacciones WHERE usuario_id = ? AND publicacion_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $publicacion_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Si no existe reacción, insertar una nueva
    $insert_sql = "INSERT INTO reacciones (usuario_id, publicacion_id, tipo_reaccion) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $usuario_id, $publicacion_id, $tipo_reaccion);
    $insert_stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Reacción registrada']);
} else {
    // Obtener la reacción existente
    $row = $result->fetch_assoc();
    if ($row['tipo_reaccion'] !== $tipo_reaccion) {
        // Si el usuario cambió de reacción, actualizarla
        $update_sql = "UPDATE reacciones SET tipo_reaccion = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $tipo_reaccion, $row['id']);
        $update_stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Reacción actualizada']);
    } else {
        // Si ya reaccionó con el mismo tipo, evitar duplicados
        echo json_encode(['success' => false, 'message' => 'Ya reaccionaste con esta opción']);
    }
}

$stmt->close();
$conn->close();
?>
