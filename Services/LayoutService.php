<?php

namespace App\Services;

use App\Models\Layout;
use App\Models\LayoutEntity;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class LayoutService implements LayoutServiceInterface
{
    public function createLayout(array $data)
    {
        return DB::transaction(function () use ($data) {
            $layout = Layout::create([
                'module_id' => $data['module_id'],
                'name' => $data['name'],
            ]);

            foreach ($data['entities'] as $entity) {
                $this->addEntityToLayout($layout->id, $entity);
            }

            return $layout;
        });
    }

    public function updateLayout(int $layoutId, array $data)
    {
        $layout = Layout::findOrFail($layoutId);
        $layout->update([
            'name' => $data['name'],
        ]);

        foreach ($data['entities'] as $entity) {
            if (isset($entity['id'])) {
                $this->updateEntity($entity['id'], $entity);
            } else {
                $this->addEntityToLayout($layoutId, $entity);
            }
        }

        return $layout;
    }

    public function getLayout(int $layoutId)
    {
        return Layout::with(['entities'])->findOrFail($layoutId);
    }

    public function deleteLayout(int $layoutId)
    {
        $layout = Layout::findOrFail($layoutId);
        $layout->delete();
    }

    public function addEntityToLayout(int $layoutId, array $data)
    {
        $layoutEntity = LayoutEntity::create([
            'layout_id' => $layoutId,
            'type' => $data['type'],
            'x-position' => $data['x-position'],
            'y-position' => $data['y-position'],
            'rotation' => $data['rotation'],
        ]);

        // Handle specific types of entities (e.g., seats, cabins, partitions)
        if ($data['type'] === 'Seat') {
            $this->createSeat($layoutEntity->id, $data);
        } elseif ($data['type'] === 'Cabin' || $data['type'] === 'Conference') {
            $this->createCabinOrConferenceRoom($layoutEntity->id, $data);
        } elseif ($data['type'] === 'Partition') {
            $this->createPartition($layoutEntity->id, $data);
        }
    }

    public function updateEntity(int $entityId, array $data)
    {
        $entity = LayoutEntity::findOrFail($entityId);
        $entity->update([
            'type' => $data['type'],
            'x-position' => $data['x-position'],
            'y-position' => $data['y-position'],
            'rotation' => $data['rotation'],
        ]);

        // Update specific types of entities if needed
        // (e.g., update Seat, Cabin, Partition)
    }

    public function deleteEntity(int $entityId)
    {
        $entity = LayoutEntity::findOrFail($entityId);
        $entity->delete();
    }

    // public function getSeat(int $seatId)
    // {
    //     return Seat::findOrFail($seatId);
    // }

    // public function bookSeat(int $seatId, int $userId)
    // {
    //     $seat = Seat::findOrFail($seatId);
    //     $seat->update([
    //         'booked_by_user_id' => $userId,
    //         'status' => 'booked',
    //     ]);
    // }

    // public function cancelBooking(int $seatId)
    // {
    //     $seat = Seat::findOrFail($seatId);
    //     $seat->update([
    //         'booked_by_user_id' => null,
    //         'status' => 'available',
    //     ]);
    // }

    private function createSeat(int $layoutEntityId, array $data)
    {
        Seat::create([
            'seat_number' => $data['seat_number'],
            'module_id' => $data['module_id'],
            'layout_entities_id' => $layoutEntityId,
            'status' => $data['status'] ?? 'available',
        ]);
    }

    private function createCabinOrConferenceRoom(int $layoutEntityId, array $data)
    {
        // Implementation for cabins and conference rooms
    }

    private function createPartition(int $layoutEntityId, array $data)
    {
        // Implementation for partitions
    }
}
