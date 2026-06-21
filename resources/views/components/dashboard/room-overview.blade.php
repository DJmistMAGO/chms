@props(['rooms', 'totalRooms'])

<div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm">
    <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h3 class="font-semibold text-gray-600 dark:text-white">Room Overview</h3>
            <p class="mt-1 text-sm text-gray-500">Live status of all rooms.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-green-400"></span> Available</span>
            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400"></span> Occupied</span>
            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-yellow-400"></span> Maintenance</span>
            <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-400"></span> Reserved</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-sm">
            <thead>
                <tr class="border-b border-white/[0.06] text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                    <th class="pb-3 pr-4">Room</th>
                    <th class="pb-3 pr-4">Room Type</th>
                    <th class="pb-3 pr-4">Status</th>
                    <th class="pb-3 pr-4">Current Guest</th>
                    <th class="pb-3 pr-4">Check-in</th>
                    <th class="pb-3">Check-out</th>
                </tr>
            </thead>
            @php
                $statusColors = [
                    'Available' => ['class' => 'bg-green-400/20 text-green-600', 'dot' => 'bg-green-600', 'hover' => 'hover:bg-green-200/30'],
                    'Occupied' => ['class' => 'bg-red-400/20 text-red-600', 'dot' => 'bg-red-600', 'hover' => 'hover:bg-red-200/30'],
                    'Maintenance' => ['class' => 'bg-yellow-400/20 text-yellow-600', 'dot' => 'bg-yellow-600', 'hover' => 'hover:bg-yellow-200/30'],
                    'Reserved' => ['class' => 'bg-blue-400/20 text-blue-600', 'dot' => 'bg-blue-600', 'hover' => 'hover:bg-blue-200/30'],
                ];
            @endphp
            <tbody class="divide-y divide-white/[0.04]">
                @foreach ($rooms as $room)
                    @php
                        $status = $room->status ?? 'Available';
                        $styles = $statusColors[$status] ?? ['class' => 'bg-gray-400/20 text-gray-600', 'dot' => 'bg-gray-600', 'hover' => 'hover:bg-gray-200/30'];
                    @endphp
                    <tr class="group transition {{ $styles['hover'] }} dark:hover:bg-white/[0.02]">
                        <td class="py-4 px-4 font-semibold text-gray-800 dark:text-white">{{ $room->room_no }}</td>
                        <td class="py-4 pr-4 text-gray-400">{{ $room->room_type }}</td>
                        <td class="py-4 pr-4">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium {{ $styles['class'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $styles['dot'] }}"></span>
                                {{ $status }}
                            </span>
                        </td>
                        <td class="py-4 pr-4 text-gray-400">{{ optional($room->currentBooking->user ?? null)->name ?? '—' }}</td>
                        <td class="py-4 pr-4 text-gray-400">{{ optional($room->currentBooking->check_in_date ?? null)->format('M j, Y') ?? '—' }}</td>
                        <td class="py-4 text-gray-400">{{ optional($room->currentBooking->check_out_date ?? null)->format('M j, Y') ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <x-dashboard.pagination :rooms="$rooms" />

    </div>
</div>
