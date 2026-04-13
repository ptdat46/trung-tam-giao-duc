<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // Tầng 1 — Phòng học lý thuyết
            ['name' => 'P.101', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.102', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.103', 'status' => Room::STATUS_IN_USE],
            ['name' => 'P.104', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.105', 'status' => Room::STATUS_MAINTENANCE],

            // Tầng 2 — Phòng máy / thực hành
            ['name' => 'P.201', 'status' => Room::STATUS_IN_USE],
            ['name' => 'P.202', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.203', 'status' => Room::STATUS_IN_USE],
            ['name' => 'P.204', 'status' => Room::STATUS_MAINTENANCE],

            // Tầng 3 — Phòng hội thảo
            ['name' => 'P.301', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.302', 'status' => Room::STATUS_EMPTY],
            ['name' => 'P.303', 'status' => Room::STATUS_IN_USE],

            // Phòng online / phòng họp
            ['name' => 'Phòng họp A', 'status' => Room::STATUS_EMPTY],
            ['name' => 'Phòng họp B', 'status' => Room::STATUS_IN_USE],
            ['name' => 'Phòng thi', 'status' => Room::STATUS_MAINTENANCE],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['name' => $room['name']], $room);
        }
    }
}
