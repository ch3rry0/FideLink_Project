<!-- Menu latéral -->
<div class="fixed top-0 w-full md:w-[350px] h-screen bg-gradient-to-br from-black to-gray-900 z-40 p-6 md:p-10 pt-24 md:pt-28 flex flex-col justify-between transition-all duration-400" 
     :class="menuOpen ? 'right-0' : '-right-full md:-right-[350px]'"
     x-data="{ user: null }" 
     x-init="user = JSON.parse(sessionStorage.getItem('user') || 'null')">
    <ul class="space-y-6">
        <li><a @click="menuOpen = false" href="/index.html#accueil" class="text-white text-xl md:text-2xl font-medium hover:text-primary-orange transition-all duration-300 inline-block hover:translate-x-3">Accueil</a></li>
        <li><a @click="menuOpen = false" href="/index.html#a-propos" class="text-white text-xl md:text-2xl font-medium hover:text-primary-orange transition-all duration-300 inline-block hover:translate-x-3">À propos</a></li>
        <li><a @click="menuOpen = false" href="/index.html#comment-ca-marche" class="text-white text-xl md:text-2xl font-medium hover:text-primary-orange transition-all duration-300 inline-block hover:translate-x-3">Comment ça marche</a></li>
        <li><a @click="menuOpen = false" href="/index.html#commercants" class="text-white text-xl md:text-2xl font-medium hover:text-primary-orange transition-all duration-300 inline-block hover:translate-x-3">Commerçants partenaires</a></li>
        <li><a @click="menuOpen = false" href="/index.html#contact" class="text-white text-xl md:text-2xl font-medium hover:text-primary-orange transition-all duration-300 inline-block hover:translate-x-3">Contact</a></li>
    </ul>
    
    <!-- Boutons selon état de connexion -->
    <div class="flex flex-col gap-4 mt-8">
        <!-- Si utilisateur connecté -->
        <template x-if="user">
            <div class="space-y-4">
                <div class="px-6 py-4 bg-gradient-to-r from-primary-orange/20 to-gold/20 rounded-lg border-2 border-primary-orange">
                    <p class="text-white/80 text-sm mb-1">Connecté en tant que</p>
                    <p class="text-white font-bold text-lg" x-text="user.name"></p>
                    <p class="text-primary-orange text-sm" x-text="user.type === 'customer' ? 'Client' : user.type === 'merchant' ? 'Commerçant' : 'Administrateur'"></p>
                </div>
                <a :href="user.type === 'customer' ? 'customer-dashboard.php' : user.type === 'merchant' ? 'merchant-dashboard.php' : user.type === 'admin' ? 'admin-dashboard.php' : '#'" 
                   class="px-6 py-4 text-center bg-gradient-to-r from-primary-orange to-gold text-white rounded-lg font-semibold hover:shadow-xl transition-all duration-300 hover:-translate-y-1 block">
                    Mon espace <span x-text="user.type === 'customer' ? '👤' : user.type === 'merchant' ? '🏪' : '🔐'"></span>
                </a>
                <button @click="sessionStorage.removeItem('user'); window.location.reload();" 
                        class="px-6 py-4 text-center bg-transparent text-white border-2 border-red-500 rounded-lg font-semibold hover:bg-red-500 transition-all duration-300 hover:-translate-y-1 w-full">
                    Se déconnecter
                </button>
            </div>
        </template>
        
        <!-- Si utilisateur non connecté -->
        <template x-if="!user">
            <div class="space-y-4">
                <a href="/inscription.php" class="px-6 py-4 text-center bg-primary-orange text-white rounded-lg font-semibold hover:bg-secondary-orange transition-all duration-300 hover:-translate-y-1 block">S'inscrire</a>
                <a href="/connexion.php" class="px-6 py-4 text-center bg-transparent text-white border-2 border-primary-orange rounded-lg font-semibold hover:bg-primary-orange transition-all duration-300 hover:-translate-y-1 block">Se connecter</a>
            </div>
        </template>
    </div>
</div>

<!-- Overlay pour fermer le menu -->
<div @click="menuOpen = false" class="fixed inset-0 bg-black bg-opacity-70 z-30 transition-opacity duration-300" :class="menuOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"></div>
