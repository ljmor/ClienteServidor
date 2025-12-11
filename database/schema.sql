-- Script de creación de base de datos para el sistema de Gimnasio
-- Este script crea las tablas en el SERVIDOR de base de datos PostgreSQL

-- Crear base de datos (ejecutar como superusuario)
-- CREATE DATABASE gimnasio_db;
-- \c gimnasio_db;

-- Tabla de Miembros
CREATE TABLE IF NOT EXISTS members (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    registration_date DATE NOT NULL DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'suspended')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Clases
CREATE TABLE IF NOT EXISTS classes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    instructor VARCHAR(100) NOT NULL,
    schedule_time TIME NOT NULL,
    schedule_days VARCHAR(50) NOT NULL, -- Ej: 'Lunes, Miércoles, Viernes'
    capacity INTEGER NOT NULL CHECK (capacity > 0),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Tipos de Membresía
CREATE TABLE IF NOT EXISTS membership_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    price DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
    duration_days INTEGER NOT NULL CHECK (duration_days > 0),
    description TEXT
);

-- Tabla de Pagos
CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    member_id INTEGER NOT NULL REFERENCES members(id) ON DELETE CASCADE,
    membership_type_id INTEGER NOT NULL REFERENCES membership_types(id),
    amount DECIMAL(10, 2) NOT NULL CHECK (amount >= 0),
    payment_date DATE NOT NULL DEFAULT CURRENT_DATE,
    payment_method VARCHAR(50) DEFAULT 'cash' CHECK (payment_method IN ('cash', 'card', 'transfer')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo para tipos de membresía
INSERT INTO membership_types (name, price, duration_days, description) VALUES
    ('Mensual', 50.00, 30, 'Membresía mensual estándar'),
    ('Trimestral', 135.00, 90, 'Membresía trimestral con descuento'),
    ('Anual', 500.00, 365, 'Membresía anual con máximo descuento')
ON CONFLICT (name) DO NOTHING;

-- Insertar datos de ejemplo
INSERT INTO members (name, email, phone, registration_date) VALUES
    ('Juan Pérez', 'juan.perez@email.com', '0987654321', '2024-01-15'),
    ('María García', 'maria.garcia@email.com', '0987654322', '2024-02-20'),
    ('Carlos López', 'carlos.lopez@email.com', '0987654323', '2024-03-10')
ON CONFLICT (email) DO NOTHING;

INSERT INTO classes (name, instructor, schedule_time, schedule_days, capacity, description) VALUES
    ('Yoga Matutino', 'Ana Martínez', '08:00:00', 'Lunes, Miércoles, Viernes', 20, 'Clase de yoga para principiantes'),
    ('CrossFit', 'Pedro Rodríguez', '18:00:00', 'Martes, Jueves, Sábado', 15, 'Entrenamiento de alta intensidad'),
    ('Pilates', 'Laura Sánchez', '10:00:00', 'Lunes, Miércoles, Viernes', 18, 'Clase de pilates para fortalecimiento')
ON CONFLICT DO NOTHING;

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
    ('Pedro Rodríguez', 'pedro.rodriguez@gimnasio.com', '0987654322', 'CrossFit', '2023-02-20'),
    ('Laura Sánchez', 'laura.sanchez@gimnasio.com', '0987654323', 'Pilates', '2023-03-10')
ON CONFLICT (email) DO NOTHING;

-- =====================================================
-- MÓDULO DE RUTINAS DE ENTRENAMIENTO
-- =====================================================

-- Tabla de Ejercicios (catálogo)
CREATE TABLE IF NOT EXISTS exercises (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    muscle_group VARCHAR(50) NOT NULL,
    equipment VARCHAR(255), -- Etiquetas separadas por coma (ej: 'mancuernas,barra,banco')
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Rutinas
CREATE TABLE IF NOT EXISTS routines (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    objective TEXT,
    difficulty VARCHAR(20) NOT NULL CHECK (difficulty IN ('facil', 'intermedio', 'avanzado')),
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Bloques de Rutina (días/semanas)
CREATE TABLE IF NOT EXISTS routine_blocks (
    id SERIAL PRIMARY KEY,
    routine_id INTEGER NOT NULL REFERENCES routines(id) ON DELETE CASCADE,
    block_name VARCHAR(50) NOT NULL, -- Ej: 'Día 1', 'Lunes', 'Semana 1'
    order_index INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Ejercicios de Rutina (relación bloque-ejercicio)
CREATE TABLE IF NOT EXISTS routine_exercises (
    id SERIAL PRIMARY KEY,
    block_id INTEGER NOT NULL REFERENCES routine_blocks(id) ON DELETE CASCADE,
    exercise_id INTEGER NOT NULL REFERENCES exercises(id) ON DELETE CASCADE,
    repetitions VARCHAR(50), -- Ej: '3x12', '4x10', '3x15-20'
    estimated_time INTEGER, -- Tiempo en minutos
    order_index INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar ejercicios de ejemplo
INSERT INTO exercises (name, description, muscle_group, equipment) VALUES
    ('Press de Banca', 'Ejercicio para pecho con barra', 'Pecho', 'barra,banco'),
    ('Sentadillas', 'Ejercicio compuesto para piernas', 'Piernas', 'barra,rack'),
    ('Peso Muerto', 'Ejercicio para espalda baja y piernas', 'Espalda', 'barra'),
    ('Dominadas', 'Ejercicio para espalda alta', 'Espalda', 'barra de dominadas'),
    ('Curl de Bíceps', 'Ejercicio de aislamiento para bíceps', 'Brazos', 'mancuernas'),
    ('Fondos en Paralelas', 'Ejercicio para tríceps y pecho', 'Brazos', 'paralelas'),
    ('Press Militar', 'Ejercicio para hombros', 'Hombros', 'barra,mancuernas'),
    ('Plancha', 'Ejercicio isométrico para core', 'Core', 'ninguno'),
    ('Zancadas', 'Ejercicio unilateral para piernas', 'Piernas', 'mancuernas'),
    ('Remo con Barra', 'Ejercicio para espalda media', 'Espalda', 'barra')
ON CONFLICT DO NOTHING;

-- Insertar rutina de ejemplo
INSERT INTO routines (name, objective, difficulty) VALUES
    ('Rutina Full Body Principiante', 'Ganar fuerza general y adaptación muscular', 'facil')
ON CONFLICT DO NOTHING;