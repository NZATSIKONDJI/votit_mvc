<?php require __DIR__ . '/../header.php'; ?>
<h2>Modifier le sondage</h2>
<form method="post" action="/poll/edit/post/">
  <input type="hidden" name="id" value="<?= $poll->getId() ?>">
  <div class="mb-3">
    <label for="title" class="form-label">Titre</label>
    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($poll->getTitle()) ?>" required>
  </div>
  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($poll->getDescription()) ?></textarea>
  </div>
  <div class="mb-3">
    <label for="category_id" class="form-label">Catégorie</label>
    <select class="form-control" id="category_id" name="category_id" required>
      <option value="1" <?= $poll->getCategoryId() == 1 ? 'selected' : '' ?>>front-end</option>
      <option value="2" <?= $poll->getCategoryId() == 2 ? 'selected' : '' ?>>back-end</option>
      <option value="3" <?= $poll->getCategoryId() == 3 ? 'selected' : '' ?>>devops</option>
      <option value="4" <?= $poll->getCategoryId() == 4 ? 'selected' : '' ?>>UX/UI</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Options actuelles</label>
    <ul class="list-group">
      <?php foreach ($items as $item) : ?>
        <li class="list-group-item"><?= htmlspecialchars($item->getName()) ?></li>
      <?php endforeach; ?>
    </ul>
    <small class="form-text text-muted">Les options ne peuvent pas être modifiées pour le moment.</small>
  </div>
  <button type="submit" class="btn btn-success">Modifier</button>
  <a href="/poll/?id=<?= $poll->getId() ?>" class="btn btn-secondary">Annuler</a>
</form>
<?php require __DIR__ . '/../footer.php'; ?>