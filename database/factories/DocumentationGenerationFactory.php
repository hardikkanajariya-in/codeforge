<?php

namespace HkDevs\CodeForgeStudio\Database\Factories;

use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentationGenerationFactory extends Factory
{
    protected $model = DocumentationGeneration::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'format' => $this->faker->randomElement(['markdown', 'html', 'pdf', 'json']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']),
            'file_path' => null,
            'options' => [
                'include_examples' => $this->faker->boolean(),
                'include_api_docs' => $this->faker->boolean(),
                'include_troubleshooting' => $this->faker->boolean(),
            ],
            'generated_at' => $this->faker->optional()->dateTime(),
            'file_size' => $this->faker->optional()->numberBetween(1024, 1048576), // 1KB to 1MB
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'file_path' => 'documentation/'.$this->faker->uuid().'.'.$attributes['format'],
                'generated_at' => now(),
                'file_size' => $this->faker->numberBetween(1024, 1048576),
            ];
        });
    }

    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
                'file_path' => null,
                'generated_at' => null,
                'file_size' => null,
                'error_message' => $this->faker->sentence(),
            ];
        });
    }

    public function processing(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processing',
                'file_path' => null,
                'generated_at' => null,
                'file_size' => null,
            ];
        });
    }

    public function markdown(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'format' => 'markdown',
                'file_path' => $attributes['status'] === 'completed'
                    ? 'documentation/'.$this->faker->uuid().'.md'
                    : null,
            ];
        });
    }

    public function html(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'format' => 'html',
                'file_path' => $attributes['status'] === 'completed'
                    ? 'documentation/'.$this->faker->uuid().'.html'
                    : null,
            ];
        });
    }

    public function pdf(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'format' => 'pdf',
                'file_path' => $attributes['status'] === 'completed'
                    ? 'documentation/'.$this->faker->uuid().'.pdf'
                    : null,
            ];
        });
    }

    public function json(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'format' => 'json',
                'file_path' => $attributes['status'] === 'completed'
                    ? 'documentation/'.$this->faker->uuid().'.json'
                    : null,
            ];
        });
    }
}
