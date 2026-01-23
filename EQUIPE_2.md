
## Focus: taches pour les 2 Devs HTTP/Views
### Objectif
Livrer un flux complet HTTP -> Controller -> View/JSON avec layout et helpers. Couverture: routes web, Request/Response, controleurs, systeme de vues, helpers, exemples.

### Etapes concretes
1) Creer classes Request/Response
   - Fichier: `app/Http/Request.php` (method, uri, path, query, body, headers, cookies; helpers `input($key)`, `json()`, `isMethod($m)`).
   - Fichier: `app/Http/Response.php` (status, headers, body; helpers statiques `json($data, $status = 200)`, `html($body, $status = 200)`; methode `send()` pour emettre).
   - Variable env utile: `APP_ENV` pour mode dev (affichage erreurs).

2) Integrer Request/Response dans le Router
   - Adapter `app/Core/Router.php` pour recevoir un `Request` et retourner un `Response`.
   - Resolver `Controller@method` via App/DI; si un controleur retourne array => transformer en `Response::json`; si string => `Response::html`.
   - Gerer 404/405 via reponses Response.

3) Base Controller
   - Fichier: `app/Http/Controller.php` avec helpers:
     - `view($name, array $data = []) : Response`
     - `json($data, int $status = 200) : Response`
     - `redirect($url, int $status = 302) : Response`

4) Systeme de vues (layout + helpers)
   - Fichier: `app/Http/View.php` avec methode statique `make($name, $data = [], $layout = null)`.
   - Dossier: `views/` pour templates; `views/layouts/` pour layouts.
   - Convention: `view('home', ['user' => $user])` charge `views/home.php`; si `$layout` fourni, wrapper avec `views/layouts/$layout.php` en utilisant une variable `$content`.
   - Helpers globaux: fonction `view($name, $data = [], $layout = null)` dans un fichier helpers (ex: `app/Core/helpers.php`) qui delegue a `View::make`.

5) Exemples de controleurs et vues
   - `app/Http/Controllers/HomeController.php` :
     - action `index(Request $request)` => `return $this->view('home', ['title' => 'Accueil']);`
     - action `api(Request $request)` => `return $this->json(['status' => 'ok']);`
   - Vues:
     - `views/home.php` (contenu principal)
     - `views/layouts/app.php` (HTML <head>, header/footer, echo `$content`)
   - Route: dans `routes/web.php`, map `GET /` vers `HomeController@index`, `GET /api/ping` vers `HomeController@api`.

6) Middlewares (optionnel mais utile)
   - Interface simple `handle(Request $request, Closure $next): Response`.
   - Exemple: middleware `JsonMiddleware` qui force `Content-Type: application/json` pour routes API.

7) Gestion des erreurs
   - Catch global dans front controller: si exception, retourner `Response::json(['error' => $e->getMessage()], 500)` en dev, sinon message generique.
   - Logger via logger central (dev infra) mais assurer une page erreur HTML simple pour routes web.

### Classes/fichiers a produire ou completer
- `app/Http/Request.php`
- `app/Http/Response.php`
- `app/Http/Controller.php` (classe abstraite)
- `app/Http/View.php`
- `app/Core/helpers.php` (fonctions `view`, `json`, `redirect` eventuelles)
- `app/Core/Router.php` (integration Request/Response)
- `app/Http/Controllers/HomeController.php` (exemple)
- `views/home.php`, `views/layouts/app.php`
- `routes/web.php` (routes d'exemple)

### Variables/parametres clefs a utiliser
- `$_SERVER` (REQUEST_METHOD, REQUEST_URI) pour construire Request.
- `$_GET`, `$_POST`, `php://input` pour remplir `Request`.
- `APP_ENV` (.env) pour mode dev/prod (affichage erreurs).
- Optionnel: `VIEW_PATH` si vous voulez rendre le chemin configurable; sinon hardcode `views/`.
