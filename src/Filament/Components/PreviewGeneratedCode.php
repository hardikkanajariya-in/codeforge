<?php

namespace HkDevs\CodeForgeStudio\Filament\Components;

use Filament\Forms\Components\View;

/**
 * PreviewGeneratedCode
 * 
 * A Filament view component for displaying generated code files with syntax highlighting.
 * Extends the base View component to show multiple code files in a tabbed interface.
 * 
 * Features:
 * - Multi-file code display with array of code files
 * - Language-specific syntax highlighting support
 * - Custom view template for code presentation
 * - Simple API for setting code files and language
 * 
 * @package HkDevs\CodeForgeStudio\Filament\Components
 * @author hardikkanajariya.in
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * PreviewGeneratedCode::make()
 *     ->codeFiles(['User.php' => $code, 'UserFactory.php' => $factoryCode])
 *     ->language('php')
 */
class PreviewGeneratedCode extends View
{
    protected string $view = 'codeforge-studio::components.preview-generated-code';

    protected array $codeFiles = [];
    protected string $language = 'php';

    public static function make(?string $view = null): static
    {
        $static = app(static::class);
        if ($view) {
            $static->view($view);
        }
        return $static;
    }

    public function codeFiles(array $files): static
    {
        $this->codeFiles = $files;
        return $this;
    }

    public function language(string $language): static
    {
        $this->language = $language;
        return $this;
    }

    public function getCodeFiles(): array
    {
        return $this->codeFiles;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }
}
