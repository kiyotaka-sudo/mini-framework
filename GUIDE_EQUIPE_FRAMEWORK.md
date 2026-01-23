# Guide pratique pour construire le mini-framework

Public cible: une equipe de 8 personnes debutantes en frameworks. Objectif: construire un mini-framework PHP (style Laravel) en utilisant la structure deja presente dans ce repo.

## 1) Pre-requis et hygiene
- Installer PHP >= 8.1, Composer, et une base MySQL ou SQLite. Verifier avec `php -v` et `composer -V`.
- Cloner le repo et travailler sur des branches par feature. Commit courts et frequents.
- Mettre en place `php -S localhost:8000 -t public` pour servir `public/index.php`.

## 2) Comprendre la structure actuelle
- `public/index.php` : front-controller (point d'entree HTTP).
- `bootstrap.php` : boot du framework (autoload, env, providers).
- `app/Core` : noyau (App.php pour le conteneur, Router.php pour le routage, Database.php pour l'accès BD).
- `app/Http/Controllers` : controleurs (HomeController.php etc.).
- `database/models` : models (ex: User.php).
- `routes/web.php` : declaration des routes.
- `views` : vues PHP (ex: home.php).
- `storage/logs` : logs applicatifs.

## 3) Repartition de l'equipe (8 personnes)
- 1 Lead technique: definit standards, fait les revues.
- 1 Dev infra: bootstrap, autoloading Composer, gestion env (.env), error handling.
- 2 Devs core: Router, App (DI/service container), middleware pipeline.
- 2 Devs HTTP/Views: controleurs, systeme de vues (layout, helpers), reponses JSON/HTML.
- 1 Dev base de donnees: Database.php, models CRUD, migrations simples.
- 1 Dev DX/outils: commandes CLI basiques (artisan-like), doc utilisateur et exemples.


## 4) Plan de livraison par phases
### Phase A - Mise en route
- Ajouter Composer (`composer.json`) avec autoload PSR-4 (`App\\` => `app/`, `Database\\` => `database/`).
- bootstrap: charger `.env`, config error reporting (dev/prod), enregistrer autoload, initialiser logger dans `storage/logs`.
- Front controller `public/index.php` : inclure `../bootstrap.php`, initialiser l'instance App, charger routes, dispatcher la requete.

### Phase B - Noyau HTTP
- Router (`app/Core/Router.php`): enregistrer routes GET/POST, supporter params dynamiques `/users/{id}`, resoudre vers un controleur@method, retourner une Response.
- Requete/Response: creer des classes simples (Request: method, uri, body, query, headers; Response: status, headers, body) et helpers (`json`, `view`).
- Middleware minimal: pile avant controleur (ex: verifier token CSRF, auth faux), interface `handle(Request $request, Closure $next)`.

### Phase C - Controleurs et vues
- Controleur de base (Appel via Router) avec injection basique d'App/Request.
- Systemes de vues (`views/`): helper `view('home', ['name' => '...'])` avec `ob_start()` et extraction des variables; ajouter layouts optionnels.
- Exemple: remplir `routes/web.php`, `HomeController.php`, `views/home.php` pour avoir une page fonctionnelle.

### Phase D - Donnees
- Database.php: encapsuler PDO (connexion DSN depuis .env, options d'erreur, transactions).
- Model de base: classe abstraite avec CRUD generique (find, all, create, update, delete) en s'appuyant sur PDO. Implementer `User` dans `database/models/User.php`.
- Migrations simples: fichier PHP par table dans `database/migrations` (a creer) + script CLI pour les executer dans l'ordre.

### Phase E - Expérience Dev et qualite
- CLI basique (ex: `php artisan` like) dans `bin/console` ou `app/Core/Console.php` pour: lister routes, lancer serveur, lancer migrations.
- Logs: `storage/logs/app.log` avec un logger minimal (date, niveau, message). Ajouter un handler d'exceptions global.
- Tests: au moins un test d'integration pour le Router (ex: `tests/RouterTest.php`) et un pour Database/Model. Utiliser PHPUnit ou Pest.
- Documentation: README pour l'installation/usage, reference des helpers (Request/Response/view/json/db), guide de contribution.

## 5) Checklist detaillee (a cocher)
- [ ] Composer et autoload PSR-4 configures.
- [ ] bootstrap.php charge .env, configure erreurs, logger pret.
- [ ] Router supporte GET/POST + params + 404/405.
- [ ] Request/Response classes + helpers `json()`, `view()`.
- [ ] Middleware pipeline disponible (meme simple).
- [ ] Front controller index.php route correctement.
- [ ] Vues fonctionnent avec variables + layout.
- [ ] Controleur d'exemple (Home) relié a une route.
- [ ] Database.php avec PDO + config env.
- [ ] Model de base + model User fonctionnel.
- [ ] Migrations simples et script d'execution.
- [ ] Logger ecrit dans storage/logs.
- [ ] Tests de base routage et base de donnees.
- [ ] Documentation utilisateur et contribution.

## 6) Conseils pratiques
- Garder les classes petites et testables; eviter les singletons cachees.
- Prioriser la lisibilite: noms clairs, types PHP, commentaires uniquement pour expliquer les intentions.
- Toujours fournir un exemple runnable (route + controleur + vue + model) pour servir de reference aux nouveaux arrivants.

## 7) Prochaines etapes concretes (ordre suggere)
1) Creer `composer.json` avec autoload PSR-4, executer `composer dump-autoload`.
2) Implementer `bootstrap.php` (autoload + .env + logger).
3) Ecrire le front controller `public/index.php` qui monte App, charge routes, et appelle Router.
4) Completer Router + Request/Response + middleware minimal.
5) Creer helper `view()` et mettre a jour `views/home.php` + `HomeController`.
6) Implementer Database.php + base Model + `User.php`.
7) Ajouter migrations + CLI simple pour les lancer.
8) Rédiger README + exemples et lancer les premiers tests.
