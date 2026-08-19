<?php

namespace HkDevs\CodeForgeStudio\Support;

if (class_exists(\Filament\Schemas\Components\Grid::class)) {
    class Grid extends \Filament\Schemas\Components\Grid {}
} else {
    class Grid extends \Filament\Forms\Components\Grid {}
}
