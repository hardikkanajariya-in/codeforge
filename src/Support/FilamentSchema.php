<?php

namespace HkDevs\CodeForgeStudio\Support;

use Filament\Schemas\Schema;

final class FilamentSchema
{
    public static function configure(Schema $schema, array $components): Schema
    {
        return $schema->components($components);
    }
}
