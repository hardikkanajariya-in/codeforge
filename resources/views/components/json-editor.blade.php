<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="json-editor-container">
        <style>
            .json-editor-container .json-editor-input {
                font-family: 'Fira Code', 'Monaco', 'Consolas', monospace !important;
                font-size: 13px !important;
                line-height: 1.4 !important;
                background: #1e1e1e !important;
                color: #d4d4d4 !important;
                border: 1px solid #3e3e42 !important;
                border-radius: 6px !important;
                padding: 12px !important;
                width: 100% !important;
                resize: vertical !important;
            }

            .json-editor-container .json-editor-input:focus {
                border-color: #007acc !important;
                box-shadow: 0 0 0 2px rgba(0, 122, 204, 0.2) !important;
                outline: none !important;
            }

            .json-editor-toolbar {
                display: flex;
                gap: 8px;
                margin-bottom: 8px;
                padding: 8px;
                background: #f8f9fa;
                border-radius: 6px 6px 0 0;
                border: 1px solid #dee2e6;
                border-bottom: none;
            }

            .json-editor-btn {
                padding: 4px 8px;
                background: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                transition: background-color 0.2s;
            }

            .json-editor-btn:hover {
                background: #0056b3;
            }

            .json-editor-btn.secondary {
                background: #6c757d;
            }

            .json-editor-btn.secondary:hover {
                background: #545b62;
            }
        </style>

        <div class="json-editor-toolbar">
            <button type="button" class="json-editor-btn" onclick="formatJson(this)">
                Format JSON
            </button>
            <button type="button" class="json-editor-btn secondary" onclick="validateJson(this)">
                Validate
            </button>
            <button type="button" class="json-editor-btn secondary" onclick="minifyJson(this)">
                Minify
            </button>
        </div>

        <textarea class="json-editor-input" @if ($getId()) id="{{ $getId() }}" @endif @if ($isRequired() && !$isDisabled()) required @endif @if ($isDisabled()) disabled @endif wire:model="{{ $getStatePath() }}"
            rows="12" placeholder="{{ $getPlaceholder() ?? '{}' }}">{{ $getState() }}</textarea>

        <script>
            function formatJson(button) {
                const container = button.closest('.json-editor-container');
                const textarea = container.querySelector('textarea');

                try {
                    const parsed = JSON.parse(textarea.value || '{}');
                    textarea.value = JSON.stringify(parsed, null, 2);

                    // Trigger Livewire update
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    textarea.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (e) {
                    alert('Invalid JSON: ' + e.message);
                }
            }

            function validateJson(button) {
                const container = button.closest('.json-editor-container');
                const textarea = container.querySelector('textarea');

                try {
                    JSON.parse(textarea.value || '{}');
                    alert('✅ Valid JSON!');
                } catch (e) {
                    alert('❌ Invalid JSON: ' + e.message);
                }
            }

            function minifyJson(button) {
                const container = button.closest('.json-editor-container');
                const textarea = container.querySelector('textarea');

                try {
                    const parsed = JSON.parse(textarea.value || '{}');
                    textarea.value = JSON.stringify(parsed);

                    // Trigger Livewire update
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    textarea.dispatchEvent(new Event('change', { bubbles: true }));
                } catch (e) {
                    alert('Invalid JSON: ' + e.message);
                }
            }
        </script>
    </div>
</x-dynamic-component>
