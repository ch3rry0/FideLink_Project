<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Commerçant - FideLink</title>
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
      x-data="merchantDetail()" 
      x-init="init()"
      @keydown.escape="menuOpen = false">
    
    <?php include '../components/header.php'; ?>
    <?php include '../components/menu.php'; ?>

    <!-- Contenu principal -->
    <main class="pt-32 pb-16 px-4 sm:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Bouton retour -->
            <a href="customer-dashboard.php" class="inline-flex items-center text-primary-orange font-semibold mb-8 hover:underline">
                ← Retour à mes commerçants
            </a>

            <!-- Carte du commerçant -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-8">
                <div class="bg-gradient-to-br from-primary-orange to-gold p-8 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-4xl font-black mb-4" x-text="merchant.name"></h1>
                            <p x-show="merchant.loc" class="text-white/90 text-lg mb-2">
                                📍 <span x-text="merchant.loc ? `${merchant.loc.address}, ${merchant.loc.zip} ${merchant.loc.city}` : ''"></span>
                            </p>
                            <p class="text-white/90">ID: <span class="font-mono font-bold" x-text="merchant.merchant_id"></span></p>
                        </div>
                        <div class="text-6xl">🏪</div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Description -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-4">À propos</h2>
                        <p class="text-gray-700 text-lg" x-text="merchant.bio || 'Aucune description disponible'"></p>
                    </div>

                    <!-- Valeur des points -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                        <h2 class="text-2xl font-bold mb-4">Valeur de vos points</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6">
                                <p class="text-gray-600 mb-2">Votre solde FideLink</p>
                                <p class="text-3xl font-black text-primary-orange" x-text="user.pointsBal + ' FDL'"></p>
                            </div>
                            <div class="bg-white rounded-xl p-6">
                                <p class="text-gray-600 mb-2">Valeur chez ce commerçant</p>
                                <p class="text-3xl font-black text-gold" x-text="totalValue"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Taux de conversion -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold mb-4">Taux de conversion</h3>
                        <div class="bg-gradient-to-r from-primary-orange/10 to-gold/10 rounded-xl p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 mb-1">1 point FDL =</p>
                                    <p class="text-2xl font-bold text-primary-orange" x-text="merchant.pointVal + '€'"></p>
                                </div>
                                <div class="text-4xl">💰</div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4" x-show="merchant.miniThresh">
                            ⚠️ Montant minimum pour gagner des points : <span class="font-semibold" x-text="merchant.miniThresh + '€'"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        function merchantDetail() {
            return {
                menuOpen: false,
                scrolled: false,
                user: {},
                merchant: {},
                totalValue: '0€',

                init() {
                    // Vérifier si l'utilisateur est connecté
                    const userStr = sessionStorage.getItem('user');
                    if (!userStr) {
                        window.location.href = 'connexion.php';
                        return;
                    }

                    this.user = JSON.parse(userStr);

                    // Récupérer les infos du merchant
                    const merchantStr = sessionStorage.getItem('selectedMerchant');
                    if (!merchantStr) {
                        window.location.href = 'customer-dashboard.php';
                        return;
                    }

                    this.merchant = JSON.parse(merchantStr);
                    this.calculateTotal();
                },

                calculateTotal() {
                    const value = (this.user.pointsBal * this.merchant.pointVal).toFixed(2);
                    this.totalValue = value + '€';
                }
            }
        }
    </script>
</body>
</html>
