<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Documentación de la API</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 text-lg">

  <div class="max-w-5xl mx-auto py-12 px-6">
    <h1 class="text-5xl font-bold text-indigo-700 mb-10 text-center">📘Documentación API LARAVEL</h1>

    <!-- Usuarios -->
    <section class="mb-14">
      <h2 class="text-3xl font-semibold mb-6">👤 Usuarios</h2>

      <!-- Registro -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">1) POST /api/user/register</h3>
        <p class="mb-2 text-gray-700">Registrar nuevo usuario.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            POST /api/user/register
            Content-Type: application/json

            {
            "name": "Juan",
            "surname": "Pérez",
            "nick": "juanpe",
            "email": "juan@mail.com",
            "password": "123456"
            }
        </pre>
      </div>

      <!-- Login -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">2) POST /api/user/login</h3>
        <p class="mb-2 text-gray-700">Login y obtención del token JWT.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            POST /api/user/login
            Content-Type: application/json

            {
            "email": "juan@mail.com",
            "password": "123456"
            }
        </pre>
      </div>

      <!-- profile -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">3) GET /user/profile/{id}</h3>
        <p class="mb-2 text-gray-700">Con el ID busca el usuario.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/user/profile/1
        </pre>
      </div>

      <!-- avatar -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">4) GET /user/avatar/{file}</h3>
        <p class="mb-2 text-gray-700">Con el nombre del avatar busca el archivo.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/user/avatar/1753976748iconoPerfil.png
        </pre>
      </div>

      <!-- Actualizar perfil -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">5) PUT /api/user/update</h3>
        <p class="mb-2 text-gray-700">Actualizar datos del usuario autenticado.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            PUT /api/user/update
            Authorization: Bearer TU_TOKEN
            Content-Type: application/json

            {
            "name": "Juan Actualizado",
            "email": "nuevo@mail.com"
            }
        </pre>
      </div>

      <!-- Subir avatar -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-indigo-700">6) POST /api/user/upload</h3>
        <p class="mb-2 text-gray-700">Subir imagen de perfil (campo: file0).</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            POST /api/user/upload
            Authorization: Bearer TU_TOKEN
            Content-Type: multipart/form-data

            file0: avatar.jpg
        </pre>
      </div>
    </section>

    <!-- Artículos -->
    <section>
      <h2 class="text-3xl font-semibold mb-6">📰 Artículos</h2>

      <!-- Crear artículo -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">1) POST /api/article/save</h3>
        <p class="mb-2 text-gray-700">Crear nuevo artículo.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            POST /api/article/save
            Authorization: Bearer TU_TOKEN
            Content-Type: application/json

            {
            "title": "Mi primer post",
            "content": "Este es el contenido del artículo",
            "user_id": 1
            }
        </pre>
      </div>

      <!-- Subir imagen -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">2) POST /api/article/upload/{id}</h3>
        <p class="mb-2 text-gray-700">Subir imagen a un artículo existente.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            POST /api/article/upload/1
            Authorization: Bearer TU_TOKEN
            Content-Type: multipart/form-data

            file0: imagen.jpg
        </pre>
      </div>

      <!-- Listar artículos -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">3) GET /api/article/items/{page}</h3>
        <p class="mb-2 text-gray-700">Obtener lista paginada de artículos.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/article/items/1
        </pre>
      </div>

      <!-- Un artículo -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">4) GET /api/article/item/{id}</h3>
        <p class="mb-2 text-gray-700">Obtener un artículo.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/article/item/1
        </pre>
      </div>

      <!-- Buscar por palabra -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">5) GET /api/article/search/{searchString}</h3>
        <p class="mb-2 text-gray-700">Buscar artículos por título o contenido.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/article/search/javascript
        </pre>
      </div>

      <!-- Buscar por usuario -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">6) GET /api/article/user/{user_id}</h3>
        <p class="mb-2 text-gray-700">Buscar artículos por usuario.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/article/user/1
        </pre>
      </div>

      <!-- Obtener imagen -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">7) GET /api/article/poster/{file}</h3>
        <p class="mb-2 text-gray-700">Busca un poster de un artículo.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            GET /api/article/poster/1753976748iconoPerfil.png
        </pre>
      </div>

    <!-- Actualizar articulo foto -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">8) PUT /api/article/update</h3>
        <p class="mb-2 text-gray-700">Actualizar datos del articulo autenticado.</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            PUT /api/article/update
            Authorization: Bearer TU_TOKEN
            Content-Type: application/json

            {
                "title": "Mi primer post nuevo",
                "content": "Este es el contenido del artículo nuevo",
            }
        </pre>
      </div>

      <!-- Eliminar artículo -->
      <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold text-yellow-700">9) DELETE /api/article/delete/{id}</h3>
        <p class="mb-2 text-gray-700">Eliminar artículo (requiere token).</p>
        <pre class="bg-gray-100 p-4 rounded text-base overflow-auto">
            DELETE /api/article/delete/3
            Authorization: Bearer TU_TOKEN
        </pre>
      </div>
    </section>

    <div class="text-center text-base text-gray-500 mt-12">
      ⚙️ API protegida con JWT. Se utilizó <strong>Postman</strong> para testear todos los endpoints.
    </div>

  </div>
</body>
</html>
