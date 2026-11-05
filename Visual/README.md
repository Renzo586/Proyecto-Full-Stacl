## Sistema de Gestión Hospitalaria - Hospital de Clínicas

Sistema web con dos módulos:
 Documentación de pacientes: alta, listado, edición y baja de documentos, con subida física de archivos, categorías y acceso móvil por código QR.
 Ambulancias: gestión completa de traslados (Pendiente, En curso, Finalizado, Cancelado) con historial filtrable.

Incluye encuestas de satisfacción anónimas.

## Tecnologías
PHP, MySQL/MariaDB, HTML5, CSS3, JavaScript, GitHub, XAMPP.

## Instalación paso a paso
1. Instalar XAMPP e iniciar Apache y MySQL.
2. Copiar la carpeta del proyecto dentro de `C:\xampp\htdocs\`.
3. Abrir `http://localhost/phpmyadmin`.
4. En la pestaña SQL, ejecutar primero `bdd/schema.sql` y después `bdd/data.sql`.
5. Verificar que exista la carpeta `uploads/documentos/` con su archivo `.htaccess`.
6. Abrir `http://localhost/Proyecto-Full-Stacl/Visual/login.html`.

Si MySQL tiene otra contraseña, se configura en `datos/conexion.php` (usuario `root`, sin contraseña por defecto).

## Credenciales de prueba
| Rol | Usuario | Contraseña |
| Administrador | papoi67 | (la definida en data.sql) |
| Operador | operador1 | (la definida en data.sql) |

## Estructura (3 capas)
- `Visual/` y `JS/`: presentación (HTML, CSS, JavaScript).
- `negocio/`: lógica (validaciones, sesión, roles).
- `datos/` y `bdd/`: acceso a datos (clases PDO) y scripts SQL.

## Seguridad
Contraseñas con `password_hash()` / `password_verify()`, consultas preparadas PDO, sesiones PHP con control de rol en cada endpoint, validación en PHP y JavaScript, y carpeta de archivos bloqueada con `.htaccess`.

## Uso desde un celular (QR)
Abrir el sistema con la IP de la PC para que el QR funcione desde otros dispositivos de la misma red.

## Integrantes
- Renzo Bianchi(coordinador) Benjamin Abreu(Co-coordiandor) Isaías Gomez(Integrante)