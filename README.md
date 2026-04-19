# EventApp - Plataforma de Gestión de Eventos Locales

<p align="center">
  <img src="https://img.icons8.com/color/96/000000/calendar--v1.png" alt="EventApp Logo" width="80">
</p>

<p align="center">
  <strong>Plataforma para la creación y gestión de eventos y comunidades locales</strong>
</p>
<p align="center">
<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="">
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="">
<img src="https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E" alt="">
</p>

## Índice

- [Instalación](#instalación)
- [Sobre el Proyecto](#sobre-el-proyecto)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Arquitectura](#arquitectura)
- [Roles de Usuario](#roles-de-usuario)
- [Módulos del Sistema](#módulos-del-sistema)
- [Seguridad](#seguridad)


## Instalación

1. **Clonar el repositorio**

    ```bash
    git clone https://github.com/amaiagb/eventapp.git
    ```

2. **Instalar dependencias**

    ```bash
    cd eventapp
    composer install
    npm install
    ```

3. **Configurar el archivo `.env`**

    Duplica el archivo `.env.example` y renómbralo a `.env`. Luego, ajusta los siguientes parámetros:

    ```env
    DB_CONNECTION=mysql           # Tipo de conexión
    DB_HOST=127.0.0.1             # Dirección del host
    DB_PORT=3306                  # Puerto para la conexión MySQL
    DB_DATABASE=eventapp          # Nombre de la base de datos
    DB_USERNAME=eventapp_admin    # Usuario de la base de datos
    DB_PASSWORD=***               # Contraseña del usuario
    SESSION_DRIVER=cookie         # Método de manejo de sesiones
    CACHE_PREFIX=sync             # Prefijo para el cache
    ```

4. **Crear la base de datos y el usuario**

    Usando HeidiSQL, phpMyAdmin o una herramienta similar, crea una base de datos y usuario con los valores correspondientes al archivo `.env`.

5. **Generar la clave de la aplicación**

    ```bash
    php artisan key:generate
    ```

6. **Ejecutar las migraciones y seeders**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

7. **Compilar assets de frontend**

    ```bash
    npm run build
    ```

## Sobre el Proyecto

**EventApp** es una plataforma de gestión de eventos locales diseñada para facilitar la creación, descubrimiento y participación en actividades comunitarias. El sistema permite a los usuarios crear eventos, buscar actividades de su interés, inscribirse y recibir recordatorios, con un enfoque inclusivo para pequeñas comunidades y grupos diversos.

### Objetivos Principales

- **Facilitar la creación de eventos**: Permitir a los usuarios crear eventos con facilidad, incluyendo categorías, descripciones, imágenes y ubicación
- **Promover la visibilidad de eventos pequeños**: Ofrecer una plataforma donde eventos locales tengan mayor visibilidad
- **Gestión de inscripciones y aforo**: Sistema de inscripción con control de aforo para evitar sobreventa
- **Interacción entre usuarios**: Chat por evento, sistema de seguimiento y notificaciones
- **Moderación de contenidos**: Sistema de validación y aprobación de eventos por administradores

## Tecnologías Utilizadas

### Backend
- **Laravel 11**: Framework PHP principal
- **MySQL**: Sistema de gestión de base de datos
- **Eloquent ORM**: Mapeo objeto-relacional
- **Blade**: Motor de plantillas

### Frontend
- **Bootstrap**: Framework CSS utility-first
- **JavaScript ES6+**: Lógica del cliente
- **Vite**: Build tool para assets

### Desarrollo
- **Composer**: Gestión de dependencias PHP
- **Git**: Control de versiones

## Arquitectura

### Estructura del Proyecto
```
eventapp/
├── app/
│   ├── Http/Controllers/     # Controladores MVC
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   └── seeders/            # Datos iniciales
├── resources/
│   ├── views/              # Plantillas Blade
│   ├── css/                # Estilos CSS
│   └── js/                 # Archivos JavaScript
├── public/                 # Archivos públicos
└── routes/                 # Definición de rutas
```

### Patrones de Diseño
- **MVC**: Model-View-Controller
- **Repository Pattern**: Para acceso a datos
- **Service Layer**: Lógica de negocio
- **Middleware**: Autenticación y autorización

## Roles de Usuario

### Administrador
- Validación y aprobación de eventos
- Gestión de usuarios y moderación de contenidos
- Gestión de reportes y bloqueos

### Usuario Estándar
- Creación y edición de eventos propios
- Inscripción a eventos y control de aforo
- Participación en chats por evento
- Sistema de valoraciones y reseñas


---

**EventApp** - 2026  
