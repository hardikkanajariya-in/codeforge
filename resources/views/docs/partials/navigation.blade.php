<div class="space-y-6">
    <!-- Getting Started Section -->
    <div>
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Getting Started</h3>
        <div class="space-y-1">
            <a href="{{ route('codeforge.docs.getting-started') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.getting-started') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🚀 Quick Start
            </a>
            <a href="{{ route('codeforge.docs.installation') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.installation') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                📦 Installation
            </a>
            <a href="{{ route('codeforge.docs.configuration') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.configuration') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                ⚙️ Configuration
            </a>
            <a href="{{ route('codeforge.docs.requirements') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.requirements') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                📋 Requirements
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <div>
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Core Features</h3>
        <div class="space-y-1">
            <a href="{{ route('codeforge.docs.features.overview') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.overview') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🎯 Features Overview
            </a>
            <a href="{{ route('codeforge.docs.features.database-health') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.database-health') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                💖 Database Health
            </a>
            <a href="{{ route('codeforge.docs.features.migration-management') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.migration-management') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🔄 Migration Management
            </a>
            <a href="{{ route('codeforge.docs.features.schema-designer') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.schema-designer') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🎨 Schema Designer
            </a>
            <a href="{{ route('codeforge.docs.features.code-generation') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.code-generation') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                ⚡ Code Generation
            </a>
            <a href="{{ route('codeforge.docs.features.documentation-generator') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.documentation-generator') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                📄 Documentation Generation
            </a>
            <a href="{{ route('codeforge.docs.features.data-seeding') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.features.data-seeding') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🌱 Data Seeding
            </a>
        </div>
    </div>

    <!-- Architecture Section -->
    <div>
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Architecture</h3>
        <div class="space-y-1">
            <a href="{{ route('codeforge.docs.architecture.overview') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.architecture.overview') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🏗️ Overview
            </a>
            <a href="{{ route('codeforge.docs.architecture.services') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.architecture.services') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🔧 Services
            </a>
            <a href="{{ route('codeforge.docs.architecture.events') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.architecture.events') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                📡 Events
            </a>
        </div>
    </div>

    <!-- API Reference Section -->
    <div>
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">API Reference</h3>
        <div class="space-y-1">
            <a href="{{ route('codeforge.docs.api.overview') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.api.overview') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                📚 API Overview
            </a>
            <a href="{{ route('codeforge.docs.api.services') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.api.services') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🛠️ Services API
            </a>
            <a href="{{ route('codeforge.docs.api.commands') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.api.commands') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                ⌨️ Artisan Commands
            </a>
        </div>
    </div>

    <!-- Support Section -->
    <div>
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Help & Support</h3>
        <div class="space-y-1">
            <a href="{{ route('codeforge.docs.troubleshooting') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.troubleshooting') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                🐛 Troubleshooting
            </a>
            <a href="{{ route('codeforge.docs.faq') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.faq') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                ❓ FAQ
            </a>
            <a href="{{ route('codeforge.docs.support') }}"
                class="flex items-center px-3 py-2 text-sm {{ request()->routeIs('codeforge.docs.support') ? 'text-primary-600 bg-primary-50 border-r-2 border-primary-600 font-medium' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }} rounded">
                💬 Get Support
            </a>
        </div>
    </div>
</div>
