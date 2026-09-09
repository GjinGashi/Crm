<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement([
                'Todo',
                'In Progress',
                'Completed',
                'Canceled',
            ]),
            'priority' => fake()->randomElement([
                'Low',
                'Medium',
                'High',
                'Urgent',
            ]),
            'start_time' => fake()->dateTimeBetween('now', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'due_date' => fake()->date(),
        ];
    }
}
