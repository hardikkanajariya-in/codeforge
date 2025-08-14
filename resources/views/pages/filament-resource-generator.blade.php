<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Progress Steps -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0;">Filament Resource Generator</h2>
                <div style="color: rgba(255,255,255,0.8); font-size: 0.875rem;">
                    Build powerful Filament resources with zero code
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                @php
                    $steps = [
                        'select_source' => 'Select Source',
                        'configure_resource' => 'Configure Resource',
                        'preview' => 'Preview & Generate',
                        'generation_complete' => 'Complete'
                    ];
                @endphp
                
                @foreach($steps as $stepKey => $stepLabel)
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="
                            width: 2rem; 
                            height: 2rem; 
                            border-radius: 50%; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            font-weight: 600; 
                            font-size: 0.875rem;
                            @if($currentStep === $stepKey) 
                                background: white; 
                                color: #667eea; 
                            @elseif(array_search($currentStep, array_keys($steps)) > array_search($stepKey, array_keys($steps))) 
                                background: rgba(255,255,255,0.3); 
                                color: white; 
                            @else 
                                background: rgba(255,255,255,0.1); 
                                color: rgba(255,255,255,0.6); 
                            @endif
                        ">
                            {{ $loop->iteration }}
                        </div>
                        <span style="color: white; font-weight: 500;">{{ $stepLabel }}</span>
                        @if(!$loop->last)
                            <div style="width: 2rem; height: 2px; background: rgba(255,255,255,0.3); margin-left: 0.5rem;"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Step 1: Select Source -->
        @if($currentStep === 'select_source')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Source Type Selection -->
                <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Choose Source Type</h3>
                    
                    <div class="space-y-4">
                        <div style="display: flex; gap: 1rem;">
                            <button 
                                wire:click="selectSourceType('model')"
                                style="
                                    flex: 1; 
                                    padding: 1rem; 
                                    border-radius: 0.5rem; 
                                    border: 2px solid {{ $sourceType === 'model' ? '#3b82f6' : '#e5e7eb' }}; 
                                    background: {{ $sourceType === 'model' ? '#eff6ff' : 'white' }}; 
                                    color: {{ $sourceType === 'model' ? '#1e40af' : '#6b7280' }};
                                    font-weight: 600;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                "
                            >
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 2rem; height: 2rem;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 4h16v2H4V4zm0 4h16v2H4V8zm0 4h16v2H4v-2zm0 4h16v2H4v-2z"/>
                                    </svg>
                                    <span>From Model</span>
                                </div>
                            </button>
                            
                            <button 
                                wire:click="selectSourceType('migration')"
                                style="
                                    flex: 1; 
                                    padding: 1rem; 
                                    border-radius: 0.5rem; 
                                    border: 2px solid {{ $sourceType === 'migration' ? '#3b82f6' : '#e5e7eb' }}; 
                                    background: {{ $sourceType === 'migration' ? '#eff6ff' : 'white' }}; 
                                    color: {{ $sourceType === 'migration' ? '#1e40af' : '#6b7280' }};
                                    font-weight: 600;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                "
                            >
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 2rem; height: 2rem;" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                                    </svg>
                                    <span>From Migration</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Source Selection -->
                @if($sourceType === 'model')
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Select Model</h3>
                        
                        <div class="space-y-3" style="max-height: 400px; overflow-y: auto;">
                            @foreach($this->getModelsWithoutResources() as $model)
                                <div 
                                    wire:click="selectModel('{{ $model['class'] }}')"
                                    style="
                                        padding: 1rem; 
                                        border: 1px solid {{ $selectedModel === $model['class'] ? '#3b82f6' : '#e5e7eb' }}; 
                                        border-radius: 0.5rem; 
                                        background: {{ $selectedModel === $model['class'] ? '#eff6ff' : 'white' }}; 
                                        cursor: pointer;
                                        transition: all 0.2s;
                                    "
                                >
                                    <div style="display: flex; justify-content: between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 600; color: #1f2937;">{{ $model['name'] }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $model['class'] }}</div>
                                        </div>
                                        @if($selectedModel === $model['class'])
                                            <svg style="width: 1.25rem; height: 1.25rem; color: #3b82f6;" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($sourceType === 'migration')
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Select Migration</h3>
                        
                        <div class="space-y-3" style="max-height: 400px; overflow-y: auto;">
                            @foreach($this->getAvailableMigrations() as $migration)
                                <div 
                                    wire:click="selectMigration('{{ $migration['file'] }}')"
                                    style="
                                        padding: 1rem; 
                                        border: 1px solid {{ $selectedMigration === $migration['file'] ? '#3b82f6' : '#e5e7eb' }}; 
                                        border-radius: 0.5rem; 
                                        background: {{ $selectedMigration === $migration['file'] ? '#eff6ff' : 'white' }}; 
                                        cursor: pointer;
                                        transition: all 0.2s;
                                    "
                                >
                                    <div style="display: flex; justify-content: between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 600; color: #1f2937;">{{ $migration['name'] }}</div>
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $migration['file'] }}</div>
                                        </div>
                                        @if($selectedMigration === $migration['file'])
                                            <svg style="width: 1.25rem; height: 1.25rem; color: #3b82f6;" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Existing Resources -->
            @if(count($this->getExistingResources()) > 0)
                <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Existing Resources</h3>
                    <p style="color: #6b7280; margin-bottom: 1rem;">Edit or manage previously generated resources</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($this->getExistingResources() as $resource)
                            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div style="font-weight: 600; color: #1f2937;">{{ $resource['name'] }}</div>
                                    <span style="
                                        padding: 0.25rem 0.5rem; 
                                        border-radius: 0.375rem; 
                                        font-size: 0.75rem; 
                                        font-weight: 600;
                                        background: {{ $resource['status'] === 'generated' ? '#dcfce7' : ($resource['status'] === 'error' ? '#fef2f2' : '#f3f4f6') }};
                                        color: {{ $resource['status'] === 'generated' ? '#166534' : ($resource['status'] === 'error' ? '#dc2626' : '#374151') }};
                                    ">
                                        {{ ucfirst($resource['status']) }}
                                    </span>
                                </div>
                                <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">
                                    {{ class_basename($resource['model_class']) }}
                                </div>
                                <button 
                                    wire:click="editExistingResource({{ $resource['id'] }})"
                                    style="
                                        width: 100%; 
                                        padding: 0.5rem; 
                                        background: #3b82f6; 
                                        color: white; 
                                        border: none; 
                                        border-radius: 0.375rem; 
                                        font-weight: 600;
                                        cursor: pointer;
                                    "
                                >
                                    Edit Resource
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Step 2: Configure Resource -->
        @if($currentStep === 'configure_resource')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Configuration Panel -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Form Configuration -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Form Fields</h3>
                            <button 
                                wire:click="addFormField"
                                style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;"
                            >
                                Add Field
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($formConfiguration['fields'] as $index => $field)
                                <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Field Name</label>
                                            <input 
                                                type="text" 
                                                wire:model="formConfiguration.fields.{{ $index }}.name"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="field_name"
                                            >
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Field Type</label>
                                            <select 
                                                wire:model="formConfiguration.fields.{{ $index }}.type"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                            >
                                                @foreach($this->getFormFieldTypes() as $type => $label)
                                                    <option value="{{ $type }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Label</label>
                                            <input 
                                                type="text" 
                                                wire:model="formConfiguration.fields.{{ $index }}.label"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="Field Label"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" style="margin-top: 1rem;">
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Placeholder</label>
                                            <input 
                                                type="text" 
                                                wire:model="formConfiguration.fields.{{ $index }}.placeholder"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="Enter placeholder text"
                                            >
                                        </div>
                                        <div style="display: flex; align-items: end; gap: 1rem; padding-bottom: 0.5rem;">
                                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151;">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="formConfiguration.fields.{{ $index }}.required"
                                                    style="width: 1rem; height: 1rem;"
                                                >
                                                Required
                                            </label>
                                            <button 
                                                wire:click="removeFormField({{ $index }})"
                                                style="padding: 0.25rem 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer;"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(empty($formConfiguration['fields']))
                                <div style="text-align: center; padding: 2rem; color: #6b7280;">
                                    No form fields configured. Click "Add Field" to get started.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table Configuration -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Table Columns</h3>
                            <button 
                                wire:click="addTableColumn"
                                style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;"
                            >
                                Add Column
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($tableConfiguration['columns'] as $index => $column)
                                <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Column Name</label>
                                            <input 
                                                type="text" 
                                                wire:model="tableConfiguration.columns.{{ $index }}.name"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="column_name"
                                            >
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Column Type</label>
                                            <select 
                                                wire:model="tableConfiguration.columns.{{ $index }}.type"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                            >
                                                @foreach($this->getTableColumnTypes() as $type => $label)
                                                    <option value="{{ $type }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Label</label>
                                            <input 
                                                type="text" 
                                                wire:model="tableConfiguration.columns.{{ $index }}.label"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="Column Label"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem;">
                                        <div style="display: flex; gap: 1rem;">
                                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151;">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="tableConfiguration.columns.{{ $index }}.searchable"
                                                    style="width: 1rem; height: 1rem;"
                                                >
                                                Searchable
                                            </label>
                                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151;">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model="tableConfiguration.columns.{{ $index }}.sortable"
                                                    style="width: 1rem; height: 1rem;"
                                                >
                                                Sortable
                                            </label>
                                        </div>
                                        <button 
                                            wire:click="removeTableColumn({{ $index }})"
                                            style="padding: 0.25rem 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer;"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(empty($tableConfiguration['columns']))
                                <div style="text-align: center; padding: 2rem; color: #6b7280;">
                                    No table columns configured. Click "Add Column" to get started.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Filters Configuration -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Filters</h3>
                            <button 
                                wire:click="addFilter"
                                style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;"
                            >
                                Add Filter
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($filterConfiguration['filters'] as $index => $filter)
                                <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Filter Name</label>
                                            <input 
                                                type="text" 
                                                wire:model="filterConfiguration.filters.{{ $index }}.name"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                                placeholder="filter_name"
                                            >
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Filter Type</label>
                                            <select 
                                                wire:model="filterConfiguration.filters.{{ $index }}.type"
                                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                            >
                                                @foreach($this->getFilterTypes() as $type => $label)
                                                    <option value="{{ $type }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="display: flex; align-items: end;">
                                            <button 
                                                wire:click="removeFilter({{ $index }})"
                                                style="width: 100%; padding: 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer;"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(empty($filterConfiguration['filters']))
                                <div style="text-align: center; padding: 2rem; color: #6b7280;">
                                    No filters configured. Click "Add Filter" to add filters.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Templates -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Quick Templates</h3>
                        
                        <div class="space-y-2">
                            @foreach($this->getAvailableTemplates() as $template)
                                <button 
                                    wire:click="applyTemplate({{ $template['id'] }})"
                                    style="
                                        width: 100%; 
                                        text-align: left; 
                                        padding: 0.75rem; 
                                        border: 1px solid #e5e7eb; 
                                        border-radius: 0.5rem; 
                                        background: white; 
                                        cursor: pointer;
                                        transition: all 0.2s;
                                    "
                                >
                                    <div style="font-weight: 600; color: #1f2937;">{{ $template['name'] }}</div>
                                    @if($template['description'])
                                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $template['description'] }}</div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Page Configuration -->
                    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Page Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Navigation Icon</label>
                                <input 
                                    type="text" 
                                    wire:model="pageConfiguration.navigation_icon"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                    placeholder="heroicon-o-rectangle-stack"
                                >
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Navigation Group</label>
                                <input 
                                    type="text" 
                                    wire:model="pageConfiguration.navigation_group"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                    placeholder="Resources"
                                >
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Navigation Sort</label>
                                <input 
                                    type="number" 
                                    wire:model="pageConfiguration.navigation_sort"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                                    placeholder="10"
                                >
                            </div>
                            
                            <div class="space-y-2">
                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151;">
                                    <input 
                                        type="checkbox" 
                                        wire:model="pageConfiguration.enable_view_page"
                                        style="width: 1rem; height: 1rem;"
                                    >
                                    Enable View Page
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151;">
                                    <input 
                                        type="checkbox" 
                                        wire:model="policyConfiguration.generate_policy"
                                        style="width: 1rem; height: 1rem;"
                                    >
                                    Generate Policy
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <button 
                            wire:click="previewResource"
                            style="width: 100%; padding: 0.75rem 1rem; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;
                                @if(empty($selectedModel) && empty($selectedMigration))
                                    background: #9ca3af; cursor: not-allowed;
                                @else
                                    background: #8b5cf6;
                                @endif
                            "
                            @if(empty($selectedModel) && empty($selectedMigration)) disabled @endif
                        >
                            @if(empty($selectedModel) && empty($selectedMigration))
                                Select Model or Migration First
                            @else
                                Preview Resource
                            @endif
                        </button>
                        
                        <button 
                            wire:click="setStep('preview')"
                            style="width: 100%; padding: 0.75rem 1rem; background: #059669; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"
                            @if(empty($formConfiguration['fields']) && empty($tableConfiguration['columns'])) disabled @endif
                        >
                            Continue to Preview
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 3: Preview & Generate -->
        @if($currentStep === 'preview')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Preview Panel -->
                <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Resource Preview</h3>
                    
                    @if($showPreview && $previewData)
                        @php
                            $resourcePreview = $this->getResourcePreviewData();
                        @endphp
                        <div style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
                            <h4 style="font-weight: 600; margin-bottom: 0.5rem;">Resource Class: {{ $resourcePreview['resource_name'] }}</h4>
                            <p style="color: #6b7280; font-size: 0.875rem;">Model: {{ $resourcePreview['model_class'] }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Form Fields ({{ count($formConfiguration['fields']) }})</h5>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($formConfiguration['fields'] as $field)
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            {{ $field['name'] }} ({{ $field['type'] }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Table Columns ({{ count($tableConfiguration['columns']) }})</h5>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($tableConfiguration['columns'] as $column)
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            {{ $column['name'] }} ({{ $column['type'] }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            @if(!empty($filterConfiguration['filters']))
                                <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                                    <h5 style="font-weight: 600; margin-bottom: 0.5rem;">Filters ({{ count($filterConfiguration['filters']) }})</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($filterConfiguration['filters'] as $filter)
                                            <div style="font-size: 0.875rem; color: #6b7280;">
                                                {{ $filter['name'] }} ({{ $filter['type'] }})
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #6b7280;">
                            Click "Preview Resource" to see the generated code preview.
                        </div>
                    @endif
                </div>

                <!-- Generation Panel -->
                <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">Generate Resource</h3>
                    
                    <div class="space-y-4">
                        <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.5rem; padding: 1rem;">
                            <h4 style="font-weight: 600; color: #92400e; margin-bottom: 0.5rem;">Files to be Created:</h4>
                            @php
                                $resourcePreview = $this->getResourcePreviewData();
                            @endphp
                            <ul style="color: #92400e; font-size: 0.875rem;">
                                <li>• app/Filament/Resources/{{ $resourcePreview['resource_name'] }}.php</li>
                                <li>• app/Filament/Resources/{{ $resourcePreview['resource_name'] }}/Pages/List{{ $resourcePreview['model_name'] }}s.php</li>
                                <li>• app/Filament/Resources/{{ $resourcePreview['resource_name'] }}/Pages/Create{{ $resourcePreview['model_name'] }}.php</li>
                                <li>• app/Filament/Resources/{{ $resourcePreview['resource_name'] }}/Pages/Edit{{ $resourcePreview['model_name'] }}.php</li>
                                @if($pageConfiguration['enable_view_page'] ?? false)
                                    <li>• app/Filament/Resources/{{ $resourcePreview['resource_name'] }}/Pages/View{{ $resourcePreview['model_name'] }}.php</li>
                                @endif
                                @if($policyConfiguration['generate_policy'] ?? false)
                                    <li>• app/Policies/{{ $resourcePreview['model_name'] }}Policy.php</li>
                                @endif
                            </ul>
                        </div>

                        @if($isGenerating)
                            <div style="text-align: center; padding: 2rem;">
                                <div style="display: inline-block; width: 2rem; height: 2rem; border: 2px solid #e5e7eb; border-top: 2px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                <div style="color: #6b7280; margin-top: 1rem;">Generating resource files...</div>
                            </div>
                        @else
                            <div class="space-y-3">
                                <button 
                                    wire:click="previewResource"
                                    style="width: 100%; padding: 0.75rem 1rem; background: #8b5cf6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"
                                >
                                    Update Preview
                                </button>
                                
                                <button 
                                    wire:click="generateResource"
                                    style="width: 100%; padding: 0.75rem 1rem; background: #059669; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"
                                >
                                    Generate Resource Files
                                </button>
                                
                                <button 
                                    wire:click="setStep('configure_resource')"
                                    style="width: 100%; padding: 0.75rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;"
                                >
                                    Back to Configuration
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Step 4: Generation Complete -->
        @if($currentStep === 'generation_complete')
            <div style="background: white; border-radius: 0.75rem; padding: 2rem; border: 1px solid #e5e7eb; text-align: center;">
                <div style="width: 4rem; height: 4rem; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg style="width: 2rem; height: 2rem; color: #16a34a;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
                
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Resource Generated Successfully!</h3>
                <p style="color: #6b7280; margin-bottom: 2rem;">Your Filament resource has been created and is ready to use.</p>
                
                @if($generationResult && $generationResult['success'])
                    <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 0.5rem; padding: 1rem; margin-bottom: 2rem;">
                        <h4 style="font-weight: 600; color: #0c4a6e; margin-bottom: 0.5rem;">Created Files:</h4>
                        <ul style="color: #0c4a6e; font-size: 0.875rem; text-align: left;">
                            @foreach($generationResult['created_files'] as $file)
                                <li>• {{ $file }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="space-y-3">
                    <button 
                        wire:click="resetWizard"
                        style="padding: 0.75rem 2rem; background: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; margin-right: 1rem;"
                    >
                        Create Another Resource
                    </button>
                    
                    <a 
                        href="/admin"
                        style="display: inline-block; padding: 0.75rem 2rem; background: #059669; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 600;"
                    >
                        Go to Admin Panel
                    </a>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</x-filament-panels::page>
