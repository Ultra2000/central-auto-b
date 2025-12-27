# Configuration du Paiement Stripe

## 🎯 Vue d'ensemble

Le système de paiement en ligne a été intégré avec Stripe pour traiter les paiements des plaques d'immatriculation et potentiellement les locations de véhicules.

## 📋 Prérequis

1. Un compte Stripe (https://dashboard.stripe.com)
2. Les clés API Stripe (Publishable Key et Secret Key)

## 🔧 Configuration

### 1. Créer un compte Stripe

1. Inscrivez-vous sur https://stripe.com
2. Activez votre compte (vérification d'identité nécessaire pour le mode production)

### 2. Récupérer les clés API

#### Mode Test (Développement)
1. Allez sur https://dashboard.stripe.com/test/apikeys
2. Récupérez:
   - **Publishable key** (commence par `pk_test_`)
   - **Secret key** (commence par `sk_test_`)

#### Mode Production
1. Allez sur https://dashboard.stripe.com/apikeys
2. Récupérez:
   - **Publishable key** (commence par `pk_live_`)
   - **Secret key** (commence par `sk_live_`)

### 3. Configurer les clés dans .env

Ouvrez le fichier `.env` et remplacez les valeurs:

```env
STRIPE_KEY=pk_test_votre_cle_publishable_ici
STRIPE_SECRET=sk_test_votre_cle_secrete_ici
VITE_STRIPE_KEY="${STRIPE_KEY}"
```

**⚠️ IMPORTANT:** 
- Ne commitez JAMAIS le fichier `.env` avec vos vraies clés
- En production, utilisez les clés `pk_live_` et `sk_live_`

### 4. Configurer les Webhooks (Optionnel mais recommandé)

Les webhooks permettent à Stripe de notifier votre application des événements de paiement.

1. Allez sur https://dashboard.stripe.com/test/webhooks
2. Cliquez sur "Add endpoint"
3. URL du endpoint: `https://votre-domaine.com/webhook/stripe`
4. Sélectionnez les événements:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Récupérez le **Signing secret** (commence par `whsec_`)
6. Ajoutez-le dans `.env`:

```env
STRIPE_WEBHOOK_SECRET=whsec_votre_secret_webhook_ici
```

### 5. Tester localement avec Stripe CLI (Optionnel)

Pour tester les webhooks en local:

```bash
# Installer Stripe CLI
# Windows: télécharger depuis https://github.com/stripe/stripe-cli/releases

# S'authentifier
stripe login

# Transférer les événements vers votre serveur local
stripe listen --forward-to http://127.0.0.1:8000/webhook/stripe
```

## 🚀 Utilisation

### Flux de paiement

1. **Client remplit le formulaire** de commande de plaque
2. **Click sur "Commander"** déclenche la création d'une session Stripe
3. **Redirection vers Stripe Checkout** (page de paiement sécurisée)
4. **Client entre ses informations bancaires** sur la page Stripe
5. **Paiement traité** par Stripe
6. **Redirection vers la page de succès** (`/payment/success`)
7. **Emails envoyés** automatiquement au client et à l'admin
8. **Commande marquée comme payée** dans la base de données

### Pages créées

- ✅ `/payment/success` - Page de confirmation après paiement réussi
- ❌ `/payment/cancel` - Page affichée si le paiement est annulé
- 🔗 `/payment/checkout` - API endpoint pour créer une session Stripe
- 📡 `/webhook/stripe` - Endpoint pour recevoir les événements Stripe

## 💳 Cartes de test Stripe

Pour tester les paiements en mode test:

### Paiement réussi
- **Numéro:** 4242 4242 4242 4242
- **Date:** n'importe quelle date future
- **CVC:** n'importe quel 3 chiffres
- **Code postal:** n'importe quel code postal

### Paiement refusé (carte insuffisante)
- **Numéro:** 4000 0000 0000 9995

### Authentification 3D Secure requis
- **Numéro:** 4000 0025 0000 3155

[Liste complète des cartes de test](https://stripe.com/docs/testing#cards)

## 📊 Base de données

### Nouveaux champs ajoutés à `plate_orders`:

| Champ | Type | Description |
|-------|------|-------------|
| `payment_status` | enum | pending, paid, failed, refunded |
| `payment_method` | enum | stripe, paypal, cash |
| `stripe_payment_intent_id` | string | ID de l'intention de paiement Stripe |
| `stripe_session_id` | string | ID de la session Checkout |
| `paid_at` | timestamp | Date et heure du paiement |

## 🔒 Sécurité

- ✅ Les informations de carte ne transitent JAMAIS par votre serveur
- ✅ Stripe gère toute la conformité PCI-DSS
- ✅ Les webhooks sont signés pour vérifier leur provenance
- ✅ Les clés secrètes sont stockées dans `.env` (jamais dans le code)

## 📝 Checklist avant mise en production

- [ ] Remplacer les clés test par les clés live
- [ ] Configurer les webhooks avec l'URL de production
- [ ] Tester un vrai paiement en mode live (avec une vraie carte)
- [ ] Vérifier la réception des emails
- [ ] Tester l'annulation d'un paiement
- [ ] Configurer le logo de votre entreprise dans le dashboard Stripe
- [ ] Activer les reçus automatiques dans Stripe
- [ ] Configurer la devise (EUR par défaut)

## 🆘 Dépannage

### Erreur "Stripe key not found"
- Vérifiez que `STRIPE_KEY` et `STRIPE_SECRET` sont dans `.env`
- Vérifiez que `VITE_STRIPE_KEY` est configuré
- Redémarrez `npm run dev` après modification du `.env`

### Webhook non reçu
- Vérifiez l'URL du webhook dans le dashboard Stripe
- En local, utilisez Stripe CLI pour transférer les événements
- Vérifiez que `STRIPE_WEBHOOK_SECRET` est correct

### Paiement réussi mais commande non mise à jour
- Vérifiez les logs de l'application
- Assurez-vous que le webhook endpoint est accessible
- Vérifiez que la queue est active (`php artisan queue:work`)

## 📚 Documentation

- [Documentation Stripe](https://stripe.com/docs)
- [Laravel Cashier](https://laravel.com/docs/11.x/billing)
- [Stripe.js Documentation](https://stripe.com/docs/js)

## 💡 Extensions futures possibles

- [ ] Paiement en plusieurs fois avec Stripe
- [ ] Support Apple Pay / Google Pay
- [ ] Remboursements automatiques
- [ ] Abonnements pour locations longue durée
- [ ] Facturation automatique PDF

## 🎨 Personnalisation

Le design des pages de paiement peut être personnalisé dans:
- `resources/js/Pages/Payment/Success.vue`
- `resources/js/Pages/Payment/Cancel.vue`

Le flux de paiement peut être modifié dans:
- `app/Http/Controllers/PaymentController.php`
- `resources/js/Components/PlatesConfigurator.vue`
