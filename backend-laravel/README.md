# 📘 API Laravel - Gestión de Usuarios y Artículos

## ✨ Descripción

Este proyecto es una API RESTful desarrollada en Laravel que permite:

- Registro y autenticación de usuarios con JWT.
- Gestión de perfil de usuario y avatar.
- Creación, actualización y eliminación de artículos.
- Subida de imágenes de artículo.
- Búsqueda de artículos.

## 💠 Tecnologías

- **Laravel** (Framework backend)
- **JWT Authentication**
- **MySQL** (o el motor que configures)
- **Tailwind CSS** (opcional en frontend)
- **Postman** (para testeo)

---

## 🔐 Autenticación

Se utiliza **JWT (JSON Web Token)**.\
Para acceder a rutas protegidas es obligatorio enviar el header:

```
Authorization: Bearer TU_TOKEN
```

Puedes obtener el token en `/api/user/login`.

---

## 📈 Endpoints

### 👤 Usuarios

#### 🔹 Registro

```
POST /api/user/register
```

**Body:**

```json
{
  "name": "Juan",
  "surname": "Pérez",
  "nick": "juanpe",
  "email": "juan@mail.com",
  "password": "123456"
}
```

#### 🔹 Login

```
POST /api/user/login
```

**Body:**

```json
{
  "email": "juan@mail.com",
  "password": "123456"
}
```

**Response:**

```json
{
  "token": "JWT_TOKEN_GENERADO"
}
```

#### 🔹 Obtener perfil por ID

```
GET /api/user/profile/{id}
```

#### 🔹 Obtener avatar

```
GET /api/user/avatar/{filename}
```

#### 🔹 Actualizar perfil (Requiere token)

```
PUT /api/user/update
```

**Headers:**

```
Authorization: Bearer TU_TOKEN
```

**Body:**

```json
{
  "name": "Nuevo Nombre",
  "email": "nuevo@mail.com"
}
```

#### 🔹 Subir avatar (Requiere token)

```
POST /api/user/upload
```

**Headers:**

```
Authorization: Bearer TU_TOKEN
Content-Type: multipart/form-data
```

**Form Data:**

```
file0: avatar.jpg
```

---

### 📰 Artículos

#### 🔹 Crear artículo (Requiere token)

```
POST /api/article/save
```

**Body:**

```json
{
  "title": "Mi primer post",
  "content": "Este es el contenido del artículo",
  "user_id": 1
}
```

#### 🔹 Subir imagen a artículo (Requiere token)

```
POST /api/article/upload/{id}
```

**Form Data:**

```
file0: imagen.jpg
```

#### 🔹 Listar artículos paginados

```
GET /api/article/items/{page}
```

#### 🔹 Obtener artículo por ID

```
GET /api/article/item/{id}
```

#### 🔹 Buscar artículos por palabra

```
GET /api/article/search/{searchString}
```

#### 🔹 Obtener artículos de un usuario

```
GET /api/article/user/{user_id}
```

#### 🔹 Obtener imagen del artículo

```
GET /api/article/poster/{filename}
```

#### 🔹 Actualizar artículo (Requiere token)

```
PUT /api/article/update/{id}
```

**Body:**

```json
{
  "title": "Nuevo título",
  "content": "Nuevo contenido"
}
```

#### 🔹 Eliminar artículo (Requiere token)

```
DELETE /api/article/delete/{id}
```

---

## ⚡ Instalación

1. Clona el repositorio.
2. Configura tu `.env` con datos de base de datos y JWT.
3. Ejecuta migraciones:

```
php artisan migrate
```

4. Publica configuración de JWT:

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

5. Genera la clave:

```
php artisan jwt:secret
```

6. Inicia el servidor:

```
php artisan serve
```

---

## 🔪 Testeo

Puedes usar **Postman** o **Insomnia** para probar cada endpoint.\
Recuerda que las rutas protegidas requieren enviar el token en el encabezado.

---

## 📝 Licencia

Este proyecto está bajo licencia MIT.

