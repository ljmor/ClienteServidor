<?php
/**
 * Vista: Editar Instructor
 * 
 * CLIENTE: Formulario que muestra datos del SERVIDOR y permite editarlos
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Editar Instructor</h2>

<form method="POST" action="/index.php?controller=instructor&action=update" class="form">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($instructor['id']); ?>">
    
    <div class="form-group">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required 
               value="<?php echo htmlspecialchars($instructor['name']); ?>"
               class="<?php echo isset($errors['name']) ? 'error' : ''; ?>">
        <?php if (isset($errors['name'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required 
               value="<?php echo htmlspecialchars($instructor['email']); ?>"
               class="<?php echo isset($errors['email']) ? 'error' : ''; ?>">
        <?php if (isset($errors['email'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="tel" id="phone" name="phone" 
               value="<?php echo htmlspecialchars($instructor['phone'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
        <label for="specialization">Especialización</label>
        <input type="text" id="specialization" name="specialization" 
               placeholder="Ej: Yoga, CrossFit, Pilates"
               value="<?php echo htmlspecialchars($instructor['specialization'] ?? ''); ?>">
    </div>
    
    <div class="form-group">
        <label for="hire_date">Fecha de Contratación *</label>
        <input type="date" id="hire_date" name="hire_date" required 
               value="<?php echo htmlspecialchars($instructor['hire_date']); ?>"
               class="<?php echo isset($errors['hire_date']) ? 'error' : ''; ?>">
        <?php if (isset($errors['hire_date'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['hire_date']); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="status">Estado *</label>
        <select id="status" name="status" required>
            <option value="active" <?php echo $instructor['status'] === 'active' ? 'selected' : ''; ?>>Activo</option>
            <option value="inactive" <?php echo $instructor['status'] === 'inactive' ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/index.php?controller=instructor&action=index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>