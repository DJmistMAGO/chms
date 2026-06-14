<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [

            // ── Standard Room (₱1,500) ─────────────────────────
            // 1st Floor: S101 – S107
            ['room_no' => 'S101', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S102', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S103', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S104', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S105', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S106', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],
            ['room_no' => 'S107', 'room_type' => 'Standard Room', 'floor' => 1, 'base_price' => 1500],

            // 2nd Floor: S201 – S202
            ['room_no' => 'S201', 'room_type' => 'Standard Room', 'floor' => 2, 'base_price' => 1500],
            ['room_no' => 'S202', 'room_type' => 'Standard Room', 'floor' => 2, 'base_price' => 1500],

            // 4th Floor: S401 – S405
            ['room_no' => 'S401', 'room_type' => 'Standard Room', 'floor' => 4, 'base_price' => 1500],
            ['room_no' => 'S402', 'room_type' => 'Standard Room', 'floor' => 4, 'base_price' => 1500],
            ['room_no' => 'S403', 'room_type' => 'Standard Room', 'floor' => 4, 'base_price' => 1500],
            ['room_no' => 'S404', 'room_type' => 'Standard Room', 'floor' => 4, 'base_price' => 1500],
            ['room_no' => 'S405', 'room_type' => 'Standard Room', 'floor' => 4, 'base_price' => 1500],

            // ── Standard Premium Room (₱1,900) ──────────────────
            // 1st Floor: SP101 – SP102
            ['room_no' => 'SP101', 'room_type' => 'Standard Premium Room', 'floor' => 1, 'base_price' => 1900],
            ['room_no' => 'SP102', 'room_type' => 'Standard Premium Room', 'floor' => 1, 'base_price' => 1900],

            // 2nd Floor: SP201 – SP202
            ['room_no' => 'SP201', 'room_type' => 'Standard Premium Room', 'floor' => 2, 'base_price' => 1900],
            ['room_no' => 'SP202', 'room_type' => 'Standard Premium Room', 'floor' => 2, 'base_price' => 1900],

            // 4th Floor: SP401 – SP406
            ['room_no' => 'SP401', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],
            ['room_no' => 'SP402', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],
            ['room_no' => 'SP403', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],
            ['room_no' => 'SP404', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],
            ['room_no' => 'SP405', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],
            ['room_no' => 'SP406', 'room_type' => 'Standard Premium Room', 'floor' => 4, 'base_price' => 1900],

            // ── Family Room (₱2,700) ────────────────────────────
            // 2nd Floor: F201 – F204
            ['room_no' => 'F201', 'room_type' => 'Family Room', 'floor' => 2, 'base_price' => 2700],
            ['room_no' => 'F202', 'room_type' => 'Family Room', 'floor' => 2, 'base_price' => 2700],
            ['room_no' => 'F203', 'room_type' => 'Family Room', 'floor' => 2, 'base_price' => 2700],
            ['room_no' => 'F204', 'room_type' => 'Family Room', 'floor' => 2, 'base_price' => 2700],

        ];

        foreach ($rooms as $room) {
            Room::create([
                'room_no'    => $room['room_no'],
                'room_type'  => $room['room_type'],
                'floor'      => $room['floor'],
                'base_price' => $room['base_price'],
                'status'     => 'available',
            ]);
        }

        $this->command->info('✓ 28 rooms seeded successfully.');
        $this->command->table(
            ['Room No', 'Type', 'Floor', 'Price'],
            collect($rooms)->map(fn($r) => [
                $r['room_no'],
                $r['room_type'],
                'Floor ' . $r['floor'],
                '₱' . number_format($r['base_price']),
            ])->toArray()
        );
    }
}
