<?php

namespace HkDevs\CodeForgeStudio\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

/**
 * DocsController handles the documentation system for CodeForge Database Studio.
 *
 * This controller provides comprehensive documentation for developers including:
 * - Feature explanations and usage guides
 * - Architecture and implementation details
 * - API reference and examples
 * - Development guidelines and best practices
 *
 * @author HkDevs <contact@hardikkanajariya.in>
 *
 * @version 1.0
 *
 * @since 1.0
 */
class DocsController extends Controller
{
    /**
     * Show the documentation home page.
     */
    public function home(): View
    {
        return view('codeforge-studio::docs.home');
    }

    /**
     * Show the getting started guide.
     */
    public function gettingStarted(): View
    {
        return view('codeforge-studio::docs.getting-started');
    }

    /**
     * Show the installation guide.
     */
    public function installation(): View
    {
        return view('codeforge-studio::docs.installation');
    }

    /**
     * Show the configuration guide.
     */
    public function configuration(): View
    {
        return view('codeforge-studio::docs.configuration');
    }

    /**
     * Show the requirements page.
     */
    public function requirements(): View
    {
        return view('codeforge-studio::docs.requirements');
    }

    /**
     * Show the features overview.
     */
    public function featuresOverview(): View
    {
        return view('codeforge-studio::docs.features.overview');
    }

    /**
     * Show the database health feature documentation.
     */
    public function databaseHealth(): View
    {
        return view('codeforge-studio::docs.features.database-health');
    }

    /**
     * Show the migration management feature documentation.
     */
    public function migrationManagement(): View
    {
        return view('codeforge-studio::docs.features.migration-management');
    }

    /**
     * Show the schema designer feature documentation.
     */
    public function schemaDesigner(): View
    {
        return view('codeforge-studio::docs.features.schema-designer');
    }

    /**
     * Show the code generation feature documentation.
     */
    public function codeGeneration(): View
    {
        return view('codeforge-studio::docs.features.code-generation');
    }

    /**
     * Show the data seeding feature documentation.
     */
    public function dataSeeding(): View
    {
        return view('codeforge-studio::docs.features.data-seeding');
    }

    /**
     * Show the documentation generator feature documentation.
     */
    public function documentationGenerator(): View
    {
        return view('codeforge-studio::docs.features.documentation-generator');
    }

    /**
     * Show the architecture overview.
     */
    public function architectureOverview(): View
    {
        return view('codeforge-studio::docs.architecture.overview');
    }

    /**
     * Show the services architecture documentation.
     */
    public function services(): View
    {
        return view('codeforge-studio::docs.architecture.services');
    }

    /**
     * Show the events architecture documentation.
     */
    public function events(): View
    {
        return view('codeforge-studio::docs.architecture.events');
    }

    /**
     * Show the database design documentation.
     */
    public function databaseDesign(): View
    {
        return view('codeforge-studio::docs.architecture.database-design');
    }

    /**
     * Show the security architecture documentation.
     */
    public function security(): View
    {
        return view('codeforge-studio::docs.architecture.security');
    }

    /**
     * Show the API overview.
     */
    public function apiOverview(): View
    {
        return view('codeforge-studio::docs.api.overview');
    }

    /**
     * Show the API services documentation.
     */
    public function apiServices(): View
    {
        return view('codeforge-studio::docs.api.services');
    }

    /**
     * Show the API commands documentation.
     */
    public function apiCommands(): View
    {
        return view('codeforge-studio::docs.api.commands');
    }

    /**
     * Show the API events documentation.
     */
    public function apiEvents(): View
    {
        return view('codeforge-studio::docs.api.events');
    }

    /**
     * Show the API Filament resources documentation.
     */
    public function apiFilamentResources(): View
    {
        return view('codeforge-studio::docs.api.filament-resources');
    }

    /**
     * Show the customization guide.
     */
    public function customization(): View
    {
        return view('codeforge-studio::docs.advanced.customization');
    }

    /**
     * Show the extending guide.
     */
    public function extending(): View
    {
        return view('codeforge-studio::docs.advanced.extending');
    }

    /**
     * Show the performance guide.
     */
    public function performance(): View
    {
        return view('codeforge-studio::docs.advanced.performance');
    }

    /**
     * Show the testing guide.
     */
    public function testing(): View
    {
        return view('codeforge-studio::docs.advanced.testing');
    }

    /**
     * Show the deployment guide.
     */
    public function deployment(): View
    {
        return view('codeforge-studio::docs.advanced.deployment');
    }

    /**
     * Show the coding standards guide.
     */
    public function codingStandards(): View
    {
        return view('codeforge-studio::docs.guidelines.coding-standards');
    }

    /**
     * Show the contribution guide.
     */
    public function contribution(): View
    {
        return view('codeforge-studio::docs.guidelines.contribution');
    }

    /**
     * Show the workflow guide.
     */
    public function workflow(): View
    {
        return view('codeforge-studio::docs.guidelines.workflow');
    }

    /**
     * Show the troubleshooting guide.
     */
    public function troubleshooting(): View
    {
        return view('codeforge-studio::docs.troubleshooting');
    }

    /**
     * Show the FAQ page.
     */
    public function faq(): View
    {
        return view('codeforge-studio::docs.faq');
    }

    /**
     * Show the changelog.
     */
    public function changelog(): View
    {
        return view('codeforge-studio::docs.changelog');
    }

    /**
     * Show the support page.
     */
    public function support(): View
    {
        return view('codeforge-studio::docs.support');
    }

    /**
     * Handle search requests for documentation.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Query too short',
            ]);
        }

        // This is a simplified search implementation
        // In a real-world scenario, you might want to use a search engine like Elasticsearch
        $searchResults = $this->performDocumentationSearch($query);

        return response()->json([
            'results' => $searchResults,
            'query' => $query,
            'total' => count($searchResults),
        ]);
    }

    /**
     * Perform a simple search across documentation content.
     */
    private function performDocumentationSearch(string $query): array
    {
        $query = strtolower($query);

        // Define searchable documentation pages with keywords
        $pages = [
            [
                'title' => 'Getting Started',
                'url' => route('codeforge.docs.getting-started'),
                'excerpt' => 'Learn how to install and configure CodeForge Database Studio for your Laravel project.',
                'keywords' => ['installation', 'setup', 'configure', 'getting started', 'begin', 'start'],
            ],
            [
                'title' => 'Installation Guide',
                'url' => route('codeforge.docs.installation'),
                'excerpt' => 'Step-by-step installation instructions and system requirements.',
                'keywords' => ['install', 'composer', 'requirements', 'setup', 'configuration'],
            ],
            [
                'title' => 'Database Health Monitoring',
                'url' => route('codeforge.docs.features.database-health'),
                'excerpt' => 'Monitor database performance, health metrics, and connection status.',
                'keywords' => ['health', 'monitoring', 'performance', 'metrics', 'database'],
            ],
            [
                'title' => 'Migration Management',
                'url' => route('codeforge.docs.features.migration-management'),
                'excerpt' => 'Advanced migration tools with history tracking and rollback capabilities.',
                'keywords' => ['migration', 'migrate', 'rollback', 'history', 'database'],
            ],
            [
                'title' => 'Schema Designer',
                'url' => route('codeforge.docs.features.schema-designer'),
                'excerpt' => 'Visual database schema design and relationship mapping.',
                'keywords' => ['schema', 'design', 'visual', 'diagram', 'erd', 'relationships'],
            ],
            [
                'title' => 'Code Generation',
                'url' => route('codeforge.docs.features.code-generation'),
                'excerpt' => 'Automated generation of models, migrations, factories, and Filament resources.',
                'keywords' => ['generation', 'generate', 'model', 'migration', 'factory', 'resource', 'filament'],
            ],
            [
                'title' => 'API Reference',
                'url' => route('codeforge.docs.api.overview'),
                'excerpt' => 'Complete API documentation for services, commands, and events.',
                'keywords' => ['api', 'reference', 'service', 'command', 'event', 'method'],
            ],
            [
                'title' => 'Architecture Overview',
                'url' => route('codeforge.docs.architecture.overview'),
                'excerpt' => 'Understanding the plugin architecture and design patterns.',
                'keywords' => ['architecture', 'design', 'pattern', 'structure', 'overview'],
            ],
            [
                'title' => 'Troubleshooting',
                'url' => route('codeforge.docs.troubleshooting'),
                'excerpt' => 'Common issues and their solutions.',
                'keywords' => ['troubleshooting', 'problem', 'issue', 'error', 'fix', 'solution'],
            ],
        ];

        $results = [];

        foreach ($pages as $page) {
            $score = 0;

            // Check title match
            if (stripos($page['title'], $query) !== false) {
                $score += 10;
            }

            // Check excerpt match
            if (stripos($page['excerpt'], $query) !== false) {
                $score += 5;
            }

            // Check keywords match
            foreach ($page['keywords'] as $keyword) {
                if (stripos($keyword, $query) !== false) {
                    $score += 3;
                }
            }

            if ($score > 0) {
                $results[] = [
                    'title' => $page['title'],
                    'url' => $page['url'],
                    'excerpt' => $page['excerpt'],
                    'score' => $score,
                ];
            }
        }

        // Sort by relevance score
        usort($results, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Remove score from final results
        return array_map(function ($result) {
            unset($result['score']);

            return $result;
        }, array_slice($results, 0, 10)); // Limit to top 10 results
    }
}
