<?php require __DIR__ . '/../header.php'; ?>
<div class="row text-center">
    <h2>Liste des sondages</h2>
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form method="get" action="/poll/list/">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher un sondage..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <?php if (!empty($polls)) : ?>
            <?php foreach ($polls as $poll) : ?>
                <?php include __DIR__ . '/../poll/poll_part.php'; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <p>Aucun sondage disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
