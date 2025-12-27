# 🚗 Central Auto B

**Plateforme de vente et location de véhicules avec service de plaques d'immatriculation personnalisées**

Une application web complète construite avec Laravel 11, Inertia.js et Vue 3 pour la gestion d'un garage automobile à Paris.

---

## ✨ Fonctionnalités

### 🏪 Gestion de Véhicules
- Catalogue complet avec filtres avancés
- Gestion des statuts (disponible, vendu, loué)
- Upload d'images multiples
- Véhicule mis en avant sur la page d'accueil
- Historique des ventes

### 💳 Paiement en Ligne
- **Intégration Stripe** pour paiements sécurisés
- Checkout intégré avec redirection automatique
- Webhooks pour confirmation de paiement
- Gestion des statuts de paiement (pending, paid, failed, refunded)
- Pages de succès/annulation personnalisées

### 🔧 Plaques d'Immatriculation
- Configurateur visuel en temps réel
- Types : définitives et provisoires
- Matériaux : plexiglass et aluminium
- Texte personnalisable (régions françaises)
- Calcul automatique des prix
- Options de livraison ou retrait en magasin

### 📧 Système de Notifications
- Emails automatiques de confirmation de commande
- Templates HTML élégants et responsive
- Notifications admin pour nouvelles commandes
- Système de queue pour envoi asynchrone

### 🌍 Multi-langue
- **11 langues supportées** via Google Translate
- Français, Anglais, Espagnol, Allemand, Italien, Portugais, Russe, Chinois, Japonais, Arabe, Néerlandais
- Dropdown élégant avec drapeaux
- Traduction automatique de tout le contenu

### 📊 Dashboard Admin
- Statistiques en temps réel
- Gestion des véhicules
- Suivi des commandes de plaques
- Gestion des demandes personnalisées
- Configuration des zones de livraison
- Paramètres et tarification

### 📞 Gestion des Leads
- Formulaire de contact intelligent
- Demandes de rappel
- Demandes de visite avec calendrier
- Manifestation d'intérêt pour véhicules
- Suivi et historique dans l'admin

---

## 🛠️ Technologies

### Backend
- **Laravel 11** - Framework PHP moderne
- **SQLite** - Base de données légère
- **Laravel Cashier** - Intégration Stripe
- **Laravel Mail** - Système d'emails

### Frontend
- **Vue 3** - Framework JavaScript progressif
- **Inertia.js** - SPA sans API
- **Tailwind CSS** - Framework CSS utility-first
- **Phosphor Icons** - Icônes modernes
- **Stripe.js** - SDK de paiement

### Outils
- **Vite** - Build tool ultra-rapide
- **Composer** - Gestionnaire de dépendances PHP
- **NPM** - Gestionnaire de packages JavaScript

---

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- SQLite

### Installation

1. **Cloner le repository**
```bash
git clone https://github.com/Ultra2000/central-auto-b.git
cd central-auto-b
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
```bash
touch database/database.sqlite
php artisan migrate
```

5. **Configurer Stripe** (optionnel)

Créez un compte sur [stripe.com](https://stripe.com) et ajoutez vos clés dans `.env`:

```env
STRIPE_KEY=pk_test_votre_cle_publishable
STRIPE_SECRET=sk_test_votre_cle_secrete
VITE_STRIPE_KEY="${STRIPE_KEY}"
```

📖 Voir [STRIPE_SETUP.md](STRIPE_SETUP.md) pour plus de détails

6. **Configurer les emails** (optionnel)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@centralauto-b.com"
MAIL_ADMIN_ADDRESS="admin@centralauto-b.com"
```

7. **Créer un compte admin**
```bash
php artisan make:filament-user
```

8. **Lancer le serveur de développement**
```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend
npm run dev

# Terminal 3 - Queue (pour les emails)
php artisan queue:work
```

L'application sera accessible sur `http://127.0.0.1:8000`

---

## 📁 Structure du Projet

```
central-auto-b/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Contrôleurs admin
│   │   ├── PaymentController   # Gestion paiements Stripe
│   │   ├── PlateOrderController
│   │   └── VehicleInterestController
│   ├── Mail/                   # Templates emails
│   └── Models/                 # Modèles Eloquent
├── database/
│   ├── migrations/             # Schéma de base de données
│   └── database.sqlite
├── resources/
│   ├── js/
│   │   ├── Components/         # Composants Vue réutilisables
│   │   ├── Pages/              # Pages Inertia
│   │   │   ├── Admin/          # Pages admin
│   │   │   ├── Payment/        # Pages paiement
│   │   │   └── Welcome.vue     # Page d'accueil
│   │   └── Layouts/            # Layouts de l'app
│   └── views/
│       ├── app.blade.php       # Template principal
│       └── emails/             # Templates emails HTML
├── routes/
│   └── web.php                 # Routes de l'application
├── public/
│   └── images/                 # Assets publics
├── STRIPE_SETUP.md             # Guide Stripe
└── README.md
```

---

## 🎯 Utilisation

### Interface Publique

#### Page d'accueil
- Véhicule en vedette
- Catalogue des derniers véhicules
- Section services et avantages
- Témoignages clients
- Formulaire de contact

#### Catalogue
- Vue grille des véhicules disponibles
- Filtres par marque, modèle, prix
- Pagination
- Détails de chaque véhicule

#### Plaques d'immatriculation
- Configurateur interactif
- Aperçu en temps réel
- Choix du matériau et texte
- Options de livraison
- Paiement sécurisé via Stripe

### Interface Admin (`/admin`)

#### Dashboard
- Statistiques des ventes
- Commandes récentes
- Revenus mensuels
- Graphiques d'activité

#### Gestion des véhicules
- Ajout/modification/suppression
- Upload multiple d'images
- Marquage "vendu" ou "loué"
- Mise en avant sur l'accueil

#### Commandes de plaques
- Liste de toutes les commandes
- Filtres par statut de paiement
- Détails client et livraison
- Suivi des paiements Stripe

#### Demandes clients
- Demandes personnalisées
- Manifestations d'intérêt
- Demandes de visite
- Gestion du statut de traitement

#### Configuration
- Tarifs des plaques
- Zones de livraison
- Disponibilités pour visites
- Paramètres généraux

---

## 💳 Paiements Stripe

Le système de paiement est entièrement fonctionnel avec Stripe:

1. **Client passe commande** → formulaire de plaque
2. **Création session Stripe** → redirection checkout
3. **Client paie** → page de paiement sécurisée Stripe
4. **Confirmation** → retour automatique sur le site
5. **Emails envoyés** → client + admin notifiés
6. **Webhook** → mise à jour automatique du statut

### Cartes de test

Pour tester les paiements en mode développement:

- **Succès**: `4242 4242 4242 4242`
- **Refus**: `4000 0000 0000 9995`
- **3D Secure**: `4000 0025 0000 3155`
- Date: n'importe quelle date future
- CVC: n'importe quel 3 chiffres

📖 Documentation complète: [STRIPE_SETUP.md](STRIPE_SETUP.md)

---

## 🌍 Traduction

La traduction automatique est activée via Google Translate:

- **11 langues** disponibles
- **Traduction instantanée** de tout le contenu
- **Dropdown personnalisé** dans le header
- **Cookies de langue** pour mémoriser le choix

Langues supportées: 🇫🇷 🇬🇧 🇪🇸 🇩🇪 🇮🇹 🇵🇹 🇷🇺 🇨🇳 🇯🇵 🇸🇦 🇳🇱

---

## 📧 Emails

### Templates disponibles

- **Confirmation de commande client** - Récapitulatif élégant avec détails
- **Notification admin** - Alerte pour nouvelle commande

### Configuration

Les emails utilisent les templates Blade HTML avec design responsive.

Pour tester en local, utilisez [Mailtrap](https://mailtrap.io) ou configurez votre SMTP.

---

## 🚀 Déploiement

### Préparation

1. **Configuration production**
```bash
APP_ENV=production
APP_DEBUG=false
```

2. **Optimisation**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. **Stripe en mode live**
```env
STRIPE_KEY=pk_live_votre_cle_live
STRIPE_SECRET=sk_live_votre_cle_live
```

4. **Configurer webhooks Stripe**
- URL: `https://votre-domaine.com/webhook/stripe`
- Événements: `checkout.session.completed`, `payment_intent.succeeded`

### Hébergement recommandé

- **Heroku** - Déploiement facile
- **DigitalOcean** - VPS flexible
- **Laravel Forge** - Optimisé Laravel
- **Vercel** - Pour frontend statique

---

## 🔐 Sécurité

- ✅ Protection CSRF sur tous les formulaires
- ✅ Validation des données côté serveur
- ✅ Authentification Laravel Breeze
- ✅ Paiements sécurisés via Stripe (conformité PCI-DSS)
- ✅ Sanitisation des entrées utilisateur
- ✅ HTTPS obligatoire en production
- ✅ Clés API dans variables d'environnement

---

## 🧪 Tests

### Tester les emails localement
```bash
php test_emails.php
```

### Tester les paiements
1. Utilisez les cartes de test Stripe
2. Vérifiez le dashboard Stripe en mode test
3. Consultez les logs: `storage/logs/laravel.log`

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer:

1. Forkez le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'feat: Add AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

---

## 📝 Licence

Ce projet est sous licence MIT.

---

## 📞 Contact

**Central Auto B**  
📍 Paris, Île-de-France  
📧 contact@centralauto-b.com  
🌐 [GitHub Repository](https://github.com/Ultra2000/central-auto-b)

---

## 🙏 Remerciements

- [Laravel](https://laravel.com) - Framework PHP
- [Vue.js](https://vuejs.org) - Framework JavaScript
- [Stripe](https://stripe.com) - Plateforme de paiement
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Inertia.js](https://inertiajs.com) - Adaptateur moderne

---

**Fait avec ❤️ pour Central Auto B**
