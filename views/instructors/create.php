<?php
/**
 * Vista: Crear Instructor
 * 
 * CLIENTE: Formulario que captura datos del usuario y los envía al SERVIDOR
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Nuevo Instructor</h2>

<form method="POST" action="/index.php?controller=instructor&action=store" class="form">
    <div class="form-group">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required 
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
               class="<?php echo isset($errors['name']) ? 'error' : ''; ?>">
        <?php if (isset($errors['name'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required 
               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
               class="<?php echo isset($errors['email']) ? 'error' : ''; ?>">
        <?php if (isset($errors['email'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="tel" id="phone" name="phone" 
               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
        <label for="specialization">Especialización</label>
        <input type="text" id="specialization" name="specialization" 
               placeholder="Ej: Yoga, CrossFit, Pilates"
               value="<?php echo htmlspecialchars($_POST['specialization'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
        <label for="hire_date">Fecha de Contratación *</label>
        <input type="date" id="hire_date" name="hire_date" required 
               value="<?php echo htmlspecialchars($_POST['hire_date'] ?? date('Y-m-d')); ?>"
               class="<?php echo isset($errors['hire_date']) ? 'error' : ''; ?>">
        <?php if (isset($errors['hire_date'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['hire_date']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="/index.php?controller=instructor&action=index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>