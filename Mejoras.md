# Registro de Mejoras y Cambios

Este documento detalla las modificaciones realizadas al proyecto el 10 de Diciembre de 2025, incluyendo correcciones de documentación e implementación de nuevas funcionalidades.

## 1. Corrección de Documentación

### Archivo: `README.md`
**Ubicación:** Sección de Instalación / Configuración del servidor web.
**Cambio:** Corrección de error de numeración en las opciones de instalación. Había dos "Opción B".
**Código Modificado:**
```markdown
#### Opción C: Apache/Nginx (producción)
```
**Explicación:** Se renombró la segunda "Opción B" a "Opción C" para mantener una secuencia lógica y evitar confusiones en la guía de instalación.

---

## 2. Actualización de Base de Datos

### Archivo: `database/schema.sql`
**Ubicación:** Final del archivo.
**Cambio:** Adición de la tabla `instructors` y datos semilla.
**Código Añadido:**
```sql
-- Tabla de Instructores
CREATE TABLE IF NOT EXISTS instructors (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    specialization VARCHAR(100),
    hire_date DATE NOT NULL DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo
INSERT INTO instructors (name, email, phone, specialization, hire_date) VALUES
    ('Ana Martínez', 'ana.martinez@gimnasio.com', '0987654321', 'Yoga', '2023-01-15'),
    ...
```
**Explicación:** Se definió la estructura de datos para almacenar la información de los instructores y se agregaron registros de ejemplo para facilitar pruebas inmediatas.

---

## 3. Implementación del Modelo (Backend)

### Archivo: `models/Instructor.php`
**Ubicación:** Nuevo archivo.
**Cambio:** Creación de la clase `Instructor`.
**Funcionalidad:**
- Implementa el patrón Singleton para la conexión a DB.
- Métodos CRUD completos: `getAll()`, `getById($id)`, `create($data)`, `update($id, $data)`, `delete($id)`.
- Método de utilidad: `emailExists($email)` para validaciones.
**Explicación:** Este archivo encapsula toda la lógica de acceso a datos referente a los instructores, siguiendo el principio de responsabilidad única.

---

## 4. Implementación del Controlador (Backend)

### Archivo: `controllers/InstructorController.php`
**Ubicación:** Nuevo archivo.
**Cambio:** Creación de la clase `InstructorController`.
**Funcionalidad:**
- Maneja las peticiones HTTP (`GET` y `POST`).
- Coordina entre el Modelo (`Instructor`) y las Vistas.
- Realiza validaciones de servidor (campos requeridos, formato de email, duplicados).
- Gestiona redirecciones y mensajes de éxito/error.
**Explicación:** Actúa como intermediario, procesando la entrada del usuario y devolviendo la respuesta adecuada. Se aseguró el uso de rutas absolutas (`/index.php`) para evitar errores de navegación.

---

## 5. Implementación de Vistas (Frontend)

### Archivo: `views/instructors/index.php`
**Ubicación:** Nuevo archivo.
**Cambio:** Vista de listado.
**Detalle:** Tabla HTML que itera sobre los instructores, mostrando sus datos y botones de acción (Editar/Eliminar).

### Archivo: `views/instructors/create.php`
**Ubicación:** Nuevo archivo.
**Cambio:** Formulario de creación.
**Detalle:** Formulario HTML con validación de errores visuales y persistencia de datos previos en caso de error.

### Archivo: `views/instructors/edit.php`
**Ubicación:** Nuevo archivo.
**Cambio:** Formulario de edición.
**Detalle:** Similar al de creación, pero precarga los datos existentes del instructor seleccionado.

---

## 6. Registro de Rutas

### Archivo: `public/index.php`
**Ubicación:** Array `$controllers`.
**Cambio:** Registro del nuevo controlador.
**Código Añadido:**
```php
$controllers = [
    'member' => 'MemberController',
    'class' => 'ClassController',
    'payment' => 'PaymentController',
    'instructor' => 'InstructorController' // <--- Nueva línea
];
```
**Explicación:** Permite que el "Front Controller" reconozca y despache peticiones dirigidas a `controller=instructor`.

---

## 7. Actualización de Navegación

### Archivo: `views/layouts/header.php`
**Ubicación:** Dentro de la etiqueta `<nav>`.
**Cambio:** Adición del enlace al menú principal.
**Código Añadido:**
```html
<a href="/index.php?controller=instructor&action=index" class="nav-link">Instructores</a>
```
**Explicación:** Hace accesible la nueva funcionalidad desde cualquier parte de la aplicación.

---

## 8. Estandarización de Rutas

### Archivos: Vistas y Controlador de Instructores
**Cambio:** Uso de rutas absolutas.
**Detalle:** Se modificaron todas las referencias de `index.php?` a `/index.php?`.
**Explicación:** Esto asegura que la navegación funcione correctamente independientemente de la profundidad del directorio o la configuración del servidor web, evitando errores 404 en redirecciones.

---

# Descripción General de la Nueva Funcionalidad

## Gestión de Instructores
Se ha implementado un módulo completo (CRUD) para la administración de instructores del gimnasio, integrado totalmente con la arquitectura MVC existente.

**Capacidades:**
1. **Listar:** Visualización tabular de todos los instructores con indicador de estado (Activo/Inactivo).
2. **Crear:** Formulario para registrar nuevos instructores con validaciones de:
   - Campos obligatorios (Nombre, Email, Fecha).
   - Formato de email válido.
   - Unicidad del email (evita duplicados en la BD).
3. **Editar:** Capacidad de modificar todos los datos de un instructor existente.
4. **Eliminar:** Opción para borrar instructores de la base de datos con confirmación de seguridad en JavaScript.

**Integración:**
La funcionalidad respeta la arquitectura Cliente-Servidor separando estrictamente la lógica de negocio (PHP/SQL) de la presentación (HTML/CSS), y utiliza los estilos globales del proyecto para mantener una coherencia visual.
