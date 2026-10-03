<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomManagementValidationTest extends Tests\TestCase
{
    use RefreshDatabase;

    public function test_it_updates_the_base_price_without_changing_the_existing_room_type(): void
    {
        $this->withoutMiddleware();

        $room = \App\Models\Room::create([
            'room_no' => '101',
            'floor' => '1',
            'room_type' => 'Standard Room',
            'base_price' => '1500.00',
            'status' => 'Available',
        ]);

        $response = $this->from(route('room.index'))->put(route('room.update', $room), [
            'room_no' => '101',
            'floor' => '1',
            'base_price' => '1800.50',
            'status' => 'Available',
            '_form' => 'edit-room',
        ]);

        $response->assertRedirect(route('room.index'));
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'room_no' => '101',
            'room_type' => 'Standard Room',
            'base_price' => '1800.50',
        ]);
    }

    public function test_it_accepts_the_maximum_room_base_price_and_rejects_values_above_it(): void
    {
        $this->withoutMiddleware();

        $response = $this->from(route('room.index'))->post(route('room.store'), [
            'room_no' => '901',
            'floor' => '1',
            'room_type' => 'Standard Room',
            'base_price' => '99999999.99',
            'status' => 'Available',
            '_form' => 'add-room',
        ]);

        $response->assertRedirect(route('room.index'));
        $this->assertDatabaseHas('rooms', [
            'room_no' => '901',
            'base_price' => '99999999.99',
        ]);

        $response = $this->from(route('room.index'))->post(route('room.store'), [
            'room_no' => '902',
            'floor' => '1',
            'room_type' => 'Standard Room',
            'base_price' => '100000000',
            'status' => 'Available',
            '_form' => 'add-room',
        ]);

        $response->assertSessionHasErrors('base_price');
        $this->assertDatabaseMissing('rooms', ['room_no' => '902']);
    }
}
