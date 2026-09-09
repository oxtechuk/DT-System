<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Room A', 'code' => 'A', 'status' => 'active', 'color' => '#6366f1'],
            ['name' => 'Room B', 'code' => 'B', 'status' => 'active', 'color' => '#10b981'],
            ['name' => 'Room C', 'code' => 'C', 'status' => 'active', 'color' => '#f59e0b'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['code' => $room['code']], $room);
        }

        $this->command->info('Rooms seeded. Add real rooms as needed.');
    }
}
