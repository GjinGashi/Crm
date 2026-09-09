<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement([
                'Planning',
                'In Progress',
                'On Hold',
                'Completed',
                'Canceled',
            ]),
            'priority' => fake()->randomElement([
                'Low',
                'Medium',
                'High',
                'Urgent',
            ]),
            'start_date' => fake()->date(),
            'due_date' => fake()->date(),
            'budget' => fake()->randomFloat(2, 100, 10000),
            'archived_at' => null,
        ];
    }
}
