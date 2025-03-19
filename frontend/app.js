// Cargar publicaciones en tiempo real
function cargarPublicaciones() {
    fetch('../backend/obtener_posts.php')
        .then(response => response.json())
        .then(data => {
            let postsHtml = '';
            data.forEach(post => {
                postsHtml += `
                    <div class="post">
                        <div class="usuario">
                            <img src="data:image/jpeg;base64,${post.imagen}" alt="Imagen de ${post.nombre}" class="avatar" />
                            <h3>${post.tema} - <small>${post.nombre}</small></h3>
                        </div>
                        <p>${post.contenido}</p>
                        <button onclick="reaccionar(${post.id}, 'like')">Like(${post.likes})</button>
                        <button onclick="reaccionar(${post.id}, 'dislike')">Dislike(${post.dislikes})</button>
                    </div>
                `;
            });
            document.getElementById('postsContainer').innerHTML = postsHtml;
        });
}

// Actualizar publicaciones cada 5 segundos
setInterval(cargarPublicaciones, 5000);
cargarPublicaciones();
