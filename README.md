# 🛒 Sistema de Gestión de Órdenes de Venta

Este proyecto implementa la arquitectura básica de un sistema de ecommerce para la gestión de órdenes de venta. Utiliza Laravel como framework principal y MySQL como motor de base de datos.

---

## 🚀 Objetivo

Desarrollar una API RESTful que permita crear y consultar órdenes de venta, almacenando datos del comprador, domicilio de entrega, datos de facturación y productos involucrados.

---

## 📌 Tareas Realizadas

- 📦 Estructura de modelos y migraciones
- 🔄 API para crear y ver detalles de órdenes
- 🧾 Validación con Form Requests
- 🔐 Autenticación con Sanctum
- 🔑 Autorización mediante Policies
- ⚙️ Lógica desacoplada en Services
- 📄 Documentación con Swagger (OpenAPI)
- 🧵 Envío de emails vía Jobs
- 🧠 Uso de Cache en endpoints críticos
- 🧪 Tests unitarios e integrales

---

## ✅ Requisitos

- PHP 8.2+
- Composer
- Laravel 12
- MySQL 8.x

---

## ⚙️ Pasos para Desplegar

1. Clonar el repositorio:  
   ```bash
   git clone https://github.com/Apitarque/challenge_zipnova.git nombre-del-proyecto
   cd nombre-del-proyecto
   ```

2. Instalar dependencias:
   ```bash
   composer install
   ```

3. Crear archivo de entorno:
   ```bash
   cp .env.example .env
   ```

4. Configurar base de datos:  
   Crear una base de datos MySQL con el nombre: `zipnova_ecommerce`  
   Editar el archivo `.env` con las credenciales correspondientes.

5. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Ejecutar pruebas:
   ```bash
   php artisan test
   ```

7. Iniciar el servidor:
   ```bash
   php artisan serve
   ```

8. Generar un token de acceso con Sanctum:
   ```bash
   php artisan tinker
   >>> App\Models\User::find(1)->createToken('api-token')->plainTextToken;
   ```

9. Acceder a la documentación:
   Navegar a [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)  
   Click en **Authorize** y pegar el token generado.

10. ¡Probar la API desde Swagger o Postman! 🎉

---

## 🧩 Detalles Técnicos

- **🔐 Autenticación:**  
  Laravel Sanctum para autenticación vía token Bearer.

- **📜 Autorización:**  
  Policies para restringir el acceso solo a órdenes del usuario autenticado.

- **🧾 Validaciones:**  
  Uso de clases `FormRequest` para mantener limpias las validaciones.

- **🧠 Servicios:**  
  Se utiliza la carpeta `Services` para encapsular la lógica de negocio.

- **🛠 Excepciones:**  
  Manejo global de errores desde `bootstrap/app.php` para respuestas JSON uniformes.

- **🧪 Tests:**  
  Se incluyen pruebas unitarias y de integración con seeders y factories.

- **🧵 Jobs:**  
  Envío de email de confirmación cuando se crea una orden.

- **🧰 Caching:**  
  Implementación de `Cache::remember()` para reducir carga de la base de datos.

- **📄 Swagger:**  
  Documentación completa de los endpoints con anotaciones OpenAPI.

---

## 🏁 ¡Aguardo comentarios!
