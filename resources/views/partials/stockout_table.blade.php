{{-- partials/stockout_table.blade.php --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prev Qty</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Out</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <!-- Example Stock-Out Records -->
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Smartphone X5</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">15</div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-red-600">-2</div>
                </td>
                <td class="px-4 py-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Return
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">
                    Oct 18, 2023
                </td>
                <td class="px-4 py-3 text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 text-xs">View Details</button>
                </td>
            </tr>
             <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Smartphone X5</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">15</div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-red-600">-2</div>
                </td>
                <td class="px-4 py-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Damage
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">
                    Oct 18, 2023
                </td>
                <td class="px-4 py-3 text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 text-xs">View Details</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Smartphone X5</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-gray-900">15</div>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm font-medium text-red-600">-2</div>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Purchase
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">
                    Oct 18, 2023
                </td>
                <td class="px-4 py-3 text-sm font-medium">
                    <button class="text-indigo-600 hover:text-indigo-900 text-xs">View Details</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

