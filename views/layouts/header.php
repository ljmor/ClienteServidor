<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Gimnasio</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <!-- CLIENTE: Esta es la interfaz que el usuario ve en su navegador -->
    <header class="header">
        <div class="container">
            <h1 class="logo">🏋️ Gimnasio MVC</h1>
            <nav class="nav">
                <a href="/index.php?controller=member&action=index" class="nav-link">Miembros</a>
                <a href="/index.php?controller=class&action=index" class="nav-link">Clases</a>
                <a href="/index.php?controller=payment&action=index" class="nav-link">Pagos</a>
                <a href="/index.php?controller=instructor&action=index" class="nav-link">Instructores</a>
                <a href="/index.php?controller=routine&action=index" class="nav-link">Rutinas</a>
                <a href="/index.php?controller=exercise&action=index" class="nav-link">Ejercicios</a>
            </nav>
        </div>
    </header>
    
    <main class="main container">
        <!-- Mensajes de éxito/error del SERVIDOR al CLIENTE -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                $messages = [
                    'created' => 'Registro creado exitosamente',
                    'updated' => 'Registro actualizado exitosamente',
                    'deleted' => 'Registro eliminado exitosamente',
                    'block_added' => 'Bloque agregado exitosamente',
                    'block_deleted' => 'Bloque eliminado exitosamente',
                    'exercise_added' => 'Ejercicio agregado al bloque',
                    'exercise_removed' => 'Ejercicio removido del bloque'
                ];
                echo $messages[$_GET['success']] ?? 'Operacion exitosa';
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php
                $messages = [
                    'not_found' => 'Registro no encontrado',
                    'delete_failed' => 'Error al eliminar el registro',
                    'invalid_data' => 'Datos invalidos',
                    'remove_failed' => 'Error al remover el ejercicio'
                ];
                echo $messages[$_GET['error']] ?? 'Ha ocurrido un error';
                ?>
            </div>
        <?php endif; ?>

