<?php

namespace Database\Factories;

use App\Enums\VideoAppointmentStatus;
use App\Models\User;
use App\Models\VideoAppointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoAppointment>
 */
class VideoAppointmentFactory extends Factory
{
    protected $model = VideoAppointment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'phone' => '+91'.fake()->numberBetween(6000000000, 9999999999),
            'preferred_date' => now()->addDays(fake()->numberBetween(1, 14))->format('Y-m-d'),
            'preferred_time' => fake()->randomElement(['10:30 AM - 11:30 AM', '02:00 PM - 03:00 PM', '04:30 PM - 05:30 PM', '06:00 PM - 07:00 PM']),
            'note' => 'Looking for traditional contrast border Kanjeevaram sarees for wedding reception.',
            'status' => VideoAppointmentStatus::Pending,
        ];
    }
}
