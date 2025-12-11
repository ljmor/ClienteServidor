<?php
/**
 * Vista: Editar Ejercicio
 *
 * CLIENTE: Formulario que captura datos del usuario y los envia al SERVIDOR
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Editar Ejercicio</h2>

<form method="POST" action="/index.php?controller=exercise&action=update" class="form">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($exercise['id']); ?>">

    <div class="form-group">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required
               value="<?php echo htmlspecialchars($_POST['name'] ?? $exercise['name']); ?>"
               class="<?php echo isset($errors['name']) ? 'error' : ''; ?>">
        <?php if (isset($errors['name'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="muscle_group">Grupo Muscular *</label>
        <select id="muscle_group" name="muscle_group" required
                class="<?php echo isset($errors['muscle_group']) ? 'error' : ''; ?>">
            <option value="">Seleccione un grupo muscular</option>
            <?php foreach ($muscleGroups as $group): ?>
                <option value="<?php echo htmlspecialchars($group); ?>"
                    <?php echo (($_POST['muscle_group'] ?? $exercise['muscle_group']) === $group) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($group); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['muscle_group'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['muscle_group']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="equipment">Equipamiento</label>
        <input type="text" id="equipment" name="equipment"
               placeholder="Ej: mancuernas, barra, banco (separados por coma)"
               value="<?php echo htmlspecialchars($_POST['equipment'] ?? $exercise['equipment'] ?? ''); ?>">
        <small class="form-help">Ingrese los equipamientos separados por coma</small>
    </div>

    <div class="form-group">
        <label for="description">Descripcion</label>
        <textarea id="description" name="description" rows="3"
                  placeholder="Descripcion del ejercicio..."><?php echo htmlspecialchars($_POST['description'] ?? $exercise['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/index.php?controller=exercise&action=index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
