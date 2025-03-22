// Cargar publicaciones en tiempo real
function cargarPublicaciones() {
    fetch('../backend/obtener_posts.php')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Verificar si los datos son correctos
            let postsHtml = '';
            data.forEach(post => {
                const imagenHtml = post.imagen
                    ? `<img src="${post.imagen}" alt="Usuario" class="user-img">`
                    : `<img src="../assets/default-user.png" alt="Usuario" class="user-img">`;

                postsHtml += `
                    <div class="post">
                        <div class="post-header">
                            ${imagenHtml}
                            <h3>${post.tema} - <small>${post.nombre}</small></h3>
                            <small>${post.fecha_publicacion}</small>
                        </div>
                        <p>${post.contenido}</p>
                        <button onclick="reaccionar(${post.id}, 'like')">Like (${post.likes})</button>
                        <button onclick="reaccionar(${post.id}, 'dislike')">Dislike (${post.dislikes})</button>
                    </div>
                `;
            });
            document.getElementById('postsContainer').innerHTML = postsHtml;
        })
        .catch(error => {
            console.error("Error al cargar publicaciones:", error); // Manejar errores en la carga
        });
}

// Crear nueva publicación
document.getElementById('formPost').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../backend/crear_post.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.success) {
            cargarPublicaciones(); // Actualizar en tiempo real
        } else {
            alert(data.message);
        }
    });
});

// Reaccionar a una publicación
function reaccionar(publicacion_id, tipo) {
    fetch('../backend/reaccionar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `publicacion_id=${publicacion_id}&tipo=${tipo}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarPublicaciones(); // Actualizar en tiempo real
            } else {
                alert(data.message);
            }
        });
}

// Actualizar publicaciones cada 5 segundos
setInterval(cargarPublicaciones, 5000);
cargarPublicaciones();
