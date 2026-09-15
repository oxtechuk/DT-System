<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    /**
     * GET /api/v1/rooms
     * Returns all active rooms with their current session status — for Cashier POS.
     */
    public function index(): JsonResponse
    {
        $rooms = Room::active()
            ->with([
                'activeDeals.customer:id,full_name,phone',
                'activeDeals.workspaceType:id,name,code',
            ])
            ->get();

        $mapped = $rooms->map(function (Room $room) {
            $activeDeals = $room->activeDeals;
            $hasPrivate  = $activeDeals->contains(fn($d) => $d->workspaceType?->code === 'private');
            $hasShared   = $activeDeals->contains(fn($d) => $d->workspaceType?->code === 'shared');

            if ($hasPrivate) {
                $displayStatus = 'private';
            } elseif ($hasShared) {
                $displayStatus = 'shared';
            } else {
                $displayStatus = 'available';
            }

            return [
                'id'             => $room->id,
                'name'           => $room->name,
                'code'           => $room->code,
                'capacity'       => $room->capacity,
                'status'         => $room->status,
                'color'          => $room->display_color,
                'display_status' => $displayStatus,
                'is_available'   => $room->is_available,
                'active_deals'   => $activeDeals->map(fn($d) => [
                    'id'              => $d->id,
                    'deal_number'     => $d->deal_number,
                    'customer'        => [
                        'id'        => $d->customer?->id,
                        'full_name' => $d->customer?->full_name,
                    ],
                    'workspace_type'  => $d->workspaceType?->name,
                    'started_at'      => $d->started_at?->toISOString(),
                    'live_minutes'    => $d->live_duration_minutes,
                ]),
            ];
        });

        return response()->json([
            'data'      => $mapped,
            'summary'   => [
                'total'     => $rooms->count(),
                'available' => $rooms->filter(fn($r) => $r->is_available)->count(),
                'occupied'  => $rooms->filter(fn($r) => !$r->is_available)->count(),
            ],
        ]);
    }
}
