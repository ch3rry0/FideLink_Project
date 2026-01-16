<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Commerçant - FideLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-orange': '#FF4500',
                        'secondary-orange': '#FF6B35',
                        'gold': '#FFD700',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-white min-h-screen" 
      x-data="merchantDashboard()" 
      x-init="init()"
      @keydown.escape="menuOpen = false">
    
    <?php include '../components/header.php'; ?>
    <?php include '../components/menu.php'; ?>

    <!-- Contenu principal -->
    <main class="pt-32 pb-16 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header du dashboard -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-gray-900 mb-2">
                    Tableau de bord - <span class="text-primary-orange" x-text="user.name"></span> 🏪
                </h1>
                <p class="text-gray-600 text-lg">Gérez votre programme de fidélité</p>
            </div>

            <!-- Cartes statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Carte ID Commerçant -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-600 mb-2">ID Commerçant</p>
                            <p class="text-2xl font-black text-primary-orange font-mono" x-text="user.merchant_id"></p>
                        </div>
                        <div class="text-4xl">🆔</div>
                    </div>
                </div>

                <!-- Carte Valeur du point -->
                <div class="bg-gradient-to-br from-primary-orange to-gold rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-white/80 mb-2">Valeur du point FDL</p>
                            <p class="text-3xl font-black" x-text="user.pointVal + '€'"></p>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                </div>

                <!-- Carte Seuil minimum -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-600 mb-2">Seuil minimum</p>
                            <p class="text-2xl font-black text-gray-900" x-text="user.miniThresh + '€'"></p>
                        </div>
                        <div class="text-4xl">📊</div>
                    </div>
                </div>
            </div>

            <!-- Informations du commerce -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Informations de votre commerce</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 mb-1">Nom du commerce</p>
                        <p class="text-xl font-semibold" x-text="user.name"></p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Email</p>
                        <p class="text-xl font-semibold" x-text="user.email"></p>
                    </div>
                    <div x-show="user.loc" class="md:col-span-2">
                        <p class="text-gray-600 mb-1">Adresse</p>
                        <p class="text-xl font-semibold" x-text="user.loc ? `${user.loc.address}, ${user.loc.zip} ${user.loc.city}` : 'Non renseignée'"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 mb-1">Description</p>
                        <p class="text-lg" x-text="user.bio || 'Aucune description'"></p>
                    </div>
                </div>
            </div>

            <!-- Paramètres de fidélité (modifiable) -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Paramètres de fidélité</h2>
                    <button @click="editMode = !editMode" 
                            class="px-4 py-2 rounded-lg font-semibold transition-all duration-300"
                            :class="editMode ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-primary-orange text-white hover:bg-secondary-orange'">
                        <span x-text="editMode ? 'Annuler' : 'Modifier'"></span>
                    </button>
                </div>
                
                <!-- Mode lecture -->
                <div x-show="!editMode" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-br from-primary-orange/10 to-gold/10 rounded-xl border-2 border-primary-orange">
                        <p class="text-gray-600 mb-2">Valeur du point FDL</p>
                        <p class="text-3xl font-bold text-primary-orange" x-text="user.pointVal + '€'"></p>
                        <p class="text-sm text-gray-500 mt-2">1 point FDL = <span x-text="user.pointVal + '€'"></span> chez vous</p>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <p class="text-gray-600 mb-2">Seuil minimum d'achat</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="user.miniThresh + '€'"></p>
                        <p class="text-sm text-gray-500 mt-2">Montant minimum pour gagner des points</p>
                    </div>
                </div>

                <!-- Mode édition -->
                <div x-show="editMode" class="space-y-6">
                    <div class="p-6 bg-primary-orange/5 rounded-xl border-2 border-primary-orange">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Valeur du point FDL (en €)
                        </label>
                        <input type="number" 
                               x-model="editData.pointVal" 
                               step="0.01" 
                               min="0.01"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg text-lg focus:border-primary-orange focus:outline-none"
                               placeholder="0.05">
                        <p class="text-sm text-gray-500 mt-2">
                            💡 Exemple : 0.05€ = un client avec 100 points pourra dépenser 5€ chez vous
                        </p>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Seuil minimum d'achat (en €)
                        </label>
                        <input type="number" 
                               x-model="editData.miniThresh" 
                               step="1" 
                               min="0"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg text-lg focus:border-gray-300 focus:outline-none"
                               placeholder="20">
                        <p class="text-sm text-gray-500 mt-2">
                            💡 Montant minimum qu'un client doit dépenser pour gagner des points
                        </p>
                    </div>

                    <div x-show="updateMessage" 
                         class="p-4 rounded-lg"
                         :class="updateSuccess ? 'bg-green-50 border-2 border-green-200' : 'bg-red-50 border-2 border-red-200'">
                        <p :class="updateSuccess ? 'text-green-600' : 'text-red-600'" 
                           class="font-semibold" 
                           x-text="updateMessage"></p>
                    </div>

                    <button @click="saveSettings" 
                            :disabled="savingSettings"
                            :class="savingSettings ? 'bg-gray-300 cursor-not-allowed' : 'bg-gradient-to-r from-primary-orange to-gold hover:shadow-xl'"
                            class="w-full px-6 py-4 text-white rounded-xl font-bold text-lg transition-all duration-300">
                        <span x-show="!savingSettings">Enregistrer les modifications</span>
                        <span x-show="savingSettings" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Transactions récentes -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Transactions récentes</h2>
                <div x-show="transactions.length === 0 && !loading" class="text-center py-8 text-gray-500">
                    Aucune transaction pour le moment
                </div>
                <div x-show="loading" class="text-center py-8">
                    <svg class="animate-spin h-12 w-12 mx-auto text-primary-orange" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div x-show="transactions.length > 0" class="space-y-4">
                    <template x-for="transaction in transactions" :key="transaction.id">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                                     :class="transaction.type === 'earn' || transaction.type === 1 ? 'bg-green-100' : 'bg-red-100'">
                                    <span x-text="transaction.type === 'earn' || transaction.type === 1 ? '💰' : '🎁'"></span>
                                </div>
                                <div>
                                    <p class="font-semibold">
                                        <span x-show="transaction.type === 'earn' || transaction.type === 1">Points transférés vers </span>
                                        <span x-show="transaction.type === 0">Points récupéré de </span>
                                        <span class="text-primary-orange" x-text="transaction.customer_name || transaction.customer_id"></span>
                                    </p>
                                    <p class="text-sm text-gray-600" x-text="transaction.createdAt ? new Date(transaction.createdAt).toLocaleDateString('fr-FR') : new Date(transaction.transacDate).toLocaleDateString('fr-FR')"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold" 
                                   :class="transaction.type === 'earn' || transaction.type === 1 ? 'text-green-600' : 'text-red-600'"
                                   x-text="(transaction.type === 'earn' || transaction.type === 1 ? '+' : '-') + (transaction.points || transaction.pts || 0).toFixed(2) + ' pts'"></p>
                                <p class="text-sm text-gray-600" x-text="(transaction.amount || 0).toFixed(2) + '€'"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Bouton de déconnexion -->
            <div class="text-center">
                <button @click="logout" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Se déconnecter
                </button>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        function merchantDashboard() {
            return {
                menuOpen: false,
                scrolled: false,
                loading: false,
                editMode: false,
                savingSettings: false,
                updateMessage: '',
                updateSuccess: false,
                user: {},
                transactions: [],
                editData: {
                    pointVal: 0,
                    miniThresh: 0
                },

                init() {
                    // Vérifier si l'utilisateur est connecté
                    const userStr = sessionStorage.getItem('user');
                    if (!userStr) {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.user = JSON.parse(userStr);
                    
                    if (this.user.type !== 'merchant') {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    // Initialiser les valeurs d'édition
                    this.editData.pointVal = this.user.pointVal;
                    this.editData.miniThresh = this.user.miniThresh;

                    this.loadTransactions();
                },

                async loadTransactions() {
                    this.loading = true;
                    try {
                        const response = await fetch(`http://localhost:8000/api/transactions?merchant_id=${this.user.merchant_id}`);
                        if (response.ok) {
                            const transactions = await response.json();
                            this.transactions = transactions.slice(0, 10); // Afficher les 10 dernières
                        }
                    } catch (error) {
                        console.error('Erreur lors du chargement des transactions:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async saveSettings() {
                    this.savingSettings = true;
                    this.updateMessage = '';

                    try {
                        const response = await fetch(`http://localhost:8000/api/merchants/${this.user.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                pointVal: parseFloat(this.editData.pointVal),
                                miniThresh: parseFloat(this.editData.miniThresh)
                            })
                        });

                        if (response.ok) {
                            const updatedMerchant = await response.json();
                            
                            // Mettre à jour les données locales
                            this.user.pointVal = updatedMerchant.pointVal;
                            this.user.miniThresh = updatedMerchant.miniThresh;
                            
                            // Mettre à jour le sessionStorage
                            sessionStorage.setItem('user', JSON.stringify(this.user));
                            
                            this.updateSuccess = true;
                            this.updateMessage = '✅ Paramètres mis à jour avec succès !';
                            this.editMode = false;
                        } else {
                            const error = await response.json();
                            this.updateSuccess = false;
                            this.updateMessage = '❌ ' + (error.error || 'Erreur lors de la mise à jour');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.updateSuccess = false;
                        this.updateMessage = '❌ Erreur de connexion au serveur';
                    } finally {
                        this.savingSettings = false;
                        
                        // Effacer le message après 5 secondes
                        setTimeout(() => {
                            this.updateMessage = '';
                        }, 5000);
                    }
                },

                logout() {
                    sessionStorage.removeItem('user');
                    window.location.href = 'index.php';
                }
            }
        }
    </script>
</body>
</html>
