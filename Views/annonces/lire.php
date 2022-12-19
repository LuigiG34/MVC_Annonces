<p>Pages d'annonce individuel</p>

<a href="/mvc/public/annonces/modifier/<?= $annonce->id ?>" class="btn btn-primary">Modifier l'annonce</a>

<article>
    <h2><?= $annonce->titre ?></h2>
    <img src="/mvc/public/uploads/<?= $annonce->image ?>" alt="" width="100px" height="100px">
    <p><?= $annonce->description ?></p>
    <small><?= $annonce->created_at ?></small>
</article>