<x-filament-panels::page>
    <div class="space-y-6">
        <style>
            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
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

            .designer-grid {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr auto auto auto;
                gap: 0.75rem;
                align-items: center;
            }

            .designer-options-grid {
                display: grid;
                gap: 0.75rem;
                margin-top: 0.75rem;
            }

            .designer-field {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 1rem;
            }

            .designer-container {
                background: #f8fafc;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .designer-button {
                padding: 0.375rem;
                background: #f3f4f6;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.75rem;
            }

            .designer-button:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .designer-input {
                padding: 0.5rem;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 0.875rem;
            }

            .designer-select {
                padding: 0.5rem;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 0.875rem;
            }

            .add-button {
                padding: 0.5rem 1rem;
                background: #10b981;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 0.875rem;
                cursor: pointer;
            }

            .remove-button {
                padding: 0.375rem;
                background: #ef4444;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.75rem;
            }

            .designer-toggle {
                padding: 0.5rem 1rem;
                background: #3b82f6;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 0.875rem;
                cursor: pointer;
            }

            @media (max-width: 1024px) {
                .designer-grid {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                }

                .designer-options-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <!-- Header with Model Selection -->
        <div
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h1 style="color: white; font-size: 2rem; font-weight: 700; margin: 0;">Filament Resource Generator
                    </h1>
                    <p style="color: rgba(255,255,255,0.8); font-size: 1.1rem; margin: 0.5rem 0 0 0;">
                        Generate complete Filament admin resources from your Laravel models
                    </p>
                </div>
                @if($selectedModel)
                    <button wire:click="resetToDefaults"
                        style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; transition: all 0.2s;">
                        Reset
                    </button>
                @endif
            </div>

            <!-- Model Selection -->
            <div style="max-width: 400px;">
                <label style="color: white; font-weight: 600; display: block; margin-bottom: 0.5rem;">Select
                    Model</label>
                <select wire:model.live="selectedModel"
                    style="width: 100%; padding: 0.75rem; border-radius: 8px; border: none; font-size: 1rem; background: white; color: #1f2937;">
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
                                <label
                                    style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation Label</label>
                                <input type="text" wire:model.live="resourceSettings.navigation_label"
                                    wire:change="updateResourceSettings"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                    placeholder="e.g., Countries" />
                            </div>

                            <div>
                                <label
                                    style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation
                                    Group</label>
                                <input type="text" wire:model.live="resourceSettings.navigation_group"
                                    wire:change="updateResourceSettings"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                    placeholder="e.g., Content Management" />
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <label
                                        style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Sort
                                        Order</label>
                                    <input type="number" wire:model.live="resourceSettings.navigation_sort"
                                        wire:change="updateResourceSettings"
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                        placeholder="10" />
                                </div>

                                <div>
                                    <label
                                        style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Navigation
                                        Icon</label>
                                    <input type="text" wire:model.live="resourceSettings.navigation_icon"
                                        wire:change="updateResourceSettings"
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;"
                                        placeholder="heroicon-o-globe" />
                                </div>
                            </div>

                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" wire:model.live="resourceSettings.enable_view_page"
                                        wire:change="updateResourceSettings" />
                                    <span style="font-weight: 500;">Enable View Page</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" wire:model.live="resourceSettings.enable_global_search"
                                        wire:change="updateResourceSettings" />
                                    <span style="font-weight: 500;">Global Search</span>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" wire:model.live="resourceSettings.generate_policy"
                                        wire:change="updateResourceSettings" />
                                    <span style="font-weight: 500;">Generate Policy</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    @if(!empty($formFields) || true)
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <div style="margin-bottom: 1rem;">
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">Form Fields</h3>
                                <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">Design and configure form fields for your resource</p>
                            </div>

                            <!-- Form Designer - Always Visible -->
                            <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h4 style="font-weight: 600; color: #374151; margin: 0;">Form Designer</h4>
                                    <button wire:click="addFormField"
                                        style="padding: 0.5rem 1rem; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                                        ➕ Add Field
                                    </button>
                                </div>
                                
                                @if(empty($formFields))
                                    <div style="text-align: center; padding: 2rem; color: #6b7280; background: white; border-radius: 6px; border: 2px dashed #e5e7eb;">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
                                        <p style="margin: 0; font-weight: 500;">No form fields yet!</p>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">Click "➕ Add Field" to create your first form field</p>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach($formFields as $index => $field)
                                            <div
                                                style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                                                <div
                                                    style="display: grid; grid-template-columns: 2fr 1fr 1fr auto auto auto; gap: 0.75rem; align-items: center;">
                                                    <!-- Field Name -->
                                                    <input type="text" wire:model.live="formFields.{{ $index }}.name"
                                                        wire:change="updateFormField" placeholder="Field name (e.g., title)"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Field Type -->
                                                    <select wire:model.live="formFields.{{ $index }}.type" wire:change="updateFormField"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;">
                                                        @foreach($this->getAvailableFormFieldTypes() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Field Label -->
                                                    <input type="text" wire:model.live="formFields.{{ $index }}.label"
                                                        wire:change="updateFormField" placeholder="Label"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Move Up/Down -->
                                                    <button wire:click="moveFormFieldUp({{ $index }})" {{ $index === 0 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↑</button>

                                                    <button wire:click="moveFormFieldDown({{ $index }})" {{ $index === count($formFields) - 1 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↓</button>

                                                    <!-- Remove -->
                                                    <button wire:click="removeFormField({{ $index }})"
                                                        style="padding: 0.375rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">🗑️</button>
                                                </div>

                                                <!-- Additional Options Row -->
                                                <div
                                                    style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 0.75rem; margin-top: 0.75rem;">
                                                    <input type="text" wire:model.live="formFields.{{ $index }}.placeholder"
                                                        wire:change="updateFormField" placeholder="Placeholder text"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <input type="text" wire:model.live="formFields.{{ $index }}.helper_text"
                                                        wire:change="updateFormField" placeholder="Helper text"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <input type="text" wire:model.live="formFields.{{ $index }}.validation"
                                                        wire:change="updateFormField" placeholder="Validation rules"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <input type="checkbox" wire:model.live="formFields.{{ $index }}.required"
                                                            wire:change="updateFormField" />
                                                        <span style="font-size: 0.875rem;">Required</span>
                                                    </label>
                                                </div>

                                                <!-- Relationship Configuration (shown only for relationship type) -->
                                                @if(($formFields[$index]['type'] ?? '') === 'relationship')
                                                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f0f9ff; border-radius: 6px; border-left: 4px solid #0ea5e9;">
                                                        <h5 style="font-weight: 600; color: #0369a1; margin: 0 0 0.5rem 0; font-size: 0.875rem;">🔗 Relationship Configuration</h5>
                                                        
                                                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                                                            <!-- Relationship Type -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Relationship Type</label>
                                                                <select wire:model.live="formFields.{{ $index }}.relationship_type" wire:change="updateFormField"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;">
                                                                    @foreach($this->getRelationshipTypes() as $value => $label)
                                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Related Model -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Related Model</label>
                                                                <select wire:model.live="formFields.{{ $index }}.related_model" wire:change="updateFormField"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;">
                                                                    <option value="">Select Model...</option>
                                                                    @foreach($this->getAvailableModelsForRelationships() as $modelClass => $modelName)
                                                                        <option value="{{ $modelClass }}">{{ $modelName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Title Attribute -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Display Attribute</label>
                                                                <input type="text" wire:model.live="formFields.{{ $index }}.title_attribute"
                                                                    wire:change="updateFormField" placeholder="name"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;" />
                                                            </div>
                                                        </div>

                                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                                                            <!-- Relationship Name -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Relationship Method Name</label>
                                                                <input type="text" wire:model.live="formFields.{{ $index }}.relationship_name"
                                                                    wire:change="updateFormField" placeholder="e.g., category, user, tags"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;" />
                                                            </div>

                                                            <!-- Additional Options -->
                                                            <div style="display: flex; gap: 1rem; align-items: end;">
                                                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                                    <input type="checkbox" wire:model.live="formFields.{{ $index }}.searchable" wire:change="updateFormField" />
                                                                    <span style="font-size: 0.875rem;">Searchable</span>
                                                                </label>
                                                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                                    <input type="checkbox" wire:model.live="formFields.{{ $index }}.preload" wire:change="updateFormField" />
                                                                    <span style="font-size: 0.875rem;">Preload</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Quick Toggle List -->
                            @if(!empty($formFields))
                                <div>
                                    <h4 style="font-weight: 600; color: #374151; margin: 0 0 0.75rem 0;">Quick Toggle</h4>
                                    <div class="space-y-2">
                                        @foreach($formFields as $index => $field)
                                            <div class="field-item {{ ($field['enabled'] ?? true) ? '' : 'disabled' }}"
                                                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600; color: #374151;">
                                                        {{ $field['label'] ?: $field['name'] ?: 'Unnamed Field' }}</div>
                                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $field['name'] }}
                                                        ({{ $field['type'] }}){{ $field['required'] ? ' • Required' : '' }}</div>
                                                </div>
                                                <button wire:click="toggleFormField({{ $index }})"
                                                    class="toggle-button {{ ($field['enabled'] ?? true) ? 'enabled' : 'disabled' }}"
                                                    style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                                                    {{ ($field['enabled'] ?? true) ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Table Columns -->
                    @if(!empty($tableColumns) || true)
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <div style="margin-bottom: 1rem;">
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">Table Columns</h3>
                                <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">Configure columns for your table view</p>
                            </div>

                            <!-- Table Designer - Always Visible -->
                            <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h4 style="font-weight: 600; color: #374151; margin: 0;">Table Designer</h4>
                                    <button wire:click="addTableColumn"
                                        style="padding: 0.5rem 1rem; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                                        ➕ Add Column
                                    </button>
                                </div>
                                
                                @if(empty($tableColumns))
                                    <div style="text-align: center; padding: 2rem; color: #6b7280; background: white; border-radius: 6px; border: 2px dashed #e5e7eb;">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                                        <p style="margin: 0; font-weight: 500;">No table columns yet!</p>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">Click "➕ Add Column" to create your first table column</p>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach($tableColumns as $index => $column)
                                            <div
                                                style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                                                <div
                                                    style="display: grid; grid-template-columns: 2fr 1fr 1fr auto auto auto; gap: 0.75rem; align-items: center;">
                                                    <!-- Column Name -->
                                                    <input type="text" wire:model.live="tableColumns.{{ $index }}.name"
                                                        wire:change="updateTableColumn" placeholder="Column name (e.g., title)"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Column Type -->
                                                    <select wire:model.live="tableColumns.{{ $index }}.type"
                                                        wire:change="updateTableColumn"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;">
                                                        @foreach($this->getAvailableTableColumnTypes() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Column Label -->
                                                    <input type="text" wire:model.live="tableColumns.{{ $index }}.label"
                                                        wire:change="updateTableColumn" placeholder="Label"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Move Up/Down -->
                                                    <button wire:click="moveTableColumnUp({{ $index }})" {{ $index === 0 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↑</button>

                                                    <button wire:click="moveTableColumnDown({{ $index }})" {{ $index === count($tableColumns) - 1 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↓</button>

                                                    <!-- Remove -->
                                                    <button wire:click="removeTableColumn({{ $index }})"
                                                        style="padding: 0.375rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">🗑️</button>
                                                </div>

                                                <!-- Additional Options Row -->
                                                <div
                                                    style="display: grid; grid-template-columns: 1fr 1fr auto auto auto; gap: 0.75rem; margin-top: 0.75rem;">
                                                    <input type="text" wire:model.live="tableColumns.{{ $index }}.format"
                                                        wire:change="updateTableColumn" placeholder="Format (e.g., date, currency)"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <input type="text" wire:model.live="tableColumns.{{ $index }}.suffix"
                                                        wire:change="updateTableColumn" placeholder="Suffix/Badge"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <input type="checkbox" wire:model.live="tableColumns.{{ $index }}.sortable"
                                                            wire:change="updateTableColumn" />
                                                        <span style="font-size: 0.875rem;">Sortable</span>
                                                    </label>

                                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <input type="checkbox" wire:model.live="tableColumns.{{ $index }}.searchable"
                                                            wire:change="updateTableColumn" />
                                                        <span style="font-size: 0.875rem;">Searchable</span>
                                                    </label>

                                                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <input type="checkbox" wire:model.live="tableColumns.{{ $index }}.toggleable"
                                                            wire:change="updateTableColumn" />
                                                        <span style="font-size: 0.875rem;">Toggleable</span>
                                                    </label>
                                                </div>

                                                <!-- Relationship Configuration for Table Columns -->
                                                @if(($tableColumns[$index]['type'] ?? '') === 'relationship')
                                                    <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f0f9ff; border-radius: 6px; border-left: 4px solid #0ea5e9;">
                                                        <h5 style="font-weight: 600; color: #0369a1; margin: 0 0 0.5rem 0; font-size: 0.875rem;">🔗 Relationship Column Configuration</h5>
                                                        
                                                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                                                            <!-- Relationship Type -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Relationship Type</label>
                                                                <select wire:model.live="tableColumns.{{ $index }}.relationship_type" wire:change="updateTableColumn"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;">
                                                                    @foreach($this->getRelationshipTypes() as $value => $label)
                                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Related Model -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Related Model</label>
                                                                <select wire:model.live="tableColumns.{{ $index }}.related_model" wire:change="updateTableColumn"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;">
                                                                    <option value="">Select Model...</option>
                                                                    @foreach($this->getAvailableModelsForRelationships() as $modelClass => $modelName)
                                                                        <option value="{{ $modelClass }}">{{ $modelName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Title Attribute -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Display Attribute</label>
                                                                <input type="text" wire:model.live="tableColumns.{{ $index }}.title_attribute"
                                                                    wire:change="updateTableColumn" placeholder="name"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;" />
                                                            </div>
                                                        </div>

                                                        <div style="margin-top: 0.75rem;">
                                                            <!-- Relationship Name -->
                                                            <div>
                                                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Relationship Method Name</label>
                                                                <input type="text" wire:model.live="tableColumns.{{ $index }}.relationship_name"
                                                                    wire:change="updateTableColumn" placeholder="e.g., category, user, tags"
                                                                    style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem; width: 100%;" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Quick Toggle List -->
                            @if(!empty($tableColumns))
                                <div>
                                    <h4 style="font-weight: 600; color: #374151; margin: 0 0 0.75rem 0;">Quick Toggle</h4>
                                    <div class="space-y-2">
                                        @foreach($tableColumns as $index => $column)
                                            <div class="field-item {{ ($column['enabled'] ?? true) ? '' : 'disabled' }}"
                                                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600; color: #374151;">
                                                        {{ $column['label'] ?: $column['name'] ?: 'Unnamed Column' }}</div>
                                                    <div style="font-size: 0.875rem; color: #6b7280;">
                                                        {{ $column['name'] }} ({{ $column['type'] }})
                                                        @if($column['sortable']) • Sortable @endif
                                                        @if($column['searchable']) • Searchable @endif
                                                    </div>
                                                </div>
                                                <button wire:click="toggleTableColumn({{ $index }})"
                                                    class="toggle-button {{ ($column['enabled'] ?? true) ? 'enabled' : 'disabled' }}"
                                                    style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                                                    {{ ($column['enabled'] ?? true) ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Filters -->
                    @if(!empty($filters) || true)
                        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                            <div style="margin-bottom: 1rem;">
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">Filters</h3>
                                <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">Add filters to help users find specific records</p>
                            </div>

                            <!-- Filter Designer - Always Visible -->
                            <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <h4 style="font-weight: 600; color: #374151; margin: 0;">Filter Designer</h4>
                                    <button wire:click="addFilter"
                                        style="padding: 0.5rem 1rem; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                                        ➕ Add Filter
                                    </button>
                                </div>
                                
                                @if(empty($filters))
                                    <div style="text-align: center; padding: 2rem; color: #6b7280; background: white; border-radius: 6px; border: 2px dashed #e5e7eb;">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🔍</div>
                                        <p style="margin: 0; font-weight: 500;">No filters yet!</p>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">Click "➕ Add Filter" to create your first filter</p>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach($filters as $index => $filter)
                                            <div
                                                style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                                                <div
                                                    style="display: grid; grid-template-columns: 2fr 1fr 1fr auto auto auto; gap: 0.75rem; align-items: center;">
                                                    <!-- Filter Name -->
                                                    <input type="text" wire:model.live="filters.{{ $index }}.name"
                                                        wire:change="updateFilter" placeholder="Filter name (e.g., status)"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Filter Type -->
                                                    <select wire:model.live="filters.{{ $index }}.type" wire:change="updateFilter"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;">
                                                        @foreach($this->getAvailableFilterTypes() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                    <!-- Filter Label -->
                                                    <input type="text" wire:model.live="filters.{{ $index }}.label"
                                                        wire:change="updateFilter" placeholder="Label"
                                                        style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;" />

                                                    <!-- Move Up/Down -->
                                                    <button wire:click="moveFilterUp({{ $index }})" {{ $index === 0 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↑</button>

                                                    <button wire:click="moveFilterDown({{ $index }})" {{ $index === count($filters) - 1 ? 'disabled' : '' }}
                                                        style="padding: 0.375rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">↓</button>

                                                    <!-- Remove -->
                                                    <button wire:click="removeFilter({{ $index }})"
                                                        style="padding: 0.375rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;">🗑️</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Quick Toggle List -->
                            @if(!empty($filters))
                                <div>
                                    <h4 style="font-weight: 600; color: #374151; margin: 0 0 0.75rem 0;">Quick Toggle</h4>
                                    <div class="space-y-2">
                                        @foreach($filters as $index => $filter)
                                            <div class="field-item {{ ($filter['enabled'] ?? false) ? '' : 'disabled' }}"
                                                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 600; color: #374151;">
                                                        {{ $filter['label'] ?: $filter['name'] ?: 'Unnamed Filter' }}</div>
                                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $filter['name'] }}
                                                        ({{ $filter['type'] }})</div>
                                                </div>
                                                <button wire:click="toggleFilter({{ $index }})"
                                                    class="toggle-button {{ ($filter['enabled'] ?? false) ? 'enabled' : 'disabled' }}"
                                                    style="padding: 0.375rem 0.75rem; border-radius: 6px; border: none; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                                                    {{ ($filter['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Generate Button -->
                    <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e5e7eb;">
                        @if($isGenerating)
                            <div style="text-align: center; padding: 1rem;">
                                <div
                                    style="display: inline-block; width: 2rem; height: 2rem; border: 2px solid #e5e7eb; border-top: 2px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;">
                                </div>
                                <div style="color: #6b7280; margin-top: 1rem; font-weight: 600;">Generating resource files...
                                </div>
                            </div>
                        @else
                            <button wire:click="generateFiles"
                                style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.transform='translateY(-2px)'"
                                onmouseout="this.style.transform='translateY(0)'">
                                🚀 Generate Resource Files
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Right Panel: Code Preview -->
                <div
                    style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; height: fit-content; position: sticky; top: 2rem;">
                    <div style="border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">Code Preview</h3>
                            <button wire:click="refreshPreview"
                                style="padding: 0.5rem 1rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; font-size: 0.875rem; color: #374151;">
                                🔄 Refresh
                            </button>
                        </div>
                        @if(!empty($previewData))
                            <p style="color: #6b7280; font-size: 0.875rem; margin: 0.5rem 0 0 0;">{{ count($previewData) }}
                                file(s) will be generated</p>
                        @endif
                    </div>

                    @if(!empty($previewData))
                        <!-- Tabs Navigation -->
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 0 1.5rem; background: #f9fafb;">
                            <div style="display: flex; gap: 0.5rem; overflow-x: auto;">
                                @foreach($previewData as $index => $file)
                                    <button wire:click="$set('activePreviewTab', {{ $index }})"
                                        class="tab-button {{ $activePreviewTab === $index ? 'active' : '' }}" style="
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
                                                    ">
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
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
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
                                                                " title="Copy to clipboard">
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
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #374151; margin-bottom: 1rem;">Ready to Generate
                    Filament Resources?</h2>
                <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                    Select a Laravel model from the dropdown above to automatically generate a complete Filament admin
                    resource
                    with forms, tables, filters, and all the CRUD functionality you need.
                </p>

                <div
                    style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; max-width: 900px; margin-left: auto; margin-right: auto;">
                    <div style="background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
                        <h3 style="font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Lightning Fast</h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Generate complete resources in seconds
                        </p>
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