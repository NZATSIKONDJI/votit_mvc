<?php require __DIR__ . '/../header.php'; ?>

<div class="py-5 text-center">
    <h1 class="display-5 fw-bold">Catégories de sondages</h1>
    <p class="lead">Découvrez les différentes catégories de sondages disponibles sur VotIt.</p>
</div>

<div class="row">
    <?php if (!empty($categories)) : ?>
        <?php foreach ($categories as $category) : ?>
            <div class="col-md-4 my-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($category->getName()) ?></h5>
                        <p class="card-text">Explorez les sondages dans cette catégorie.</p>
                        <a href="/poll/list?category=<?= $category->getId() ?>" class="btn btn-primary mt-auto">Voir les sondages</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <p>Aucune catégorie disponible pour le moment.</p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../footer.php'; ?>