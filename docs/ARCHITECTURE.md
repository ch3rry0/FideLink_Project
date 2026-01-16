# Architecture Technique FideLink

## Vue d'ensemble

FideLink est une plateforme de fidélité utilisant une **architecture API REST** avec :
- **Backend** : Symfony 6.4 avec MongoDB
- **Frontend** : PHP + Alpine.js + TailwindCSS
- **Base de données** : MongoDB (port 27018)

---

## 1. Symfony - Backend API RESTful

### Rôle
Symfony gère toute la logique métier côté serveur via une API REST. Il expose des endpoints JSON consommés par le frontend.

### Architecture Symfony utilisée

#### 📁 Documents (Modèles MongoDB)
Symfony utilise **Doctrine MongoDB ODM** pour mapper les collections MongoDB en classes PHP.

**Exemple : Customer.php**
```php
#[MongoDB\Document(collection: 'customers')]
class Customer
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $fdl_id = null;

    #[MongoDB\Field(type: 'float')]
    private float $pointsBal = 0;

    #[MongoDB\Field(type: 'date')]
    private ?\DateTime $createdAt = null;
}
```
- Les annotations `#[MongoDB\Document]` définissent la collection
- `#[MongoDB\Field]` mappe les propriétés aux champs MongoDB
- `#[MongoDB\UniqueIndex]` crée des index uniques

**Exemple : Merchant.php**
```php
#[MongoDB\Document(collection: 'merchants')]
class Merchant
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex]
    private ?string $merchant_id = null;

    #[MongoDB\EmbedOne(targetDocument: Location::class)]
    private ?Location $loc = null;

    #[MongoDB\Field(type: 'float')]
    private ?float $pointVal = null;
}
```
- `#[MongoDB\EmbedOne]` permet d'imbriquer des sous-documents (Location)

#### 🎮 Controllers (API REST)
Les controllers Symfony exposent des endpoints RESTful avec des routes et retournent du JSON.

**Exemple : AuthController.php**
```php
#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        private DocumentManager $dm
    ) {}

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Recherche multi-collection
        $customer = $this->dm->getRepository(Customer::class)
            ->findOneBy(['email' => $identifier]);
        
        if ($customer && password_verify($password, $customer->getPassword())) {
            return $this->json([
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'type' => 'customer'
            ]);
        }
    }
}
```
**Points clés** :
- `#[Route('/api/auth')]` préfixe toutes les routes
- `methods: ['POST']` définit la méthode HTTP
- `DocumentManager` injecté par Dependency Injection
- `$this->json()` retourne une réponse JSON

#### 🔧 Services & Dependency Injection
Symfony injecte automatiquement les dépendances via le constructeur.

**Exemple dans MerchantController.php**
```php
public function __construct(
    private DocumentManager $dm
) {}

#[Route('/{id}', name: 'update', methods: ['PUT'])]
public function update(string $id, Request $request): JsonResponse
{
    $merchant = $this->dm->getRepository(Merchant::class)->find($id);
    $merchant->setPointVal($data['pointVal']);
    
    $this->dm->flush(); // Persiste les changements
    
    return $this->json(['success' => true]);
}
```

---

## 2. Alpine.js - Réactivité Frontend

### Rôle
Alpine.js gère l'interactivité côté client avec une approche déclarative directement dans le HTML.

### Fonctionnalités utilisées

#### 🎯 x-data : Composant réactif
Définit un scope de données et méthodes pour un élément HTML.

**Exemple : customer-dashboard.php**
```html
<body x-data="customerDashboard()" x-init="init()">
    <script>
        function customerDashboard() {
            return {
                user: {},
                merchants: [],
                searchQuery: '',
                
                init() {
                    this.user = JSON.parse(sessionStorage.getItem('user'));
                    this.loadMerchants();
                },
                
                async loadMerchants() {
                    const response = await fetch('http://localhost:8000/api/merchants');
                    this.merchants = await response.json();
                },
                
                searchMerchants() {
                    this.filteredMerchants = this.merchants.filter(m =>
                        m.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                }
            }
        }
    </script>
</body>
```

#### 🔄 x-model : Binding bidirectionnel
```html
<input type="text" 
       x-model="searchQuery" 
       @input="searchMerchants"
       placeholder="Rechercher un commerçant...">
```
- `x-model` lie l'input à la propriété `searchQuery`
- `@input` déclenche la méthode `searchMerchants` à chaque frappe

#### 📝 x-text & x-show : Affichage conditionnel
```html
<h2 x-text="user.pointsBal + ' FDL'"></h2>
<p x-show="merchant.loc" x-text="merchant.loc.city"></p>
```

#### 🔁 x-for : Boucles
```html
<template x-for="merchant in filteredMerchants" :key="merchant.id">
    <div @click="viewMerchant(merchant)">
        <h4 x-text="merchant.name"></h4>
        <p x-text="calculateValue(merchant.pointVal)"></p>
    </div>
</template>
```

#### 🎬 x-init : Initialisation
```html
<body x-init="init()">
```
Exécute automatiquement la méthode `init()` au chargement.

#### 🖱️ Événements (@click, @input, @keydown)
```html
<button @click="editMode = !editMode">Modifier</button>
<body @keydown.escape="menuOpen = false">
```

---

## 3. Modèle MVC (Model-View-Controller)

### Organisation dans FideLink

#### 📊 Model (Modèles de données)
**Backend : Documents Symfony**
- `backend/src/Document/Customer.php`
- `backend/src/Document/Merchant.php`
- `backend/src/Document/Admin.php`
- `backend/src/Document/Transaction.php`

Ces classes définissent la structure des données et les mappent à MongoDB.

#### 🎮 Controller (Logique métier)
**Backend : Controllers Symfony**
- `backend/src/Controller/AuthController.php` - Authentification
- `backend/src/Controller/CustomerController.php` - CRUD customers
- `backend/src/Controller/MerchantController.php` - CRUD merchants
- `backend/src/Controller/TransactionController.php` - Transactions

Les controllers :
1. Reçoivent les requêtes HTTP
2. Appellent les repositories pour accéder aux données
3. Appliquent la logique métier
4. Retournent des réponses JSON

**Exemple : AuthController - Authentification multi-collection**
```php
// 1. Chercher dans customers (par email ou fdl_id)
$customer = $this->dm->getRepository(Customer::class)
    ->findOneBy(['email' => $identifier]);
if (!$customer) {
    $customer = $this->dm->getRepository(Customer::class)
        ->findOneBy(['fdl_id' => $identifier]);
}

// 2. Vérifier le mot de passe
if ($customer && password_verify($password, $customer->getPassword())) {
    return $this->json(['type' => 'customer', 'name' => $customer->getName()]);
}
```

#### 👁️ View (Interface utilisateur)
**Frontend : Pages PHP avec Alpine.js**
- `frontend/public/connexion.php` - Page de connexion
- `frontend/public/customer-dashboard.php` - Dashboard client
- `frontend/public/merchant-dashboard.php` - Dashboard commerçant
- `frontend/public/admin-dashboard.php` - Dashboard admin

Les views :
1. Affichent les données reçues de l'API
2. Gèrent les interactions utilisateur avec Alpine.js
3. Envoient les actions au backend via fetch()

**Exemple : Connexion utilisateur**
```javascript
async login() {
    const response = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            identifier: this.identifier,
            password: this.password
        })
    });
    
    const data = await response.json();
    sessionStorage.setItem('user', JSON.stringify(data));
    
    // Redirection selon le type
    if (data.type === 'customer') {
        window.location.href = 'customer-dashboard.php';
    }
}
```

---

## 4. Flux de données

### Exemple complet : Connexion utilisateur

```
┌─────────────────┐
│  1. UTILISATEUR │  Saisit email + mot de passe
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│  2. VIEW (connexion.php)│  Alpine.js : x-model sur inputs
│     @submit="login()"   │  Capture la soumission du formulaire
└────────┬────────────────┘
         │
         │ fetch('http://localhost:8000/api/auth/login', {POST})
         ▼
┌──────────────────────────────┐
│  3. CONTROLLER              │
│  (AuthController.php)       │  Reçoit JSON : {identifier, password}
│  - Route: POST /api/auth/login │
└────────┬─────────────────────┘
         │
         │ $dm->getRepository(Customer::class)->findOneBy(['email' => ...])
         ▼
┌──────────────────────────────┐
│  4. MODEL (Customer.php)    │  Doctrine ODM interroge MongoDB
│     + MongoDB               │  Collection: customers
└────────┬─────────────────────┘
         │
         │ Retourne l'objet Customer si trouvé
         ▼
┌──────────────────────────────┐
│  5. CONTROLLER              │  password_verify($password, $customer->getPassword())
│  (Vérification password)    │  Retourne JSON : {id, name, email, type: 'customer'}
└────────┬─────────────────────┘
         │
         │ JSON Response
         ▼
┌──────────────────────────────┐
│  6. VIEW (connexion.php)    │  sessionStorage.setItem('user', JSON.stringify(data))
│     Alpine.js               │  window.location.href = 'customer-dashboard.php'
└────────┬─────────────────────┘
         │
         ▼
┌──────────────────────────────┐
│  7. customer-dashboard.php  │  x-init="init()" charge les données
│     user = sessionStorage    │  Affiche : x-text="user.name"
└──────────────────────────────┘
```

---

## 5. Technologies complémentaires

### TailwindCSS
Framework CSS utilitaire pour le styling.

**Exemple : Carte gradient**
```html
<div class="bg-gradient-to-br from-primary-orange to-gold rounded-3xl shadow-2xl p-8">
    <h2 class="text-5xl font-black" x-text="user.pointsBal + ' FDL'"></h2>
</div>
```
- Classes utilitaires : `bg-gradient-to-br`, `text-5xl`, `font-black`
- Configuration personnalisée :
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'primary-orange': '#FF4500',
                'gold': '#FFD700'
            }
        }
    }
}
```

### SessionStorage
Stockage client pour persister la session utilisateur.

**Exemple dans header.php**
```javascript
x-data="{
    user: JSON.parse(sessionStorage.getItem('user') || '{}')
}"

<!-- Affichage conditionnel -->
<a x-show="user.type === 'customer'" href="customer-dashboard.php">
    <span x-text="user.name"></span>
</a>
```

---

## 6. Sécurité

### Hachage des mots de passe
**Backend : Document setPassword()**
```php
public function setPassword(string $password): self
{
    $this->password = password_hash($password, PASSWORD_DEFAULT);
    return $this;
}
```

### Vérification lors du login
```php
if (password_verify($password, $customer->getPassword())) {
    // Authentification réussie
}
```

### CORS (Cross-Origin Resource Sharing)
Configuration dans `backend/public/index.php` pour permettre les requêtes depuis localhost:8080.

---

## Conclusion

L'architecture FideLink combine :
- **Symfony** : API REST robuste avec gestion MongoDB via Doctrine ODM
- **Alpine.js** : Réactivité légère et déclarative dans le frontend
- **MVC** : Séparation claire entre données (Models), logique (Controllers) et interface (Views)

Cette stack permet un développement rapide et maintenable avec une API backend indépendante consommable par n'importe quel client.
