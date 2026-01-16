<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - FideLink</title>
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
      x-data="adminDashboard()" 
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
                    Administration - <span class="text-primary-orange" x-text="user.name"></span> 🔐
                </h1>
                <p class="text-gray-600 text-lg">Tableau de bord administrateur FideLink</p>
            </div>

            <!-- Statistiques globales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Clients -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-white/80 mb-2">Clients</p>
                            <p class="text-3xl font-black" x-text="stats.customers"></p>
                        </div>
                        <div class="text-4xl">👥</div>
                    </div>
                </div>

                <!-- Commerçants -->
                <div class="bg-gradient-to-br from-primary-orange to-gold rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-white/80 mb-2">Commerçants</p>
                            <p class="text-3xl font-black" x-text="stats.merchants"></p>
                        </div>
                        <div class="text-4xl">🏪</div>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-white/80 mb-2">Transactions</p>
                            <p class="text-3xl font-black" x-text="stats.transactions"></p>
                        </div>
                        <div class="text-4xl">💳</div>
                    </div>
                </div>
            </div>

            <!-- Onglets -->
            <div class="mb-6">
                <div class="flex space-x-4 border-b border-gray-200">
                    <button @click="activeTab = 'customers'" 
                            :class="activeTab === 'customers' ? 'border-primary-orange text-primary-orange' : 'border-transparent text-gray-500'"
                            class="px-6 py-3 font-semibold border-b-2 transition-all">
                        Clients
                    </button>
                    <button @click="activeTab = 'merchants'" 
                            :class="activeTab === 'merchants' ? 'border-primary-orange text-primary-orange' : 'border-transparent text-gray-500'"
                            class="px-6 py-3 font-semibold border-b-2 transition-all">
                        Commerçants
                    </button>
                    <button @click="activeTab = 'transactions'" 
                            :class="activeTab === 'transactions' ? 'border-primary-orange text-primary-orange' : 'border-transparent text-gray-500'"
                            class="px-6 py-3 font-semibold border-b-2 transition-all">
                        Transactions
                    </button>
                </div>
            </div>

            <!-- Contenu des onglets -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Liste des clients -->
                <div x-show="activeTab === 'customers'">
                    <h2 class="text-2xl font-bold mb-6">Liste des clients</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Nom</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">FDL ID</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Points</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="customer in customers" :key="customer.id">
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4" x-text="customer.name"></td>
                                        <td class="py-3 px-4" x-text="customer.email"></td>
                                        <td class="py-3 px-4 font-mono" x-text="customer.fdl_id"></td>
                                        <td class="py-3 px-4 font-semibold text-primary-orange" x-text="customer.pointsBal"></td>
                                        <td class="py-3 px-4 text-sm text-gray-600" x-text="new Date(customer.createdAt).toLocaleDateString('fr-FR')"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Liste des commerçants -->
                <div x-show="activeTab === 'merchants'">
                    <h2 class="text-2xl font-bold mb-6">Liste des commerçants</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Nom</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Merchant ID</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Valeur point</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Ville</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="merchant in merchants" :key="merchant.id">
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4" x-text="merchant.name"></td>
                                        <td class="py-3 px-4" x-text="merchant.email"></td>
                                        <td class="py-3 px-4 font-mono" x-text="merchant.merchant_id"></td>
                                        <td class="py-3 px-4 font-semibold text-gold" x-text="merchant.pointVal + '€'"></td>
                                        <td class="py-3 px-4" x-text="merchant.loc ? merchant.loc.city : 'N/A'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Liste des transactions -->
                <div x-show="activeTab === 'transactions'">
                    <h2 class="text-2xl font-bold mb-6">Dernières transactions</h2>
                    <div class="space-y-4">
                        <template x-for="transaction in transactions" :key="transaction.id">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                                         :class="transaction.type === 'earn' || transaction.type === 1 ? 'bg-green-100' : 'bg-red-100'">
                                        <span x-text="transaction.type === 'earn' || transaction.type === 1 ? '💰' : '🎁'"></span>
                                    </div>
                                    <div>
                                        <p class="font-semibold">
                                            <span x-show="transaction.type === 'earn' || transaction.type === 1" class="text-primary-orange" x-text="transaction.merchant_name || transaction.merchant_id"></span>
                                            <span x-show="transaction.type === 0" class="text-blue-600" x-text="transaction.customer_name || transaction.customer_id"></span>
                                            <span> → </span>
                                            <span x-show="transaction.type === 'earn' || transaction.type === 1" class="text-blue-600" x-text="transaction.customer_name || transaction.customer_id"></span>
                                            <span x-show="transaction.type === 0" class="text-primary-orange" x-text="transaction.merchant_name || transaction.merchant_id"></span>
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

                <div x-show="loading" class="text-center py-12">
                    <svg class="animate-spin h-12 w-12 mx-auto text-primary-orange" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Bouton de déconnexion -->
            <div class="mt-8 text-center">
                <button @click="logout" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                    Se déconnecter
                </button>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        function adminDashboard() {
            return {
                menuOpen: false,
                scrolled: false,
                loading: false,
                user: {},
                activeTab: 'customers',
                stats: {
                    customers: 0,
                    merchants: 0,
                    transactions: 0
                },
                customers: [],
                merchants: [],
                transactions: [],

                init() {
                    // Vérifier si l'utilisateur est connecté
                    const userStr = sessionStorage.getItem('user');
                    if (!userStr) {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.user = JSON.parse(userStr);
                    
                    if (this.user.type !== 'admin') {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.loadData();
                },

                async loadData() {
                    this.loading = true;
                    try {
                        // Charger les clients
                        const customersRes = await fetch('http://localhost:8000/api/customers');
                        this.customers = await customersRes.json();
                        this.stats.customers = this.customers.length;
                        
                        // Charger les commerçants
                        const merchantsRes = await fetch('http://localhost:8000/api/merchants');
                        this.merchants = await merchantsRes.json();
                        this.stats.merchants = this.merchants.length;

                        // Charger les transactions
                        const transactionsRes = await fetch('http://localhost:8000/api/transactions');
                        this.transactions = await transactionsRes.json();
                        this.stats.transactions = this.transactions.length;
                    } catch (error) {
                        console.error('Erreur lors du chargement des données:', error);
                    } finally {
                        this.loading = false;
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
