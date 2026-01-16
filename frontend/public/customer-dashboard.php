<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client - FideLink</title>
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
      x-data="customerDashboard()" 
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
                    Bonjour, <span class="text-primary-orange" x-text="user.name"></span> 👋
                </h1>
                <p class="text-gray-600 text-lg">Gérez vos points de fidélité FideLink</p>
            </div>

            <!-- Carte Solde -->
            <div class="bg-gradient-to-br from-primary-orange to-gold rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-lg mb-2">Votre solde FideLink</p>
                        <h2 class="text-5xl font-black mb-4" x-text="user.pointsBal + ' FDL'"></h2>
                        <p class="text-white/90">ID: <span class="font-mono font-bold" x-text="user.fdl_id"></span></p>
                    </div>
                </div>
            </div>

            <!-- Barre de recherche -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-2xl font-bold mb-4">Trouver un commerçant</h3>
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery" 
                           @input="searchMerchants"
                           placeholder="Rechercher un commerçant par nom ou ville..."
                           class="w-full px-6 py-4 pl-12 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300">
                    <svg class="absolute left-4 top-5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Liste des commerçants -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="merchant in filteredMerchants" :key="merchant.id">
                    <div @click="viewMerchant(merchant)" 
                         class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="merchant.name"></h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p x-show="merchant.loc">
                                            📍 <span x-text="merchant.loc ? merchant.loc.city : ''"></span>
                                        </p>
                                        <p class="text-primary-orange font-semibold">
                                            💰 1 point = <span x-text="merchant.pointVal + '€'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-4xl">🏪</div>
                            </div>
                            <p class="text-gray-600 text-sm line-clamp-2" x-text="merchant.bio || 'Aucune description'"></p>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-sm font-semibold text-gray-700">
                                    Vos <span x-text="user.pointsBal"></span> FDL = 
                                    <span class="text-primary-orange" x-text="calculateValue(merchant.pointVal)"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="filteredMerchants.length === 0 && !loading" class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Aucun commerçant trouvé</p>
                </div>

                <div x-show="loading" class="col-span-full text-center py-12">
                    <svg class="animate-spin h-12 w-12 mx-auto text-primary-orange" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Bouton de déconnexion -->
            <div class="mt-12 text-center">
                <button @click="logout" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Se déconnecter
                </button>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        function customerDashboard() {
            return {
                menuOpen: false,
                scrolled: false,
                loading: false,
                user: {},
                merchants: [],
                filteredMerchants: [],
                searchQuery: '',

                init() {
                    // Vérifier si l'utilisateur est connecté
                    const userStr = sessionStorage.getItem('user');
                    if (!userStr) {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.user = JSON.parse(userStr);
                    
                    if (this.user.type !== 'customer') {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.loadMerchants();
                },

                async loadMerchants() {
                    this.loading = true;
                    try {
                        const response = await fetch('http://localhost:8000/api/merchants');
                        const merchants = await response.json();
                        this.merchants = merchants;
                        this.filteredMerchants = merchants;
                    } catch (error) {
                        console.error('Erreur lors du chargement des commerçants:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                searchMerchants() {
                    const query = this.searchQuery.toLowerCase();
                    this.filteredMerchants = this.merchants.filter(merchant => {
                        return merchant.name.toLowerCase().includes(query) ||
                               (merchant.loc && merchant.loc.city.toLowerCase().includes(query));
                    });
                },

                calculateValue(pointVal) {
                    const value = (this.user.pointsBal * pointVal).toFixed(2);
                    return value + '€';
                },

                viewMerchant(merchant) {
                    // Stocker les infos du merchant pour la page de détail
                    sessionStorage.setItem('selectedMerchant', JSON.stringify(merchant));
                    window.location.href = 'merchant-detail.php';
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
