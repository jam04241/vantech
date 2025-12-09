@if($services->isEmpty())
    <div class="col-span-2 text-center py-16 text-gray-400">
        <div class="mb-4">
            <i class="fas fa-search text-6xl opacity-30"></i>
        </div>
        <p class="text-lg font-bold text-gray-600 mb-2">No Services Found</p>
        <p class="text-sm text-gray-500">Try adjusting your search or filters</p>
    </div>
@else
    @foreach($services as $index => $service)
        @php
            $statusColors = [
                'Pending' => 'bg-yellow-100 text-yellow-800',
                'In Progress' => 'bg-blue-100 text-blue-800',
                'On Hold' => 'bg-orange-100 text-orange-800',
                'Completed' => 'bg-green-100 text-green-800',
                'Canceled' => 'bg-red-100 text-red-800'
            ];
            $statusColor = $statusColors[$service->status] ?? 'bg-gray-100 text-gray-800';
            $customerName = $service->customer ? $service->customer->first_name . ' ' . $service->customer->last_name : 'No Customer';
            $serviceTypeName = $service->serviceType ? $service->serviceType->name : 'No Service Type';
        @endphp

        <div class="service-card bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg transition cursor-pointer"
            data-service-id="{{ $service->id }}">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-sm mb-1">
                        #{{ $index + 1 }} - {{ $serviceTypeName }}
                    </h3>
                    <p class="text-xs text-gray-600 mb-1">
                        <i class="fas fa-user mr-1"></i>{{ $customerName }}
                    </p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                    <i class="fas fa-info-circle mr-1"></i>{{ $service->status }}
                </span>
            </div>
            <div class="text-xs space-y-1 mb-2">
                <p class="text-gray-600">
                    <span class="font-semibold">Type of Item:</span> {{ $service->type ?? '-' }}
                </p>
                <p class="text-gray-600">
                    <span class="font-semibold">Brand:</span> {{ $service->brand ?? '-' }}
                </p>
                <p class="text-gray-600">
                    <span class="font-semibold">Model:</span> {{ $service->model ?? '-' }}
                </p>
                <p class="text-gray-600">
                    <span class="font-semibold">Service Fee:</span> ₱{{ number_format($service->total_price ?? 0, 2) }}
                </p>
            </div>
            <p class="text-xs text-gray-700 border-t pt-2 line-clamp-2">
                {{ $service->description ?? '-' }}
            </p>
        </div>
    @endforeach
@endif