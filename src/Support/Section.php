<?php

namespace HkDevs\CodeForgeStudio\Support;

if (class_exists(\Filament\Schemas\Components\Section::class)) {
    class Section extends \Filament\Schemas\Components\Section {}
} else {
    class Section extends \Filament\Forms\Components\Section {}
}
