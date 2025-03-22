<?php
include 'conexion.php';

$sql = "SELECT p.id, p.tema, p.contenido, u.nombre, u.imagen,
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'like') AS likes,
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'dislike') AS dislikes
        FROM publicaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convertir la imagen a base64
        $row['imagen'] = base64_encode($row['imagen']);
        $posts[] = $row;
    }
}

echo json_encode($posts);
$conn->close();
?>
