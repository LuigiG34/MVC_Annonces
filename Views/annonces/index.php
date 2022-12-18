<p>Pages d'accueil des annonces</p>

<a href="/mvc/public/annonces/ajouter" class="btn btn-primary">Ajouter une annonce</a>

<?php foreach($annonces as $annonce) : ?>
    <article>
        <h2><a href="annonces/lire/<?= $annonce->id ?>"><?= $annonce->titre ?></a></h2>
        <p><?= $annonce->description ?></p>
        <small><?= $annonce->created_at ?></small>
    </article>
<?php endforeach; ?>