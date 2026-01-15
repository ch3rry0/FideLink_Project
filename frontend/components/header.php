<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md transition-all duration-300" :class="scrolled ? 'h-16' : 'h-[90px]'">
    <div class="h-full flex items-center justify-center px-8">
        <div class="flex items-center justify-center">
            <a href="/index.html">
                <img src="/img/FDL_logo.svg" alt="FideLink Logo" class="transition-all duration-300" :class="scrolled ? 'h-[90px]' : 'h-[140px]'">
            </a>
        </div>
        <button @click="menuOpen = !menuOpen" class="fixed w-12 h-12 bg-black rounded-lg flex flex-col items-center justify-center gap-1.5 z-50 transition-all duration-300 hover:bg-primary-orange" :class="{ 'right-4': true, 'md:right-[280px] lg:right-[350px]': menuOpen, 'top-4': !scrolled, 'top-2': scrolled }" aria-label="Menu">
            <span class="w-6 h-0.5 bg-white rounded transition-all duration-300" :class="menuOpen ? 'rotate-45 translate-y-2' : ''"></span>
            <span class="w-6 h-0.5 bg-white rounded transition-all duration-300" :class="menuOpen ? 'opacity-0' : ''"></span>
            <span class="w-6 h-0.5 bg-white rounded transition-all duration-300" :class="menuOpen ? '-rotate-45 -translate-y-2' : ''"></span>
        </button>
    </div>
</nav>
