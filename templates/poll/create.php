<?php require __DIR__ . '/../header.php'; ?>
<h2>Créer un sondage</h2>
<form method="post" action="/poll/create/post/">
  <div class="mb-3">
    <label for="title" class="form-label">Titre</label>
    <input type="text" class="form-control" id="title" name="title" required>
  </div>
  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" required></textarea>
  </div>
  <div class="mb-3">
    <label for="category_id" class="form-label">Catégorie</label>
    <select class="form-control" id="category_id" name="category_id" required>
      <option value="1">front-end</option>
      <option value="2">back-end</option>
      <option value="3">devops</option>
      <option value="4">UX/UI</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Options (une par ligne)</label>
    <textarea class="form-control" name="options" rows="4" required></textarea>
    <small class="form-text text-muted">Saisissez chaque option sur une ligne séparée.</small>
  </div>
  <button type="submit" class="btn btn-success">Créer</button>
</form>
<?php require __DIR__ . '/../footer.php'; ?>
