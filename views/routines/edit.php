<?php
/**
 * Vista: Editar Rutina
 *
 * CLIENTE: Formulario que captura datos del usuario y los envia al SERVIDOR
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Editar Rutina</h2>

<form method="POST" action="/index.php?controller=routine&action=update" class="form">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($routine['id']); ?>">

    <div class="form-group">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required
               placeholder="Ej: Rutina Full Body, Push Pull Legs..."
               value="<?php echo htmlspecialchars($_POST['name'] ?? $routine['name']); ?>"
               class="<?php echo isset($errors['name']) ? 'error' : ''; ?>">
        <?php if (isset($errors['name'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="objective">Objetivo</label>
        <textarea id="objective" name="objective" rows="3"
                  placeholder="Ej: Ganar masa muscular, perder grasa, mejorar resistencia..."><?php echo htmlspecialchars($_POST['objective'] ?? $routine['objective'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label for="difficulty">Dificultad *</label>
        <select id="difficulty" name="difficulty" required
                class="<?php echo isset($errors['difficulty']) ? 'error' : ''; ?>">
            <option value="">Seleccione la dificultad</option>
            <?php foreach ($difficulties as $value => $label): ?>
                <option value="<?php echo htmlspecialchars($value); ?>"
                    <?php echo (($_POST['difficulty'] ?? $routine['difficulty']) === $value) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['difficulty'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['difficulty']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="status">Estado</label>
        <select id="status" name="status">
            <option value="active" <?php echo (($_POST['status'] ?? $routine['status']) === 'active') ? 'selected' : ''; ?>>Activo</option>
            <option value="inactive" <?php echo (($_POST['status'] ?? $routine['status']) === 'inactive') ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="/index.php?controller=routine&action=show&id=<?php echo $routine['id']; ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
