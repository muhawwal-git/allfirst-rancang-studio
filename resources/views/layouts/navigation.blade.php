<!-- Main Sticky Header (fill: #FFFFFF, stroke: #E2E8F0) -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-colors">
    <div class="w-full px-4 sm:px-8 lg:px-14 xl:px-20">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-ars.png') }}" alt="Allfirst Rancang Studio" class="h-7 w-auto object-contain transition-transform group-hover:scale-105">
                <span class="text-lg font-extrabold font-heading text-[#0F172A] tracking-tight group-hover:text-[#D11F2E] transition-colors leading-none">
                    Allfirst Rancang Studio
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="{{ route('home') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('home') ? 'text-[#D11F2E] font-semibold' : 'text-[#334155] hover:text-[#0F172A] hover:bg-slate-50' }}">
                    Beranda
                </a>
                <a href="{{ route('kelas.index') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('kelas.*') ? 'text-[#D11F2E] font-semibold' : 'text-[#334155] hover:text-[#0F172A] hover:bg-slate-50' }}">
                    Kelas Online
                </a>
                <a href="{{ route('offline') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('offline') ? 'text-[#D11F2E] font-semibold' : 'text-[#334155] hover:text-[#0F172A] hover:bg-slate-50' }}">
                    Kelas Offline
                </a>
                <a href="{{ route('ebook') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('ebook') ? 'text-[#D11F2E] font-semibold' : 'text-[#334155] hover:text-[#0F172A] hover:bg-slate-50' }}">
                    E-Book
                </a>
                <a href="{{ route('faq') }}"
                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('faq') ? 'text-[#D11F2E] font-semibold' : 'text-[#334155] hover:text-[#0F172A] hover:bg-slate-50' }}">
                    FAQ
                </a>
            </nav>

            <!-- Authentication Actions -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ url('/admin/dashboard') }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-mono font-medium text-amber-800 bg-amber-50 border border-amber-200 hover:bg-amber-100 transition-colors">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Panel Admin
                        </a>
                    @else
                        <a href="{{ url('/student/dashboard') }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-mono font-medium text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#D11F2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Area Belajar
                        </a>
                    @endif

                    <div class="relative ml-1" id="userMenuDropdown">
                        <button type="button" onclick="toggleDropdown()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-sm font-medium text-slate-800 transition-colors">
                            <span class="w-7 h-7 rounded-md bg-[#FFF1F2] border border-[#FECDD3] text-[#D11F2E] flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="max-w-[120px] truncate text-xs font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="dropdownContent" class="hidden absolute right-0 mt-2 w-48 rounded-xl bg-white border border-slate-200 shadow-xl py-2 z-50 text-sm">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Masuk sebagai</p>
                                <p class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->isStudent())
                                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                                    Dashboard Belajar
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors">
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Masuk Button (fill: #FFFFFF, stroke: #E2E8F0, text: #0F172A) -->
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold text-[#0F172A] bg-white border border-[#E2E8F0] hover:bg-slate-50 transition-colors shadow-sm">
                        Masuk
                    </a>

                    <!-- Daftar Button (gradient: #D11F2E -> #9E111B, text: #FFFFFF) -->
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B] hover:brightness-105 border border-[#BA151C] shadow-sm transition-all transform hover:-translate-y-0.5">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center md:hidden">
                <button type="button"
                        onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none"
                        aria-label="Toggle Navigation">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2">
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'text-[#D11F2E] font-semibold bg-rose-50' : 'text-[#334155] hover:bg-slate-50' }}">
            Beranda
        </a>
        <a href="{{ route('kelas.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('kelas.*') ? 'text-[#D11F2E] font-semibold bg-rose-50' : 'text-[#334155] hover:bg-slate-50' }}">
            Kelas Online
        </a>
        <a href="{{ route('offline') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('offline') ? 'text-[#D11F2E] font-semibold bg-rose-50' : 'text-[#334155] hover:bg-slate-50' }}">
            Kelas Offline
        </a>
        <a href="{{ route('ebook') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('ebook') ? 'text-[#D11F2E] font-semibold bg-rose-50' : 'text-[#334155] hover:bg-slate-50' }}">
            E-Book
        </a>
        <a href="{{ route('faq') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('faq') ? 'text-[#D11F2E] font-semibold bg-rose-50' : 'text-[#334155] hover:bg-slate-50' }}">
            FAQ
        </a>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ url('/admin/dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-mono font-medium text-amber-800 bg-amber-50 border border-amber-200 text-center">
                        Panel Admin
                    </a>
                @else
                    <a href="{{ url('/student/dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-mono font-medium text-slate-700 bg-slate-100 text-center">
                        Area Belajar
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center px-3 py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-[#0F172A] bg-white border border-[#E2E8F0]">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-[#D11F2E] to-[#9E111B]">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</header>

<script>
    function toggleDropdown() {
        const dd = document.getElementById('dropdownContent');
        if (dd) {
            dd.classList.toggle('hidden');
        }
    }
    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const dd = document.getElementById('dropdownContent');
        const btn = document.getElementById('userMenuDropdown');
        if (dd && btn && !btn.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
</script>
