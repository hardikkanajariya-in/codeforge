<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CodeForge Database Studio Documentation')</title>
    <meta name="description"
        content="@yield('description', 'Comprehensive documentation for CodeForge Database Studio - Advanced Laravel database management and code generation suite.')">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f4f1ff',
                            100: '#ebe6ff',
                            200: '#d9d0ff',
                            300: '#bea9ff',
                            400: '#9d7aff',
                            500: '#7c3aed',
                            600: '#6d28d9',
                            700: '#5b21b6',
                            800: '#4c1d95',
                            900: '#3c1a78',
                            950: '#1e0a3c'
                        },
                        purple: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21b8',
                            900: '#581c87',
                            950: '#3b0764'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace']
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Heroicons -->
    <script src="https://unpkg.com/@heroicons/react@2.0.18/24/outline/index.js" type="module"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Prism.js for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth transitions */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Code blocks */
        pre[class*="language-"] {
            @apply rounded-lg border border-gray-200 !important;
        }

        /* Table of contents highlighting */
        .toc-link.active {
            @apply text-primary-600 font-semibold bg-primary-50 border-r-2 border-primary-600;
        }

        /* Search highlighting */
        .search-highlight {
            @apply bg-yellow-200 px-1 rounded;
        }

        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Mobile navigation */
        .mobile-menu-enter {
            transform: translateX(-100%);
        }

        .mobile-menu-enter-active {
            transform: translateX(0);
            transition: transform 0.3s ease-out;
        }

        /* Responsive sidebar */
        @media (max-width: 1024px) {
            .sidebar-overlay {
                @apply fixed inset-0 bg-black bg-opacity-50 z-40;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen" x-data="{ 
        sidebarOpen: false, 
        searchOpen: false, 
        searchQuery: '',
        searchResults: [],
        currentSection: '',
        tableOfContents: []
    }" x-init="
        // Initialize table of contents
        tableOfContents = Array.from(document.querySelectorAll('h2, h3, h4')).map(el => ({
            id: el.id,
            text: el.textContent,
            level: parseInt(el.tagName.charAt(1))
        }));
        
        // Handle scroll for active section
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    currentSection = entry.target.id;
                }
            });
        }, { rootMargin: '-20% 0px -35% 0px' });
        
        document.querySelectorAll('h2, h3, h4').forEach(el => {
            if (el.id) observer.observe(el);
        });
    ">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-80 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">CodeForge</h1>
                        <p class="text-xs text-gray-500">Database Studio</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Search -->
            <div class="p-4 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="performSearch()"
                        placeholder="Search documentation..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Search Results -->
                <div x-show="searchQuery && searchResults.length > 0" x-transition
                    class="absolute left-4 right-4 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
                    <template x-for="result in searchResults" :key="result.url">
                        <a :href="result.url"
                            class="block p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                            <h4 class="font-medium text-gray-900" x-text="result.title"></h4>
                            <p class="text-sm text-gray-600 mt-1" x-text="result.excerpt"></p>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto">
                <div class="px-4 py-4 space-y-1">
                    @yield('navigation')
                </div>
            </nav>

            <!-- Footer -->
            <div class="border-t border-gray-200 p-4">
                <div class="text-xs text-gray-500">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="font-medium">Version:</span>
                        <span class="px-2 py-1 bg-primary-100 text-primary-800 rounded-full">v1.0</span>
                    </div>
                    <div class="space-y-1">
                        <p>&copy; {{ date('Y') }} <a href="https://hardikkanajariya.in"
                                class="text-primary-600 hover:text-primary-700">Hardik Kanajariya</a></p>
                        <p><a href="https://github.com/hardikkanajariya-in/codeforge" class="text-primary-600 hover:text-primary-700">GitHub</a> · MIT License</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Breadcrumbs -->
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle (Future) -->
                    <div class="hidden">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- External Links -->
                    <a href="https://github.com/hardikkanajariya-in/codeforge" target="_blank"
                        class="text-sm text-gray-500 hover:text-primary-600 flex items-center space-x-1">
                        <span>GitHub</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </header>

            <div class="flex flex-1 min-h-0">
                <!-- Content Area -->
                <main class="flex-1 overflow-y-auto">
                    <div class="max-w-4xl mx-auto px-6 py-8">
                        @yield('content')
                    </div>
                </main>

                <!-- Table of Contents Sidebar -->
                <aside class="hidden xl:block w-64 border-l border-gray-200 bg-white overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">On this page</h3>
                        <nav class="space-y-1" x-show="tableOfContents.length > 0">
                            <template x-for="item in tableOfContents" :key="item.id">
                                <a :href="'#' + item.id" :class="{
                                       'toc-link': true,
                                       'active': currentSection === item.id,
                                       'pl-0': item.level === 2,
                                       'pl-4': item.level === 3,
                                       'pl-8': item.level === 4
                                   }"
                                    class="block py-1 px-2 text-sm text-gray-600 hover:text-primary-600 rounded transition-colors duration-150"
                                    x-text="item.text">
                                </a>
                            </template>
                        </nav>

                        <!-- Quick Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h4>
                            <div class="space-y-2">
                                <a href="{{ route('codeforge.docs.getting-started') }}"
                                    class="block text-sm text-gray-600 hover:text-primary-600">
                                    🚀 Getting Started
                                </a>
                                <a href="{{ route('codeforge.docs.installation') }}"
                                    class="block text-sm text-gray-600 hover:text-primary-600">
                                    📦 Installation Guide
                                </a>
                                {{-- <a href="{{ route('codeforge.docs.api-reference') }}"
                                    class="block text-sm text-gray-600 hover:text-primary-600">
                                    📚 API Reference
                                </a> --}}
                                <a href="mailto:contact@hardikkanajariya.in"
                                    class="block text-sm text-gray-600 hover:text-primary-600">
                                    💬 Get Support
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- JavaScript for Search and Navigation -->
    <script>
        function performSearch() {
            // This would typically make an AJAX request to a search endpoint
            // For now, we'll implement a simple client-side search
            const query = this.searchQuery.toLowerCase();
            if (query.length < 2) {
                this.searchResults = [];
                return;
            }

            // Mock search results - replace with actual search implementation
            this.searchResults = [
                {
                    title: 'Getting Started',
                    excerpt: 'Learn how to install and configure CodeForge Database Studio',
                    url: '{{ route("codeforge.docs.getting-started") }}'
                },
                {
                    title: 'Installation Guide',
                    excerpt: 'Step-by-step installation instructions',
                    url: '{{ route("codeforge.docs.installation") }}'
                }
            ].filter(item =>
                item.title.toLowerCase().includes(query) ||
                item.excerpt.toLowerCase().includes(query)
            );
        }

        // Smooth scroll for anchor links
        document.addEventListener('click', function (e) {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="text"]');
                if (searchInput) searchInput.focus();
            }

            // Escape to close mobile sidebar
            if (e.key === 'Escape') {
                Alpine.store('sidebar', false);
            }
        });
    </script>
</body>

</html>
