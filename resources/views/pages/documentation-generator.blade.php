<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Enhanced Hero Section -->
        <div style="background: linear-gradient(135deg, #1e40af 0%, #3730a3 50%, #7c2d12 100%); border-radius: 1.5rem; padding: 3rem 2rem; color: white; position: relative; overflow: hidden;">
            <!-- Animated dots pattern -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1;">
                <div style="position: absolute; top: 20%; left: 10%; width: 4px; height: 4px; background: white; border-radius: 50%; animation: float 3s ease-in-out infinite;"></div>
                <div style="position: absolute; top: 40%; left: 80%; width: 3px; height: 3px; background: white; border-radius: 50%; animation: float 4s ease-in-out infinite 1s;"></div>
                <div style="position: absolute; top: 70%; left: 60%; width: 5px; height: 5px; background: white; border-radius: 50%; animation: float 3.5s ease-in-out infinite 2s;"></div>
                <div style="position: absolute; top: 15%; left: 70%; width: 3px; height: 3px; background: white; border-radius: 50%; animation: float 4.5s ease-in-out infinite 0.5s;"></div>
                <div style="position: absolute; top: 60%; left: 20%; width: 4px; height: 4px; background: white; border-radius: 50%; animation: float 3.8s ease-in-out infinite 1.5s;"></div>
            </div>
            
            <div style="position: relative; z-index: 10;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 4rem; height: 4rem; background: rgba(255, 255, 255, 0.2); border-radius: 1rem; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                        <span style="font-size: 2rem;">📚</span>
                    </div>
                    <div>
                        <h1 style="font-size: 2.25rem; font-weight: 900; margin: 0; letter-spacing: -0.025em; background: linear-gradient(45deg, white, rgba(255, 255, 255, 0.8)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Database Documentation Generator
                        </h1>
                        <p style="font-size: 1.125rem; color: rgba(255, 255, 255, 0.9); margin: 0.5rem 0 0 0; font-weight: 400;">
                            Create comprehensive, professional documentation for your database schema
                        </p>
                    </div>
                </div>
                
                <div style="background: rgba(255, 255, 255, 0.15); border-radius: 1rem; padding: 1.5rem; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="font-size: 1rem; color: rgba(255, 255, 255, 0.95); margin: 0; line-height: 1.6;">
                        Generate documentation for your database schema—tables, relationships, models, and validation rules—in Markdown, HTML, or PDF.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); padding: 2rem; border: 1px solid #e5e7eb;" class="dark:bg-gray-800 dark:border-gray-700">
            {{ $this->form }}
        </div>

        <!-- Enhanced Format Options -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="md:grid-cols-3">
            <!-- Markdown Card -->
            <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 1rem; padding: 2rem; border: 2px solid #bfdbfe; transition: all 0.3s ease; position: relative; overflow: hidden;" class="hover:shadow-xl hover:-translate-y-1 dark:bg-gradient-to-br dark:from-blue-900/20 dark:to-blue-800/20 dark:border-blue-800">
                <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 10;">
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 3rem; height: 3rem; background: #3b82f6; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <span style="color: white; font-size: 1.5rem; font-weight: bold;">M</span>
                            </div>
                        </div>
                        <div style="margin-left: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e40af; margin: 0;" class="dark:text-blue-100">Markdown</h3>
                            <div style="width: 2rem; height: 2px; background: #3b82f6; border-radius: 1px; margin-top: 0.25rem;"></div>
                        </div>
                    </div>
                    <p style="color: #1e40af; font-size: 0.875rem; line-height: 1.6; margin: 0;" class="dark:text-blue-200">
                        Perfect for GitHub, wikis, and developer documentation. Clean, readable format that integrates seamlessly with your development workflow.
                    </p>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <span style="background: rgba(59, 130, 246, 0.2); color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-blue-900/50 dark:text-blue-300">GitHub Ready</span>
                        <span style="background: rgba(59, 130, 246, 0.2); color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-blue-900/50 dark:text-blue-300">Version Control</span>
                    </div>
                </div>
            </div>

            <!-- HTML Card -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 1rem; padding: 2rem; border: 2px solid #bbf7d0; transition: all 0.3s ease; position: relative; overflow: hidden;" class="hover:shadow-xl hover:-translate-y-1 dark:bg-gradient-to-br dark:from-green-900/20 dark:to-green-800/20 dark:border-green-800">
                <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(34, 197, 94, 0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 10;">
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 3rem; height: 3rem; background: #22c55e; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <span style="color: white; font-size: 1.5rem; font-weight: bold;">H</span>
                            </div>
                        </div>
                        <div style="margin-left: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #166534; margin: 0;" class="dark:text-green-100">HTML</h3>
                            <div style="width: 2rem; height: 2px; background: #22c55e; border-radius: 1px; margin-top: 0.25rem;"></div>
                        </div>
                    </div>
                    <p style="color: #166534; font-size: 0.875rem; line-height: 1.6; margin: 0;" class="dark:text-green-200">
                        Styled web pages ready for sharing and viewing. Interactive navigation with beautiful styling for professional presentations.
                    </p>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <span style="background: rgba(34, 197, 94, 0.2); color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-green-900/50 dark:text-green-300">Interactive</span>
                        <span style="background: rgba(34, 197, 94, 0.2); color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-green-900/50 dark:text-green-300">Styled</span>
                    </div>
                </div>
            </div>

            <!-- PDF Card -->
            <div style="background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); border-radius: 1rem; padding: 2rem; border: 2px solid #fdba74; transition: all 0.3s ease; position: relative; overflow: hidden;" class="hover:shadow-xl hover:-translate-y-1 dark:bg-gradient-to-br dark:from-orange-900/20 dark:to-orange-800/20 dark:border-orange-800">
                <div style="position: absolute; top: -10px; right: -10px; width: 80px; height: 80px; background: rgba(249, 115, 22, 0.1); border-radius: 50%;"></div>
                <div style="position: relative; z-index: 10;">
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 3rem; height: 3rem; background: #f97316; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <span style="color: white; font-size: 1.5rem; font-weight: bold;">P</span>
                            </div>
                        </div>
                        <div style="margin-left: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #c2410c; margin: 0;" class="dark:text-orange-100">PDF</h3>
                            <div style="width: 2rem; height: 2px; background: #f97316; border-radius: 1px; margin-top: 0.25rem;"></div>
                        </div>
                    </div>
                    <p style="color: #c2410c; font-size: 0.875rem; line-height: 1.6; margin: 0;" class="dark:text-orange-200">
                        Professional documents for reports and presentations. Perfect for client deliverables and formal documentation requirements.
                    </p>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <span style="background: rgba(249, 115, 22, 0.2); color: #c2410c; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-orange-900/50 dark:text-orange-300">Professional</span>
                        <span style="background: rgba(249, 115, 22, 0.2); color: #c2410c; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500;" class="dark:bg-orange-900/50 dark:text-orange-300">Print Ready</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Features Section -->
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #e2e8f0;" class="dark:bg-gradient-to-br dark:from-gray-800 dark:to-gray-900 dark:border-gray-700">
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="display: inline-flex; align-items: center; gap: 0.75rem; background: white; padding: 0.75rem 1.5rem; border-radius: 9999px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; margin-bottom: 1rem;" class="dark:bg-gray-700 dark:border-gray-600">
                    <span style="font-size: 1.5rem;">✨</span>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0; letter-spacing: -0.025em;" class="dark:text-white">Comprehensive Features</h3>
                </div>
                <p style="font-size: 1.125rem; color: #6b7280; max-width: 40rem; margin: 0 auto; line-height: 1.6;" class="dark:text-gray-400">
                    Everything you need to create professional database documentation that developers and stakeholders will appreciate
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="md:grid-cols-2 lg:grid-cols-3">
                <!-- Feature Cards -->
                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">🏗️</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Complete Table Structure</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Detailed documentation of columns, data types, constraints, and table relationships
                            </p>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">🔗</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Foreign Key Relationships</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Visual representation of all database constraints and foreign key relationships
                            </p>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">🎯</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Eloquent Model Information</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Comprehensive details about Laravel Eloquent models and their relationships
                            </p>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">⚙️</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Model Methods & Rules</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Documentation of fillable fields, casting rules, and custom model methods
                            </p>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">📊</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Table Statistics</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Comprehensive statistics including row counts, table sizes, and performance metrics
                            </p>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; transition: all 0.3s ease;" class="hover:shadow-lg hover:-translate-y-1 dark:bg-gray-800 dark:border-gray-700">
                    <div style="display: flex; align-items: start; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 1.25rem;">🔑</span>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;" class="dark:text-white">Indexes & Constraints</h4>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5;" class="dark:text-gray-400">
                                Complete documentation of indexes, primary keys, and unique constraints
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin-top: 3rem;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 2rem; border-radius: 1rem; color: white; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1;">
                        <div style="position: absolute; top: 10%; left: 20%; width: 6px; height: 6px; background: white; border-radius: 50%;"></div>
                        <div style="position: absolute; top: 30%; left: 70%; width: 4px; height: 4px; background: white; border-radius: 50%;"></div>
                        <div style="position: absolute; top: 70%; left: 40%; width: 5px; height: 5px; background: white; border-radius: 50%;"></div>
                    </div>
                    <div style="position: relative; z-index: 10;">
                        <h4 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.75rem 0;">Ready to Generate Professional Documentation?</h4>
                        <p style="font-size: 1rem; color: rgba(255, 255, 255, 0.9); margin: 0 0 1.5rem 0; max-width: 32rem; margin-left: auto; margin-right: auto;">
                            Choose your preferred format and let our generator create comprehensive, professional documentation for your database schema.
                        </p>
                        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                            <button style="background: white; color: #3b82f6; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border: none; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" class="hover:shadow-lg hover:-translate-y-0.5">
                                Generate Documentation
                            </button>
                            <button style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.2s;" class="hover:bg-white/30">
                                View Sample
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</x-filament-panels::page>
