<h1>Pages d'accueil des annonces</h1>

<a href="/mvc/public/annonces/ajouter" class="btn btn-primary">Ajouter une annonce</a>

<hr>
<?php foreach($annonces as $annonce) : ?>
    <article>
        <img src="/mvc/public/uploads/<?= $annonce->image ?>" alt="" width="100px" height="100px">
        <h2><a href="annonces/lire/<?= $annonce->id ?>"><?= $annonce->titre ?></a></h2>
        <p><?= $annonce->description ?></p>
        <small><?= $annonce->created_at ?></small>
    </article>
    <hr>
<?php endforeach; ?>