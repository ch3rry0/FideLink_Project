<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FideLink</title>
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
      x-data="loginForm()" 
      x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })" 
      @keydown.escape="menuOpen = false">
    
    <?php include '../components/header.php'; ?>
    <?php include '../components/menu.php'; ?>

    <!-- Contenu principal -->
    <main class="pt-32 pb-16 px-4 sm:px-8 flex items-center justify-center min-h-screen">
        <div class="max-w-md w-full">
            <!-- Logo et titre -->
            <div class="text-center mb-12">
                <a href="index.php"><img src="img/FDL_logo.svg" alt="FideLink Logo" class="h-24 mx-auto mb-6"></a>
                <h1 class="text-4xl font-black text-gray-900 mb-3">
                    Connexion à <span class="text-primary-orange">FideLink</span>
                </h1>
                <p class="text-gray-600 text-lg">Accédez à votre espace</p>
            </div>

            <!-- Formulaire de connexion -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-12">
                <form @submit.prevent="handleLogin">
                    <!-- Identifiant -->
                    <div class="mb-6">
                        <label for="identifier" class="block text-sm font-semibold text-gray-700 mb-2">Email ou Identifiant FideLink</label>
                        <input type="text" id="identifier" x-model="formData.identifier" 
                               placeholder="exemple@email.com ou FDL001"
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                               required>
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" id="password" x-model="formData.password" 
                               placeholder="Votre mot de passe"
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                               required>
                    </div>

                    <!-- Message d'erreur -->
                    <div x-show="errorMessage" x-transition class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-xl">
                        <p class="text-red-600 font-semibold" x-text="errorMessage"></p>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" 
                            :disabled="loading"
                            :class="loading ? 'bg-gray-300 cursor-not-allowed' : 'bg-gradient-to-r from-primary-orange to-gold hover:shadow-xl'"
                            class="w-full px-6 py-4 text-white rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center">
                        <span x-show="!loading">Se connecter</span>
                        <span x-show="loading" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Connexion en cours...
                        </span>
                    </button>
                </form>

                <!-- Lien vers inscription -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Vous n'avez pas encore de compte ?
                        <a href="inscription.php" class="text-primary-orange font-semibold hover:underline">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        function loginForm() {
            return {
                menuOpen: false,
                scrolled: false,
                loading: false,
                errorMessage: '',
                formData: {
                    identifier: '',
                    password: ''
                },

                async handleLogin() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('http://localhost:8000/api/auth/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            // Stocker les données utilisateur dans sessionStorage
                            sessionStorage.setItem('user', JSON.stringify(result.user));
                            
                            // Rediriger selon le type d'utilisateur
                            switch(result.user.type) {
                                case 'customer':
                                    window.location.href = 'customer-dashboard.php';
                                    break;
                                case 'merchant':
                                    window.location.href = 'merchant-dashboard.php';
                                    break;
                                case 'admin':
                                    window.location.href = 'admin-dashboard.php';
                                    break;
                            }
                        } else {
                            this.errorMessage = result.error || 'Erreur de connexion';
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.errorMessage = 'Erreur de connexion au serveur';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
