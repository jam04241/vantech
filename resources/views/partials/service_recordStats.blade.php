{{-- Statistics Cards (3 per row matching original design) --}}
<div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Services</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['total']) }}</p>
            @if($startDate && $endDate)
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border border-yellow-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-yellow-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['pending']) }}</p>
            @if($startDate && $endDate)
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-green-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Completed</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['completed']) }}</p>
            @if($startDate && $endDate)
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">In Progress</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['in_progress']) }}</p>
            @if($startDate && $endDate)
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border border-orange-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">On Hold</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['on_hold']) }}</p>
            @if($startDate && $endDate)
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 border border-indigo-200 shadow-sm">
    <div class="flex items-center">
        <div class="p-3 bg-indigo-500 rounded-xl shadow-sm">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">₱{{ number_format($statistics['total_revenue'], 2) }}</p>
            <p class="text-xs text-gray-400">Based on Date Range</p>
        </div>
    </div>
</div>