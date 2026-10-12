<?php

namespace Database\Factories;

use App\Models\ApprovalWorkflow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalWorkflowFactory extends Factory
{
    protected $model = ApprovalWorkflow::class;

    public function definition(): array
    {
        return [
            'morphable_type' => 'App\\Models\\Variety',
            'morphable_id' => 1,
            'submitted_by' => User::factory(),
            'reviewer_id' => null,
            'status' => $this->faker->randomElement(['pending', 'revision', 'approved', 'rejected']),
            'payload' => [
                'name' => 'Updated Variety Name',
                'attributes' => [
                    'color' => 'Green'
                ]
            ],
            'reviewer_remarks' => $this->faker->sentence,
        ];
    }
}
