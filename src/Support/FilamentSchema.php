<?php

namespace HkDevs\CodeForgeStudio\Support;

use Filament\Schemas\Schema;

/**
 * Bridges Filament v3 Form::schema() and v4/v5 Schema::components().
 */
final class FilamentSchema
{
    public static function configure(object $schema, array $components): object
    {
        if (class_exists(Schema::class) && $schema instanceof Schema) {
            return $schema->components($components);
        }

        return $schema->schema($components);
    }
}
