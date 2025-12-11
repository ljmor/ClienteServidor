<?php
/**
 * Vista: Crear Rutina
 *
 * CLIENTE: Formulario que captura datos del usuario y los envia al SERVIDOR
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Nueva Rutina</h2>

<form method="POST" action="/index.php?controller=routine&action=store" class="form">
    <div class="form-group">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required
               placeholder="Ej: Rutina Full Body, Push Pull Legs..."
               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
               class="<?php echo isset($errors['name']) ? 'error' : ''; ?>">
        <?php if (isset($errors['name'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="objective">Objetivo</label>
        <textarea id="objective" name="objective" rows="3"
                  placeholder="Ej: Ganar masa muscular, perder grasa, mejorar resistencia..."><?php echo htmlspecialchars($_POST['objective'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label for="difficulty">Dificultad *</label>
        <select id="difficulty" name="difficulty" required
                class="<?php echo isset($errors['difficulty']) ? 'error' : ''; ?>">
            <option value="">Seleccione la dificultad</option>
            <?php foreach ($difficulties as $value => $label): ?>
                <option value="<?php echo htmlspecialchars($value); ?>"
                    <?php echo (($_POST['difficulty'] ?? '') === $value) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['difficulty'])): ?>
            <span class="error-message"><?php echo htmlspecialchars($errors['difficulty']); ?></span>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear Rutina</button>
        <a href="/index.php?controller=routine&action=index" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<p class="form-help" style="margin-top: 1rem;">
    <strong>Nota:</strong> Despues de crear la rutina, podras agregar bloques (dias) y ejercicios a cada bloque.
</p>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
