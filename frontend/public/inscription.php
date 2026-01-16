<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FideLink</title>
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
      x-data="registrationForm()" 
      x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })" 
      @keydown.escape="menuOpen = false">
    <!-- Contenu principal -->
    <main class="pt-32 pb-16 px-4 sm:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Logo centré -->
            <div class="text-center mb-12">
                <a href="index.php"><img src="img/FDL_logo.svg" alt="FideLink Logo" class="h-24 mx-auto mb-6"></a>
                <h1 class="text-4xl font-black text-gray-900 mb-3">
                    Rejoignez <span class="text-primary-orange">FideLink</span>
                </h1>
                <p class="text-gray-600 text-lg">Créez votre compte en quelques étapes</p>
            </div>

            <!-- Conteneur du formulaire -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-12">
                <!-- Barre de progression -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-gray-700">Étape <span x-text="currentStep"></span> sur <span x-text="totalSteps"></span></span>
                        <span class="text-sm text-gray-500" x-text="Math.round((currentStep / totalSteps) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primary-orange to-gold h-2 rounded-full transition-all duration-500" 
                             :style="`width: ${(currentStep / totalSteps) * 100}%`"></div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="handleSubmit">
                    
                    <!-- Étape 1: Type de compte -->
                    <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900">Quel type de compte souhaitez-vous créer ?</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button type="button" @click="selectAccountType('customer')" 
                                    class="p-8 border-2 rounded-xl transition-all duration-300 hover:shadow-xl"
                                    :class="formData.accountType === 'customer' ? 'border-primary-orange bg-primary-orange/5' : 'border-gray-200 hover:border-primary-orange'">
                                <div class="text-5xl mb-4">👤</div>
                                <h3 class="text-xl font-bold mb-2">Client</h3>
                                <p class="text-gray-600 text-sm">Pour accumuler et utiliser des points fidélité</p>
                            </button>
                            <button type="button" @click="selectAccountType('merchant')" 
                                    class="p-8 border-2 rounded-xl transition-all duration-300 hover:shadow-xl"
                                    :class="formData.accountType === 'merchant' ? 'border-primary-orange bg-primary-orange/5' : 'border-gray-200 hover:border-primary-orange'">
                                <div class="text-5xl mb-4">🏪</div>
                                <h3 class="text-xl font-bold mb-2">Commerçant</h3>
                                <p class="text-gray-600 text-sm">Pour offrir des récompenses à vos clients</p>
                            </button>
                        </div>
                    </div>

                    <!-- Étapes pour CLIENT -->
                    <template x-if="formData.accountType === 'customer'">
                        <div>
                            <!-- Étape 2: Nom et Prénom -->
                            <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Comment vous appelez-vous ?</h2>
                                <input type="text" x-model="formData.name" placeholder="Nom et Prénom" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <!-- Étape 3: Âge -->
                            <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Quel âge avez-vous ?</h2>
                                <input type="number" x-model.number="formData.age" placeholder="Âge" min="13" max="120"
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <!-- Étape 4: Email -->
                            <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Quelle est votre adresse email ?</h2>
                                <input type="email" x-model="formData.email" placeholder="exemple@email.com" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <!-- Étape 5: FideLink ID -->
                            <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Choisissez votre identifiant FideLink</h2>
                                <input type="text" x-model="formData.fdl_id" placeholder="FDL001" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                                <p class="text-sm text-gray-500 mt-2">Cet identifiant sera utilisé pour vos transactions</p>
                            </div>

                            <!-- Étape 6: Mot de passe -->
                            <div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Créez un mot de passe sécurisé</h2>
                                <input type="password" x-model="formData.password" placeholder="Mot de passe" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300 mb-4"
                                       required minlength="8">
                                <input type="password" x-model="formData.passwordConfirm" placeholder="Confirmez le mot de passe" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                                <p class="text-sm text-gray-500 mt-2">Le mot de passe doit contenir au moins 8 caractères</p>
                            </div>
                        </div>
                    </template>

                    <!-- Étapes pour COMMERÇANT -->
                    <template x-if="formData.accountType === 'merchant'">
                        <div>
                            <!-- Étape 2: Nom du commerce -->
                            <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Quel est le nom de votre commerce ?</h2>
                                <input type="text" x-model="formData.name" placeholder="Nom du commerce" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <!-- Étape 3: Merchant ID -->
                            <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Choisissez votre identifiant commerçant</h2>
                                <input type="text" x-model="formData.merchant_id" placeholder="MRC001" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                                <p class="text-sm text-gray-500 mt-2">Identifiant unique pour votre commerce</p>
                            </div>

                            <!-- Étape 4: Email -->
                            <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Quelle est votre adresse email professionnelle ?</h2>
                                <input type="email" x-model="formData.email" placeholder="contact@commerce.fr" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                            </div>

                            <!-- Étape 5: Mot de passe -->
                            <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Créez un mot de passe sécurisé</h2>
                                <input type="password" x-model="formData.password" placeholder="Mot de passe" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300 mb-4"
                                       required minlength="8">
                                <input type="password" x-model="formData.passwordConfirm" placeholder="Confirmez le mot de passe" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                       required>
                                <p class="text-sm text-gray-500 mt-2">Le mot de passe doit contenir au moins 8 caractères</p>
                            </div>

                            <!-- Étape 6: Adresse -->
                            <div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Où se situe votre commerce ?</h2>
                                <input type="text" x-model="formData.address" placeholder="Numéro et nom de rue" 
                                       class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300 mb-4"
                                       required>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" x-model="formData.zip" placeholder="Code postal" 
                                           class="px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                           required>
                                    <input type="text" x-model="formData.city" placeholder="Ville" 
                                           class="px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300"
                                           required>
                                </div>
                            </div>

                            <!-- Étape 7: Biographie -->
                            <div x-show="currentStep === 7" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0">
                                <h2 class="text-2xl font-bold mb-6 text-gray-900">Présentez votre commerce</h2>
                                <textarea x-model="formData.bio" placeholder="Décrivez votre activité, vos spécialités..." 
                                          rows="6"
                                          class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-primary-orange focus:outline-none transition-all duration-300 resize-none"
                                          required></textarea>
                                <p class="text-sm text-gray-500 mt-2">Cette description sera visible par vos clients</p>
                            </div>
                        </div>
                    </template>

                    <!-- Message d'erreur -->
                    <div x-show="errorMessage" x-transition class="mt-4 p-4 bg-red-50 border-2 border-red-200 rounded-xl">
                        <p class="text-red-600 font-semibold" x-text="errorMessage"></p>
                    </div>

                    <!-- Message de succès -->
                    <div x-show="successMessage" x-transition class="mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-xl">
                        <p class="text-green-600 font-semibold" x-text="successMessage"></p>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="flex gap-4 mt-8">
                        <button type="button" @click="previousStep" 
                                x-show="currentStep > 1 && !loading"
                                class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                            ← Retour
                        </button>
                        
                        <button type="button" @click="nextStep" 
                                x-show="currentStep < totalSteps && !loading"
                                :disabled="!canProceed()"
                                :class="canProceed() ? 'bg-primary-orange hover:bg-secondary-orange' : 'bg-gray-300 cursor-not-allowed'"
                                class="flex-1 px-6 py-4 text-white rounded-xl font-semibold transition-all duration-300">
                            Suivant →
                        </button>

                        <button type="submit" 
                                x-show="currentStep === totalSteps && !loading"
                                :disabled="!canSubmit()"
                                :class="canSubmit() ? 'bg-gradient-to-r from-primary-orange to-gold hover:shadow-xl' : 'bg-gray-300 cursor-not-allowed'"
                                class="flex-1 px-6 py-4 text-white rounded-xl font-bold transition-all duration-300">
                            S'inscrire
                        </button>

                        <button type="button" disabled
                                x-show="loading"
                                class="flex-1 px-6 py-4 bg-gray-300 text-gray-500 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Inscription en cours...
                        </button>
                    </div>
                </form>

                <!-- Lien vers connexion -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        Vous avez déjà un compte ?
                        <a href="connexion.php" class="text-primary-orange font-semibold hover:underline">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function registrationForm() {
            return {
                menuOpen: false,
                scrolled: false,
                currentStep: 1,
                loading: false,
                errorMessage: '',
                successMessage: '',
                formData: {
                    accountType: '',
                    name: '',
                    age: '',
                    email: '',
                    password: '',
                    passwordConfirm: '',
                    fdl_id: '',
                    merchant_id: '',
                    address: '',
                    zip: '',
                    city: '',
                    bio: ''
                },
                
                get totalSteps() {
                    if (this.formData.accountType === 'customer') return 6;
                    if (this.formData.accountType === 'merchant') return 7;
                    return 1;
                },

                selectAccountType(type) {
                    this.formData.accountType = type;
                    this.nextStep();
                },

                canProceed() {
                    const step = this.currentStep;
                    const data = this.formData;
                    
                    if (step === 1) return data.accountType !== '';
                    
                    if (data.accountType === 'customer') {
                        if (step === 2) return data.name.trim() !== '';
                        if (step === 3) return data.age >= 13;
                        if (step === 4) return this.isValidEmail(data.email);
                        if (step === 5) return data.fdl_id.trim() !== '';
                    }
                    
                    if (data.accountType === 'merchant') {
                        if (step === 2) return data.name.trim() !== '';
                        if (step === 3) return data.merchant_id.trim() !== '';
                        if (step === 4) return this.isValidEmail(data.email);
                        if (step === 6) return data.address.trim() !== '' && data.zip.trim() !== '' && data.city.trim() !== '';
                    }
                    
                    return true;
                },

                canSubmit() {
                    const data = this.formData;
                    if (data.password.length < 8) return false;
                    if (data.password !== data.passwordConfirm) return false;
                    return this.canProceed();
                },

                isValidEmail(email) {
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                },

                nextStep() {
                    if (this.canProceed() && this.currentStep < this.totalSteps) {
                        this.currentStep++;
                        this.errorMessage = '';
                    }
                },

                previousStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        this.errorMessage = '';
                    }
                },

                async handleSubmit() {
                    console.log('handleSubmit appelé');
                    console.log('canSubmit:', this.canSubmit());
                    console.log('formData:', this.formData);
                    
                    if (!this.canSubmit()) {
                        this.errorMessage = 'Veuillez vérifier tous les champs';
                        console.log('Validation échouée');
                        return;
                    }

                    if (this.formData.password !== this.formData.passwordConfirm) {
                        this.errorMessage = 'Les mots de passe ne correspondent pas';
                        return;
                    }

                    this.loading = true;
                    this.errorMessage = '';
                    console.log('Début de la requête API...');

                    try {
                        const endpoint = this.formData.accountType === 'customer' 
                            ? 'http://localhost:8000/api/customers'
                            : 'http://localhost:8000/api/merchants';

                        let payload = {
                            name: this.formData.name,
                            email: this.formData.email,
                            password: this.formData.password
                        };

                        if (this.formData.accountType === 'customer') {
                            payload.fdl_id = this.formData.fdl_id;
                            payload.age = this.formData.age;
                        } else {
                            payload.merchant_id = this.formData.merchant_id;
                            payload.loc = {
                                address: this.formData.address,
                                zip: this.formData.zip,
                                city: this.formData.city
                            };
                            payload.bio = this.formData.bio;
                        }

                        console.log('Endpoint:', endpoint);
                        console.log('Payload:', payload);

                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        console.log('Response status:', response.status);
                        const result = await response.json();
                        console.log('Response result:', result);

                        if (response.ok) {
                            this.successMessage = '✅ Inscription réussie ! Redirection vers l\'accueil...';
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 3000);
                        } else {
                            this.errorMessage = result.error || 'Une erreur est survenue';
                        }
                    } catch (error) {
                        console.error('Erreur complète:', error);
                        this.errorMessage = 'Erreur de connexion au serveur: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
