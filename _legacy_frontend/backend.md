# Spécifications Backend - Central Auto B (Stack Laravel + Inertia.js)

Ce document détaille l'architecture et les fonctionnalités backend pour migrer le site statique actuel vers une application web dynamique moderne utilisant **Laravel** et **Inertia.js**.

## 1. Architecture Technique

*   **Framework Backend** : Laravel 10/11 (PHP).
*   **Frontend Adapter** : Inertia.js (Permet de créer une SPA sans API complexe).
*   **Framework Frontend** : Vue.js 3 ou React (Recommandé : Vue.js pour sa simplicité d'intégration avec Laravel).
*   **Base de Données** : MySQL ou PostgreSQL.
*   **ORM** : Eloquent (Natif Laravel).
*   **Authentification** : Laravel Breeze (ou Jetstream) pour l'espace administration.
*   **Styling** : Tailwind CSS (Déjà en place, à conserver).

---

## 2. Modèles de Données (Migrations & Eloquent)

### A. Véhicule (`Vehicle`)
Table : `vehicles`

```php
Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->string('make');           // ex: "Renault"
    $table->string('model');          // ex: "Clio V"
    $table->integer('year');
    $table->enum('type', ['sale', 'rent']);
    $table->decimal('price', 10, 2)->nullable();      // Prix vente
    $table->decimal('rent_price', 8, 2)->nullable();  // Prix location/jour
    $table->string('fuel');           // ex: "Hybride"
    $table->string('transmission');   // ex: "Auto"
    $table->integer('mileage')->nullable();
    $table->integer('seats')->nullable();
    $table->json('images');           // Tableau d'URLs stocké en JSON
    $table->string('badge_type')->nullable(); // 'stock', 'promo', 'lastchance'
    $table->integer('badge_value')->nullable();
    $table->boolean('is_available')->default(true);
    $table->timestamps();
});
```

### B. Lead / Contact (`Lead`)
Table : `leads`

```php
Schema::create('leads', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['contact', 'callback', 'custom_request', 'exit_intent']);
    $table->enum('status', ['new', 'contacted', 'closed'])->default('new');
    $table->string('name');
    $table->string('email')->nullable();
    $table->string('phone');
    $table->json('details')->nullable(); // Stocke marque, modèle, budget, créneau horaire...
    $table->timestamps();
});
```

### C. Commande Plaque (`Order`)
Tables : `orders` et `order_items`

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();
    $table->enum('status', ['pending', 'paid', 'shipped'])->default('pending');
    $table->string('customer_name');
    $table->string('customer_email');
    $table->text('shipping_address');
    $table->decimal('total_amount', 10, 2);
    $table->string('stripe_payment_id')->nullable();
    $table->timestamps();
});

Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained();
    $table->string('plate_number');     // AA-123-AA
    $table->string('department');       // 75
    $table->string('custom_text')->nullable();
    $table->enum('material', ['plexiglass', 'alu']);
    $table->integer('quantity');
    $table->decimal('unit_price', 8, 2);
    $table->timestamps();
});
```

---

## 3. Routes & Contrôleurs (Inertia)

Avec Inertia, nous n'avons pas besoin d'une API REST classique. Les contrôleurs retournent des vues Inertia (Composants Vue/React) avec les données pré-chargées.

### Routes Publiques (`routes/web.php`)

*   `GET /` -> `HomeController@index`
    *   Retourne : `Inertia::render('Home', ['featuredCars' => ...])`
*   `GET /catalogue` -> `CatalogueController@index`
    *   Params : `?type=sale|rent`, `?brand=...`
    *   Retourne : `Inertia::render('Catalogue', ['cars' => ..., 'filters' => ...])`
*   `GET /plaques` -> `PlateController@index`
    *   Retourne : `Inertia::render('PlateCustomizer')`
*   `POST /contact` -> `ContactController@store`
    *   Gère tous les formulaires (Rappel, Contact, Reprise).
*   `POST /checkout/plaques` -> `CheckoutController@createSession`
    *   Crée une session Stripe et redirige l'utilisateur.

### Routes Admin (Protégées par `auth`)

*   `GET /admin/dashboard` -> `Admin\DashboardController@index`
*   `RESOURCE /admin/vehicles` -> `Admin\VehicleController` (CRUD complet)
*   `GET /admin/leads` -> `Admin\LeadController@index`
*   `GET /admin/orders` -> `Admin\OrderController@index`

---

## 4. Migration du Frontend (Stratégie)

Puisque le site actuel est en HTML/JS statique, il faudra le découper en composants Vue.js (ou React).

1.  **Layout Principal (`MainLayout.vue`)** :
    *   Contiendra le `<nav>` (Navbar) et le `<footer>`.
    *   Gérera les notifications "Toast" via l'état global (Pinia ou props Inertia).
2.  **Pages (`Pages/`)** :
    *   `Home.vue` : Contenu de `index.html`.
    *   `Catalogue.vue` : Contenu de `catalogue.html` avec la logique de filtrage réécrite en Vue.js.
    *   `PlateCustomizer.vue` : Contenu de `plaque.html` avec la logique de prévisualisation réactive.
3.  **Composants Réutilisables (`Components/`)** :
    *   `CarCard.vue` : Carte véhicule.
    *   `ServiceCard.vue` : Carte service.
    *   `Simulator.vue` : Le simulateur de carte grise/financement.

---

## 5. Fonctionnalités Clés à Implémenter

### A. Filtrage Catalogue (Côté Serveur)
Contrairement à la version JS actuelle qui filtre côté client, Laravel filtrera via Eloquent :
```php
// CatalogueController.php
$query = Vehicle::query();
if ($request->has('type')) $query->where('type', $request->type);
if ($request->has('make')) $query->where('make', $request->make);
return Inertia::render('Catalogue', [
    'cars' => $query->paginate(12)
]);
```

### B. Gestion des Images
*   Utiliser le système de fichiers de Laravel (`Storage`).
*   Commande : `php artisan storage:link`.
*   Dans le formulaire Admin : Upload multiple de fichiers.

### C. Emails (Mailable)
Créer des classes Mailable Laravel :
*   `NewLeadNotification` (pour l'admin).
*   `OrderConfirmation` (pour le client).

### D. Paiement Stripe
Utiliser le package `laravel/cashier` ou l'SDK Stripe PHP direct pour gérer les paiements des plaques.

---

## 6. Pré-requis Serveur

*   PHP >= 8.1
*   Composer
*   Node.js & NPM (pour compiler les assets avec Vite)
*   Serveur de base de données (MySQL/MariaDB)
