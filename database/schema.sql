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
-- Contraseña: admin123 (encriptada con password_hash)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@alojamientos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar alojamientos de ejemplo
INSERT INTO alojamientos (nombre, descripcion, ubicacion, precio, capacidad, habitaciones, banos, imagen, wifi, estacionamiento, piscina) VALUES
('Apartamento Acogedor en el Centro', 'Hermoso apartamento de 2 habitaciones en el corazón de la ciudad. Perfecto para familias o grupos pequeños.', 'Madrid, España', 85.00, 4, 2, 1, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800', 1, 0, 0),
('Villa de Lujo con Piscina', 'Espectacular villa con piscina privada y jardín. Ideal para vacaciones de lujo con vistas al mar.', 'Marbella, España', 250.00, 8, 4, 3, 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800', 1, 1, 1),
('Estudio Moderno con WiFi', 'Estudio moderno y funcional con todas las comodidades. Perfecto para parejas o viajeros solitarios.', 'Barcelona, España', 55.00, 2, 1, 1, 'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d?w=800', 1, 0, 0),
('Casa Rural con Encanto', 'Acogedora casa rural en la sierra. Perfecta para desconectar y disfrutar de la naturaleza.', 'Sierra de Gredos, España', 120.00, 6, 3, 2, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800', 1, 1, 0),
('Penthouse con Terraza', 'Exclusivo penthouse con terraza privada y vistas panorámicas de la ciudad.', 'Valencia, España', 180.00, 4, 2, 2, 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800', 1, 1, 1),
('Apartamento Familiar cerca de la Playa', 'Espacioso apartamento a pocos metros de la playa. Perfecto para familias con niños.', 'Alicante, España', 95.00, 5, 2, 1, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800', 1, 1, 0),
('Loft Industrial en el Casco Antiguo', 'Loft con estilo industrial en el casco antiguo. Techos altos y diseño único.', 'Sevilla, España', 110.00, 3, 1, 1, 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800', 1, 0, 0),
('Chalet con Jardín Privado', 'Hermoso chalet con jardín privado y barbacoa. Perfecto para reuniones familiares.', 'San Sebastián, España', 160.00, 7, 3, 2, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800', 1, 1, 0);