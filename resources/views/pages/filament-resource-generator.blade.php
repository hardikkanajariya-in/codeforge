<x-filament-panels::page>
    <div class="space-y-6">
        <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .code-preview {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .tab-button {
            transition: all 0.2s ease;
        }
        
        .tab-button:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .tab-button.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .field-item {
            transition: all 0.2s ease;
        }
        
        .field-item.disabled {
            opacity: 0.5;
        }
        
        .toggle-button {
            transition: all 0.2s ease;
        }
        
        .toggle-button.enabled {
            background-color: #10b981;
            color: white;
        }
        
        .toggle-button.disabled {
            background-color: #e5e7eb;
            color: #6b7280;
        }
    </style>
        <!-- Header with Model Selection -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h1 style="color: white; font-size: 2rem; font-weight: 700; margin: 0;">Filament Resource Generator</h1>
                    <p style="color: rgba(255,255,255,0.8); font-size: 1.1rem; margin: 0.5rem 0 0 0;">
                        Generate complete Filament admin resources from your Laravel models
                    </p>
                </div>
                @if($selectedModel)
                    <button 
                        wire:click="resetToDefaults"
                        style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.2s;"
                    >
                        Reset
                    </button>
                @endif
            </div>
            
            <!-- Model Selection -->
            <div style="max-width: 400px;">
                <label style="color: white; font-weight: 600; display: block; margin-bottom: 0.5rem;">Select Model</label>
                <select 
                    wire:model.live="selectedModel"
                    style="width: 100%; padding: 0.75rem; border-radius: 8px; border: none; font-size: 1rem; background: white; color: #1f2937;"
                >
                    <option value="">Choose a model to get started...</option>
                    @foreach($this->availableModels as $model)
                        <option value="{{ $model['value'] }}">{{ $model['label'] }} - {{ $model['description'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($selectedModel)
            <!-- Main Content Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem; align-items: start;">
                
                <!-- Left Panel: Configuration -->
                <div class="space-y-6">
                    
                    <!-- Resource Settings -->
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Resource Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation Label</label>
                                <input 
                                    type="text" 
                                    wire:model.live="resourceSettings.navigation_label"
                                    wire:change="updateResourceSettings"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                    placeholder="e.g., Countries"
                                />
                            </div>
                            
                            <div>
                                <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation Group</label>
                                <input 
                                    type="text" 
                                    wire:model.live="resourceSettings.navigation_group"
                                    wire:change="updateResourceSettings"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                    placeholder="e.g., Content Management"
                                />
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Sort Order</label>
                                    <input 
                                        type="number" 
                                        wire:model.live="resourceSettings.navigation_sort"
                                        wire:change="updateResourceSettings"
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                        placeholder="10"
                                    />
                                </div>
                                
                                <div>
                                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation Icon</label>
                                    <input 
                                        type="text" 
                                        wire:model.live="resourceSettings.navigation_icon"
                                        wire:change="updateResourceSettings"
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                        placeholder="heroicon-o-globe"
                                    />
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="resourceSettings.enable_view_page"
                                        wire:change="updateResourceSettings"
                                    />
                                    <span style="font-weight: 500;">Enable View Page</span>
                                </label>
                                
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="resourceSettings.enable_global_search"
                                        wire:change="updateResourceSettings"
                                    />
                                    <span style="font-weight: 500;">Global Search</span>
                                </label>
                                
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="resourceSettings.generate_policy"
                                        wire:change="updateResourceSettings"
                                    />
                                    <span style="font-weight: 500;">Generate Policy</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    @if(!empty($formFields))
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Form Fields</h3>
                            
                            <div class="space-y-2">
                                @foreach($formFields as $index => $field)
                                    <div class="field-item {{ ($field['enabled'] ?? true) ? '' : 'disabled' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #374151;">{{ $field['label'] }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $field['name'] }} ({{ $field['type'] }})</div>
                                        </div>
                                        <button 
                                            wire:click="toggleFormField({{ $index }})"
                                            class="toggle-button {{ ($field['enabled'] ?? true) ? 'enabled' : 'disabled' }}"
                                            style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;"
                                        >
                                            {{ ($field['enabled'] ?? true) ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Table Columns -->
                    @if(!empty($tableColumns))
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Table Columns</h3>
                            
                            <div class="space-y-2">
                                @foreach($tableColumns as $index => $column)
                                    <div class="field-item {{ ($column['enabled'] ?? true) ? '' : 'disabled' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #374151;">{{ $column['label'] }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">
                                                {{ $column['name'] }} ({{ $column['type'] }})
                                                @if($column['sortable']) • Sortable @endif
                                                @if($column['searchable']) • Searchable @endif
                                            </div>
                                        </div>
                                        <button 
                                            wire:click="toggleTableColumn({{ $index }})"
                                            class="toggle-button {{ ($column['enabled'] ?? true) ? 'enabled' : 'disabled' }}"
                                            style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;"
                                        >
                                            {{ ($column['enabled'] ?? true) ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Filters -->
                    @if(!empty($filters))
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Filters</h3>
                            
                            <div class="space-y-2">
                                @foreach($filters as $index => $filter)
                                    <div class="field-item {{ ($filter['enabled'] ?? false) ? '' : 'disabled' }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #374151;">{{ $filter['label'] }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $filter['name'] }} ({{ $filter['type'] }})</div>
                                        </div>
                                        <button 
                                            wire:click="toggleFilter({{ $index }})"
                                            class="toggle-button {{ ($filter['enabled'] ?? false) ? 'enabled' : 'disabled' }}"
                                            style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;"
                                        >
                                            {{ ($filter['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Generate Button -->
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        @if($isGenerating)
                            <div style="text-align: center; padding: 1rem;">
                                <div style="display: inline-block; width: 2rem; height: 2rem; border: 2px solid #e5e7eb; border-top: 2px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                <div style="color: #6b7280; margin-top: 1rem; font-weight: 600;">Generating resource files...</div>
                            </div>
                        @else
                            <button 
                                wire:click="generateFiles"
                                style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.transform='translateY(0)'"
                            >
                                🚀 Generate Resource Files
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Right Panel: Code Preview -->
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; height: fit-content; position: sticky; top: 2rem;">
                    <div style="border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">Code Preview</h3>
                            <button 
                                wire:click="refreshPreview"
                                style="padding: 0.5rem 1rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 0.875rem; color: #374151;"
                            >
                                🔄 Refresh
                            </button>
                        </div>
                        @if(!empty($previewData))
                            <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">{{ count($previewData) }} file(s) will be generated</p>
                        @endif
                    </div>
                    
                    @if(!empty($previewData))
                        <!-- Tabs Navigation -->
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 0 1.5rem; background: #f9fafb;">
                            <div style="display: flex; gap: 0.5rem; overflow-x: auto;">
                                @foreach($previewData as $index => $file)
                                    <button 
                                        wire:click="$set('activePreviewTab', {{ $index }})"
                                        class="tab-button {{ $activePreviewTab === $index ? 'active' : '' }}"
                                        style="
                                            padding: 0.75rem 1rem; 
                                            border: none; 
                                            background: {{ $activePreviewTab === $index ? '#3b82f6' : 'transparent' }}; 
                                            color: {{ $activePreviewTab === $index ? 'white' : '#6b7280' }};
                                            cursor: pointer;
                                            font-weight: 500;
                                            font-size: 0.875rem;
                                            white-space: nowrap;
                                            border-radius: 6px 6px 0 0;
                                            margin-bottom: -1px;
                                        "
                                    >
                                        {{ $file['name'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Tab Content -->
                        <div style="padding: 1.5rem; max-height: 70vh; overflow-y: auto;">
                            @foreach($previewData as $index => $file)
                                @if($activePreviewTab === $index)
                                    <div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                            <h4 style="font-weight: 600; color: #374151; margin: 0;">{{ $file['name'] }}</h4>
                                            <button 
                                                onclick="navigator.clipboard.writeText(document.getElementById('code-{{ $index }}').textContent)"
                                                style="
                                                    padding: 0.5rem; 
                                                    background: #f3f4f6; 
                                                    border: 1px solid #d1d5db; 
                                                    border-radius: 0.375rem; 
                                                    cursor: pointer;
                                                    color: #6b7280;
                                                    font-size: 0.875rem;
                                                "
                                                title="Copy to clipboard"
                                            >
                                                📋 Copy
                                            </button>
                                        </div>
                                        <pre id="code-{{ $index }}" class="code-preview" style="
                                            background: #1e293b; 
                                            color: #f1f5f9; 
                                            padding: 1.5rem; 
                                            border-radius: 8px; 
                                            overflow-x: auto; 
                                            margin: 0;
                                            white-space: pre-wrap;
                                            word-wrap: break-word;
                                        "><code>{{ $file['content'] }}</code></pre>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 3rem; color: #6b7280;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📄</div>
                            <p style="font-size: 1.1rem; margin: 0;">Select a model to see the generated code preview</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Welcome State -->
            <div style="text-align: center; padding: 4rem 2rem; color: #6b7280;">
                <div style="font-size: 4rem; margin-bottom: 1.5rem;">🎯</div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #374151; margin-bottom: 1rem;">Ready to Generate Filament Resources?</h2>
                <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                    Select a Laravel model from the dropdown above to automatically generate a complete Filament admin resource 
                    with forms, tables, filters, and all the CRUD functionality you need.
                </p>
                
                <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; max-width: 900px; margin-left: auto; margin-right: auto;">
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
                        <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Lightning Fast</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Generate complete resources in seconds</p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎨</div>
                        <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Smart Design</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Intelligent field type detection</p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">👀</div>
                        <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Live Preview</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">See your code before generating</p>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔧</div>
                        <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Fully Customizable</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Toggle fields, columns & filters</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
