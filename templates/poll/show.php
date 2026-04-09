<?php require __DIR__ . '/../header.php'; ?>

<div class="row align-items-center g-5 py-5">
  <div class="col-lg-6">
    <h1 class="display-5 fw-bold lh-1 mb-3"><?= htmlspecialchars($poll->getTitle()) ?></h1>
    <p class="lead"><?= nl2br(htmlspecialchars($poll->getDescription())) ?></p>
    <?php if (!empty($_SESSION['user']) && $_SESSION['user']->getId() == $poll->getUserId()) : ?>
      <a href="/poll/edit/?id=<?= $poll->getId() ?>" class="btn btn-outline-primary">Modifier le sondage</a>
    <?php endif; ?>
  </div>
  <div class="col-10 col-sm-8 col-lg-6">
    <h2>Résultats</h2>
    <div class="results">
      <?php 
      $totalVotes = array_sum(array_column($results, 'count'));
      foreach ($items as $index => $item) {
        $votes = $results[$item->getId()]['count'] ?? 0;
        $percent = $totalVotes ? ($votes / $totalVotes * 100) : 0;
      ?>
        <h3><?= htmlspecialchars($item->getName()) ?></h3>
        <div class="progress mb-2" role="progressbar" aria-label="<?= htmlspecialchars($item->getName()) ?>" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
          <div class="progress-bar progress-bar-striped progress-color-<?= $index ?>" style="width: <?= $percent ?>%">
            <?= htmlspecialchars($item->getName()) ?> <?= round($percent, 2) ?>%
          </div>
        </div>
      <?php } ?>
    </div>
    <div class="mt-5">
      <?php if (!empty($_SESSION['user'])) { ?>
        <form method="post" action="/poll/vote/?id=<?= $poll->getId() ?>">
          <h2>Voter pour ce sondage :</h2>
          <div class="btn-group" role="group" aria-label="Choix du sondage">
            <?php foreach ($items as $item) { ?>
              <input type="radio" class="btn-check" id="btncheck<?= $item->getId() ?>" autocomplete="off" value="<?= $item->getId() ?>" name="option" required <?= ($userVote == $item->getId()) ? 'checked' : '' ?>>
              <label class="btn btn-outline-primary" for="btncheck<?= $item->getId() ?>"><?= htmlspecialchars($item->getName()) ?></label>
            <?php } ?>
          </div>
          <?php if (!empty($error)) { ?>
            <div class="alert alert-danger mt-2" role="alert">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php } ?>
          <div class="mt-2">
            <input type="submit" class="btn btn-primary" value="Voter">
          </div>
        </form>
      <?php } else { ?>
        <div class="alert alert-warning mt-3">
          Vous devez être connecté pour voter.
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<div class="row mt-5">
  <div class="col-12">
    <h2>Commentaires</h2>
    <?php if (!empty($comments)) : ?>
      <?php foreach ($comments as $comment) : ?>
        <div class="card mb-3">
          <div class="card-body">
            <p class="card-text"><?= nl2br(htmlspecialchars($comment->getContent())) ?></p>
            <small class="text-muted">Posté le <?= date('d/m/Y H:i', strtotime($comment->getCreatedAt())) ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['user'])) { ?>
      <form method="post" action="/poll/comment/">
        <input type="hidden" name="poll_id" value="<?= $poll->getId() ?>">
        <div class="mb-3">
          <label for="content" class="form-label">Ajouter un commentaire</label>
          <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-secondary">Commenter</button>
      </form>
    <?php } else { ?>
      <div class="alert alert-warning">
        Connectez-vous pour ajouter un commentaire.
      </div>
    <?php } ?>
  </div>
</div>

<a href="/" class="btn btn-secondary mt-4">Retour à la liste</a>
<?php require __DIR__ . '/../footer.php'; ?>
