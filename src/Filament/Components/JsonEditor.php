<?php

namespace HkDevs\CodeForgeStudio\Filament\Components;

use Filament\Forms\Components\Textarea;

/**
 * JsonEditor
 *
 * A specialized Filament textarea component for editing JSON data with validation and formatting.
 * Extends the base Textarea component with JSON-specific features and monospace styling.
 *
 * Features:
 * - Monospace font styling for better JSON readability
 * - Automatic JSON pretty-printing on display
 * - Real-time JSON validation with error messages
 * - Seamless conversion between JSON strings and PHP arrays
 * - Graceful handling of empty/invalid JSON states
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * JsonEditor::make('configuration')
 *     ->label('JSON Configuration')
 *     ->jsonPlaceholder('{"key": "value"}')
 */
class JsonEditor extends Textarea
{
    protected string $view = 'codeforge-studio::components.json-editor';

    public static function make(string $name): static
    {
        $static = parent::make($name);

        return $static
            ->rows(12)
            ->extraAttributes([
                'style' => 'font-family: "Fira Code", "Monaco", "Consolas", monospace; font-size: 14px;',
                'class' => 'json-editor',
            ])
            ->formatStateUsing(function (?array $state): string {
                if (! $state) {
                    return '{}';
                }

                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            })
            ->dehydrateStateUsing(function (?string $state): ?array {
                if (! $state || trim($state) === '' || trim($state) === '{}') {
                    return [];
                }

                $decoded = json_decode($state, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Return the original state if JSON is invalid, validation will catch it
                    return [];
                }

                return $decoded ?? [];
            })
            ->rules([
                function () {
                    return function (string $attribute, $value, \Closure $fail) {
                        if ($value && trim($value) !== '' && trim($value) !== '{}') {
                            json_decode($value);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                $fail('Must be valid JSON: '.json_last_error_msg());
                            }
                        }
                    };
                },
            ]);
    }

    public function jsonPlaceholder(string $placeholder): static
    {
        return $this->extraAttributes([
            'placeholder' => $placeholder,
        ], merge: true);
    }
}
