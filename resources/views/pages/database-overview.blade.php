<x-filament-panels::page>
    <div
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: -24px -24px 24px -24px; padding: 20px; border-radius: 0 0 20px 20px; position: relative; overflow: hidden;">
        <!-- Animated background elements -->
        <div
            style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><defs><pattern id=%22grid%22 width=%2210%22 height=%2210%22 patternUnits=%22userSpaceOnUse%22><path d=%22M 10 0 L 0 0 0 10%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-width=%220.5%22 opacity=%220.1%22/></pattern></defs><rect width=%22100%22 height=%22100%22 fill=%22url(%23grid)%22/></svg>'); opacity: 0.3; animation: float 20s infinite linear;">
        </div>

        <div style="position: relative; z-index: 1;">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="main-title">🗄️ Database Studio</h1>
                    @php
                        $healthService = app(\HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class);
                        $defaultConnection = config('database.default');
                        $connectionStatus = $healthService->testConnection($defaultConnection);
                        $performanceMetrics = $healthService->getPerformanceMetrics($defaultConnection);

                        // Get basic database stats
                        $totalTables = 0;
                        $totalConnections = count(array_keys(config('database.connections', [])));
                        try {
                            $tables = DB::select('SHOW TABLES');
                            $totalTables = count($tables);
                        } catch (\Exception $e) {
                            // Handle error gracefully
                        }

                        // Determine system status
                        $systemStatus = $connectionStatus['status'] === 'connected' ? 'Online' : 'Issues Detected';
                        $statusColor = $connectionStatus['status'] === 'connected' ? '#10b981' : '#ef4444';
                        $responseTime = $connectionStatus['response_time'] ?? 0;
                    @endphp
                    <p class="main-subtitle">
                        <span style="color: {{ $statusColor }}; font-weight: 600;">{{ $systemStatus }}</span> •
                        {{ $totalTables }} Tables • {{ $totalConnections }} Connections •
                        @if($responseTime > 0)
                            {{ number_format($responseTime, 1) }}ms Response
                        @else
                            No Response Data
                        @endif
                    </p>
                    <div class="badge-container">
                        <span class="feature-badge"
                            style="background: {{ $connectionStatus['status'] === 'connected' ? 'rgba(16, 185, 129, 0.8)' : 'rgba(239, 68, 68, 0.8)' }};">
                            {{ $connectionStatus['status'] === 'connected' ? '🟢' : '�' }}
                            {{ ucfirst($connectionStatus['status']) }}
                        </span>
                        @if(isset($performanceMetrics['query_performance']['total_queries']) && $performanceMetrics['query_performance']['total_queries'] > 0)
                            <span class="feature-badge">⚡
                                {{ number_format($performanceMetrics['query_performance']['total_queries']) }} Queries
                                (24h)</span>
                        @else
                            <span class="feature-badge">⚡ Query Monitoring</span>
                        @endif
                        @if(isset($performanceMetrics['query_performance']['avg_execution_time']) && $performanceMetrics['query_performance']['avg_execution_time'] > 0)
                            <span class="feature-badge">📊
                                {{ number_format($performanceMetrics['query_performance']['avg_execution_time'], 1) }}ms
                                Avg</span>
                        @else
                            <span class="feature-badge">🛠️ Smart Tools</span>
                        @endif
                    </div>
                </div>

                <div class="header-actions">
                    @php
                        $healthRouteExists = false;
                        try {
                            $healthRouteExists = Route::has('filament.admin.pages.database-health-dashboard');
                        } catch (\Exception $e) {
                            // Fallback: try to generate the route and catch any errors
                            try {
                                route('filament.admin.pages.database-health-dashboard');
                                $healthRouteExists = true;
                            } catch (\Exception $e2) {
                                $healthRouteExists = false;
                            }
                        }

                        // Check if dev docs are enabled via plugin configuration or config file
                        $pluginConfig = app()->bound('codeforge-plugin-config') ? app('codeforge-plugin-config') : [];
                        $devDocsEnabled = $pluginConfig['enable_dev_docs'] ?? config('codeforge-database-studio.features.dev_docs', false);
                        $docsRouteExists = false;

                        if ($devDocsEnabled) {
                            try {
                                $docsRouteExists = Route::has('codeforge.docs.home');
                            } catch (\Exception $e) {
                                // Fallback: try to generate the route and catch any errors
                                try {
                                    route('codeforge.docs.home');
                                    $docsRouteExists = true;
                                } catch (\Exception $e2) {
                                    $docsRouteExists = false;
                                }
                            }
                        }
                    @endphp

                    @if($devDocsEnabled && $docsRouteExists)
                        <a href="{{ route('codeforge.docs.home') }}" target="_blank" class="action-button docs-button"
                            title="Open CodeForge Database Studio Documentation">
                            📚 Documentation
                        </a>
                    @endif

                    @if(config('codeforge-database-studio.features.health_monitoring', true) && $healthRouteExists)
                        <a href="{{ route('filament.admin.pages.database-health-dashboard') }}"
                            class="action-button health-button">
                            💓 Health Dashboard
                        </a>
                    @endif

                    <button onclick="refreshMetrics()" class="action-button refresh-button">
                        🔄 Refresh Metrics
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes slideIn {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-info {
            flex: 1;
            min-width: 280px;
        }

        .main-title {
            color: white;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 700;
            margin: 0 0 8px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .main-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            margin: 0;
            font-weight: 300;
        }

        .badge-container {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .feature-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: clamp(0.75rem, 1.5vw, 0.85rem);
            backdrop-filter: blur(10px);
            white-space: nowrap;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .action-button {
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            display: inline-block;
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
            white-space: nowrap;
            text-align: center;
            min-width: 120px;
        }

        .health-button {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .health-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .docs-button {
            background: rgba(59, 130, 246, 0.8);
            color: white;
        }

        .docs-button:hover {
            background: rgba(59, 130, 246, 1);
            transform: translateY(-2px);
        }

        .refresh-button {
            background: rgba(16, 185, 129, 0.8);
            color: white;
            border: none;
            cursor: pointer;
        }

        .refresh-button:hover {
            background: rgba(16, 185, 129, 1);
            transform: translateY(-2px);
        }

        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            padding: 20px;
            border-radius: 16px;
            color: white;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.6s ease-out forwards;
        }

        .stat-card .bg-icon {
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: clamp(2.5rem, 6vw, 4rem);
            opacity: 0.2;
        }

        .stat-card-content {
            position: relative;
            z-index: 1;
        }

        .stat-card h3 {
            margin: 0 0 8px 0;
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-card p {
            margin: 0;
            opacity: 0.9;
            font-size: clamp(0.8rem, 1.3vw, 0.9rem);
        }

        .main-content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .content-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-icon {
            width: clamp(40px, 8vw, 48px);
            height: clamp(40px, 8vw, 48px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            color: white;
            flex-shrink: 0;
        }

        .section-info h2 {
            margin: 0;
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            font-weight: 700;
            color: #1e293b;
        }

        .section-info p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
        }

        .widget-container {
            border-radius: 12px;
            padding: 16px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 16px;
        }

        .tool-card {
            color: white;
            padding: 18px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
        }

        .tool-card:hover {
            transform: translateY(-4px);
            text-decoration: none;
            color: white;
        }

        .tool-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
        }

        .tool-header span {
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        .tool-header h3 {
            margin: 0;
            font-size: clamp(1rem, 2.2vw, 1.2rem);
            font-weight: 600;
        }

        .tool-card p {
            margin: 0;
            opacity: 0.9;
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
        }

        .bottom-stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .stat-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            gap: 12px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .stat-header h3 {
            margin: 0;
            font-size: clamp(1rem, 2vw, 1.2rem);
            font-weight: 600;
            color: #1e293b;
        }

        /* Mobile specific adjustments */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                justify-content: stretch;
            }

            .action-button {
                flex: 1;
                min-width: auto;
            }

            .main-content-grid {
                grid-template-columns: 1fr;
            }

            .quick-stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            }

            .tools-grid {
                grid-template-columns: 1fr;
            }

            .bottom-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 769px) {
            .main-content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .content-section {
                padding: 16px;
            }

            .stat-card {
                padding: 16px;
            }

            .badge-container {
                justify-content: center;
            }
        }
    </style>

    <!-- Quick Stats Overview Cards -->
    <div style="margin-bottom: 32px;">
        @php
            // Get real-time database statistics
            $healthService = app(\HkDevs\CodeForgeStudio\Services\DatabaseHealthService::class);
            $defaultConnection = config('database.default');
            $connectionStatus = $healthService->testConnection($defaultConnection);
            $performanceMetrics = $healthService->getPerformanceMetrics($defaultConnection);

            // Calculate real stats
            $totalTables = 0;
            $databaseSize = 0;
            $activeFeatures = 0;
            $systemHealth = 'Unknown';

            try {
                // Get table count
                $tables = DB::select('SHOW TABLES');
                $totalTables = count($tables);

                // Get database size (MySQL specific)
                if (config('database.default') === 'mysql') {
                    $dbName = config('database.connections.mysql.database');
                    $sizeQuery = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb' FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
                    $databaseSize = $sizeQuery[0]->size_mb ?? 0;
                }

                // Count active features
                $features = config('codeforge-database-studio.features', []);
                $activeFeatures = count(array_filter($features));

                // Determine system health
                if ($connectionStatus['status'] === 'connected') {
                    if (isset($connectionStatus['response_time']) && $connectionStatus['response_time'] < 100) {
                        $systemHealth = 'Excellent';
                    } elseif (isset($connectionStatus['response_time']) && $connectionStatus['response_time'] < 500) {
                        $systemHealth = 'Good';
                    } else {
                        $systemHealth = 'Slow';
                    }
                } else {
                    $systemHealth = 'Critical';
                }

            } catch (\Exception $e) {
                // Handle errors gracefully
            }

            // Get query performance for monitoring status
            $queryCount = $performanceMetrics['query_performance']['total_queries'] ?? 0;
            $monitoringStatus = $queryCount > 0 ? 'Active' : 'Standby';
        @endphp
        <div class="quick-stats-grid">
            <div class="stat-card"
                style="background: linear-gradient(135deg, #ff6b6b, #ee5a6f); box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);">
                <div class="bg-icon">🗄️</div>
                <div class="stat-card-content">
                    <h3>Database Status</h3>
                    <div class="stat-value">{{ $connectionStatus['status'] === 'connected' ? 'Connected' : 'Offline' }}
                    </div>
                    <p>{{ $totalTables }} tables • {{ number_format($databaseSize, 1) }} MB</p>
                </div>
            </div>

            <div class="stat-card"
                style="background: linear-gradient(135deg, #4ecdc4, #44a08d); box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);">
                <div class="bg-icon">⚡</div>
                <div class="stat-card-content">
                    <h3>Performance</h3>
                    <div class="stat-value">{{ $systemHealth }}</div>
                    <p>
                        @if(isset($connectionStatus['response_time']))
                            {{ number_format($connectionStatus['response_time'], 1) }}ms response
                        @else
                            No response data
                        @endif
                    </p>
                </div>
            </div>

            <div class="stat-card"
                style="background: linear-gradient(135deg, #a8e6cf, #7fcdcd); box-shadow: 0 8px 25px rgba(168, 230, 207, 0.3);">
                <div class="bg-icon">🛠️</div>
                <div class="stat-card-content">
                    <h3>Tools Active</h3>
                    <div class="stat-value">
                        {{ $activeFeatures }}/{{ count(config('codeforge-database-studio.features', [])) }}
                    </div>
                    <p>
                        @if($activeFeatures === count(config('codeforge-database-studio.features', [])))
                            All features enabled
                        @else
                            Partially enabled features
                        @endif
                    </p>
                </div>
            </div>

            <div class="stat-card"
                style="background: linear-gradient(135deg, #ffd93d, #ff9500); box-shadow: 0 8px 25px rgba(255, 217, 61, 0.3);">
                <div class="bg-icon">📊</div>
                <div class="stat-card-content">
                    <h3>Monitoring</h3>
                    <div class="stat-value">{{ $monitoringStatus }}</div>
                    <p>
                        @if($queryCount > 0)
                            {{ number_format($queryCount) }} queries tracked (24h)
                        @else
                            Query tracking ready
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Sections -->
    <div class="main-content-grid">
        <!-- Database Statistics Section -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    📈
                </div>
                <div class="section-info">
                    <h2>Database Statistics</h2>
                    <p>Overview of your database metrics</p>
                </div>
            </div>

            <div class="widget-container" style="background: #f8fafc; border-left: 4px solid #667eea;">
                @livewire(HkDevs\CodeForgeStudio\Widgets\DatabaseStatsWidget::class)
            </div>
        </div>

        <!-- Health Monitoring Section -->
        <div class="content-section">
            <div class="section-header">
                <div class="section-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    💓
                </div>
                <div class="section-info">
                    <h2>Health Monitoring</h2>
                    <p>Real-time database health metrics</p>
                </div>
            </div>

            <div class="widget-container" style="background: #f0fdfa; border-left: 4px solid #10b981;">
                @livewire(HkDevs\CodeForgeStudio\Widgets\DatabaseHealthMetricsWidget::class)
            </div>
        </div>
    </div>

    <!-- Tools and Features Grid -->
    <div class="content-section" style="margin-bottom: 32px;">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                🛠️
            </div>
            <div class="section-info">
                <h2>Database Tools & Features</h2>
                <p>Access all your database management tools</p>
            </div>
        </div>

        <div class="tools-grid">
            @php
                // Check route existence safely
                $smartSeederExists = false;
                $schemaDesignerExists = false;
                $codeGenerationExists = false;
                $migrationManagerExists = false;

                try {
                    $smartSeederExists = Route::has('filament.admin.pages.smart-data-seeder');
                    $schemaDesignerExists = Route::has('filament.admin.pages.schema-designer');
                    $codeGenerationExists = Route::has('filament.admin.pages.generator-overview-page');
                    $migrationManagerExists = Route::has('filament.admin.resources.migrations.index');
                } catch (\Exception $e) {
                    // Fallback to try-catch route generation
                    try {
                        route('filament.admin.pages.smart-data-seeder');
                        $smartSeederExists = true;
                    } catch (\Exception $e) {
                    }
                    try {
                        route('filament.admin.pages.schema-designer');
                        $schemaDesignerExists = true;
                    } catch (\Exception $e) {
                    }
                    try {
                        route('filament.admin.pages.generator-overview-page');
                        $codeGenerationExists = true;
                    } catch (\Exception $e) {
                    }
                    try {
                        route('filament.admin.resources.migrations.index');
                        $migrationManagerExists = true;
                    } catch (\Exception $e) {
                    }
                }
            @endphp

            @if(config('codeforge-database-studio.features.smart_seeding', true) && $smartSeederExists)
                <a href="{{ route('filament.admin.pages.smart-data-seeder') }}" class="tool-card"
                    style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
                    <div class="tool-header">
                        <span>🧠</span>
                        <h3>Smart Data Seeder</h3>
                    </div>
                    <p>Intelligent data generation and seeding tools</p>
                </a>
            @endif

            @if(config('codeforge-database-studio.features.schema_designer', true) && $schemaDesignerExists)
                <a href="{{ route('filament.admin.pages.schema-designer') }}" class="tool-card"
                    style="background: linear-gradient(135deg, #06b6d4, #0891b2); box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                    <div class="tool-header">
                        <span>🎨</span>
                        <h3>Schema Designer</h3>
                    </div>
                    <p>Visual database schema design and management</p>
                </a>
            @endif

            @if(config('codeforge-database-studio.features.code_generation', true) && $codeGenerationExists)
                <a href="{{ route('filament.admin.pages.generator-overview-page') }}" class="tool-card"
                    style="background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);">
                    <div class="tool-header">
                        <span>⚡</span>
                        <h3>Code Generation</h3>
                    </div>
                    <p>Automated code generation from database schema</p>
                </a>
            @endif

            @if(config('codeforge-database-studio.features.migration_manager', true) && $migrationManagerExists)
                <a href="{{ route('filament.admin.resources.migrations.index') }}" class="tool-card"
                    style="background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);">
                    <div class="tool-header">
                        <span>📋</span>
                        <h3>Migration Manager</h3>
                    </div>
                    <p>Advanced migration tracking and management</p>
                </a>
            @endif
        </div>
    </div>

    <!-- Bottom Statistics Row -->
    <div class="bottom-stats-grid">
        <!-- Migration Statistics -->
        <div class="stat-section">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    📋
                </div>
                <h3>Migration Stats</h3>
            </div>
            <div class="widget-container" style="background: #f8fafc; border-radius: 10px; padding: 16px;">
                @livewire(HkDevs\CodeForgeStudio\Widgets\MigrationStatsWidget::class)
            </div>
        </div>

        <!-- Seeder Statistics -->
        <div class="stat-section">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                    🌱
                </div>
                <h3>Seeder Stats</h3>
            </div>
            <div class="widget-container" style="background: #f0fdfa; border-radius: 10px; padding: 16px;">
                @livewire(HkDevs\CodeForgeStudio\Widgets\SeederStatsWidget::class)
            </div>
        </div>

        <!-- Code Generation Stats -->
        <div class="stat-section">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    ⚡
                </div>
                <h3>Code Generation</h3>
            </div>
            <div class="widget-container" style="background: #fef2f2; border-radius: 10px; padding: 16px;">
                @livewire(HkDevs\CodeForgeStudio\Widgets\CodeGenerationStatsWidget::class)
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="content-section" style="margin-top: 32px;">
        <div class="section-header">
            <div class="section-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                📈
            </div>
            <div class="section-info">
                <h2>Recent Activity</h2>
                <p>Latest migrations and database changes</p>
            </div>
        </div>

        <div class="widget-container" style="background: #f0fdfa; border-left: 4px solid #10b981;">
            @livewire(HkDevs\CodeForgeStudio\Widgets\RecentMigrationsWidget::class)
        </div>
    </div>

    <script>
        function refreshMetrics() {
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '🔄 Refreshing...';
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = '✅ Refreshed!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    window.location.reload();
                }, 1000);
            }, 2000);
        }
    </script>
</x-filament-panels::page>