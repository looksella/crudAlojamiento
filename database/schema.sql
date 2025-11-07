-- Crear base de datos
CREATE DATABASE IF NOT EXISTS alojamientos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE alojamientos_db;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de alojamientos
CREATE TABLE alojamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    ubicacion VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    capacidad INT NOT NULL,
    habitaciones INT NOT NULL,
    banos INT NOT NULL,
    imagen VARCHAR(255),
    wifi BOOLEAN DEFAULT FALSE,
    estacionamiento BOOLEAN DEFAULT FALSE,
    piscina BOOLEAN DEFAULT FALSE,
    disponible BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_disponible (disponible),
    INDEX idx_precio (precio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla relación usuarios-alojamientos
CREATE TABLE usuarios_alojamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_alojamiento INT NOT NULL,
    fecha_seleccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_alojamiento) REFERENCES alojamientos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seleccion (id_usuario, id_alojamiento),
    INDEX idx_usuario (id_usuario),
    INDEX idx_alojamiento (id_alojamiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar usuario administrador
-- Contraseña: admin123
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@alojamientos.com', '$2y$12$2nrP1hv/iugXJKOqZR6xlOHlt3y5s6o8p0161gC099YDyV2p.Mvs2', 'admin');

-- Insertar alojamientos de ejemplo
INSERT INTO alojamientos (nombre, descripcion, ubicacion, precio, capacidad, habitaciones, banos, imagen, wifi, estacionamiento, piscina) VALUES
('Casa Colonial en el Centro Histórico', 'Hermosa casa colonial restaurada en el corazón de San Salvador. Arquitectura tradicional con todas las comodidades modernas.', 'San Salvador, El Salvador', 75.00, 4, 2, 1, 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800', 1, 1, 0),
('Villa Frente al Mar con Piscina', 'Espectacular villa con acceso directo a la playa de El Tunco. Piscina infinity con vista al océano Pacífico.', 'El Tunco, La Libertad', 180.00, 8, 4, 3, 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800', 1, 1, 1),
('Cabaña Eco-turística en la Montaña', 'Acogedora cabaña rodeada de naturaleza en las montañas de Apaneca. Perfecta para desconectar y respirar aire puro.', 'Apaneca, Ahuachapán', 55.00, 3, 1, 1, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800', 1, 1, 0),
('Apartamento Moderno en Santa Elena', 'Moderno apartamento en zona exclusiva de Antiguo Cuscatlán. Cerca de restaurantes, centros comerciales y todo lo que necesitas.', 'Antiguo Cuscatlán, La Libertad', 90.00, 4, 2, 2, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800', 1, 1, 0),
('Casa de Playa en El Sunzal', 'Casa familiar a pocos pasos de la playa de El Sunzal. Ideal para surfistas y amantes del mar con vista espectacular.', 'El Sunzal, La Libertad', 110.00, 6, 3, 2, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800', 1, 1, 0),
('Loft Contemporáneo en Escalón', 'Elegante loft en la Colonia Escalón con diseño minimalista. Perfecto para viajeros de negocios o parejas.', 'Colonia Escalón, San Salvador', 85.00, 2, 1, 1, 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?w=800', 1, 1, 1),
('Finca Cafetalera con Vista al Lago', 'Hermosa finca en las alturas de Coatepeque con vista panorámica al lago. Incluye recorrido por plantación de café.', 'Lago de Coatepeque, Santa Ana', 120.00, 5, 2, 2, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800', 1, 1, 1),
('Cabaña Rústica en Ruta de las Flores', 'Encantadora cabaña de madera en Juayúa, corazón de la Ruta de las Flores. Perfecta para explorar pueblos pintorescos.', 'Juayúa, Sonsonate', 65.00, 4, 2, 1, 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800', 1, 1, 0);