<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Receipt - Vantech Computers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 70% !important;
            }

            .print-container {
                box-shadow: none !important;
                margin: 0 !important;
                position: absolute;
                left: 0;
                top: 0;
                overflow: hidden;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: none !important;
            }
        }

        .scrollbar-hide {
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .print-only {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Top Bar with Back Button -->
    <div class="no-print bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <button onclick="goBackToPOS()"
                class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to POS
            </button>
            <div class="flex gap-2">
                <button onclick="printReceipt()"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </button>
                <button onclick="printAndReturn()"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print & Return
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Container -->
    <div class="container mx-auto p-6 scrollbar-hide" style="max-height: calc(100vh - 80px); overflow-y: auto;">
        <div class="print-container bg-white shadow-lg mx-auto" style="width: 210mm; min-height: 200mm; padding: 10mm;">

            <!-- Print Message (Only shows when printing) -->
            <div class="print-only text-center text-sm text-gray-500 mb-4">
                Printed on: {{ now()->format('M d, Y h:i A') }}
            </div>

            <!-- Header with Blue Line -->
            <div class="border-t-8 border-blue-600 mb-6"></div>

            <!-- Company Info and Logo -->
            <div class="flex justify-between items-start mb-8">
                <div class="w-3/5">
                    <h1 class="text-2xl font-bold text-blue-700 mb-2">VANTECH COMPUTERS TRADING</h1>
                    <p class="text-sm text-gray-700">Van Bryan C. Bardillas - Sole Proprietor</p>
                    <p class="text-sm text-gray-700">Non VAT Reg. TIN 505-374240-00000</p>
                    <p class="text-sm text-gray-700">758 F Purok 3, Brgy. Mintal</p>
                    <p class="text-sm text-gray-700">Davao City, Davao del Sur, 8000</p>
                </div>
                <div class="w-2/5 flex flex-col items-end justify-end">
                    <img src="{{ asset('images/logo.png') }}" class="w-28 h-auto mb-2" />
                    <h3 class="text-lg font-bold text-blue-700 text-right whitespace-nowrap">WARRANTY RECEIPT</h3>
                    <p class="text-xs text-gray-600 mt-1 text-right">Date: <span
                            class="font-semibold">{{ now()->format('m/d/Y') }}</span></p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="mb-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="text-sm"><span class="font-semibold">Name:</span> <span
                                id="receiptCustomerName">{{ $receiptData['customerName'] ?? 'N/A' }}</span></p>
                        <p class="text-sm"><span class="font-semibold">Contact No.:</span> <span
                                id="receiptContactNo">{{ $customerContact ?? 'N/A' }}</span></p>
                        <p class="text-sm"><span class="font-semibold">Payment Method:</span> <span
                                id="receiptPaymentMethod">{{ $receiptData['paymentMethod'] ?? 'N/A' }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm"><span class="font-semibold">Payable to</span></p>
                        <p class="text-sm font-semibold">VANTECH COMPUTERS</p>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="mb-8" id="productTable">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-2 text-sm font-semibold">Description</th>
                            <th class="text-center py-2 text-sm font-semibold">Warranty</th>
                            <th class="text-center py-2 text-sm font-semibold">Qty</th>
                            <th class="text-right py-2 text-sm font-semibold" id="priceHeader">Unit price</th>
                            <th class="text-right py-2 text-sm font-semibold" id="totalPriceHeader">Subtotal price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $receiptSubtotal = 0;
                        @endphp
                        @if(isset($receiptData['items']) && count($receiptData['items']) > 0)
                            @foreach($receiptData['items'] as $item)
                                @php
                                    $receiptSubtotal += $item['subtotal'];
                                @endphp
                                <tr class="border-b border-gray-200">
                                    <td class="py-2 text-sm">{{ $item['productName'] }}</td>
                                    <td class="text-center py-2 text-sm">{{ $item['warranty'] ?? '-' }}</td>
                                    <td class="text-center py-2 text-sm">{{ $item['quantity'] }}</td>
                                    <td class="text-right py-2 text-sm priceColumn">₱{{ number_format($item['price'], 2) }}</td>
                                    <td class="text-right py-2 text-sm totalPriceColumn">
                                        ₱{{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-2 text-sm text-center text-gray-500">No items in receipt</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Note and Totals -->
            <div class="flex justify-between mb-8">
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Note:</p>
                    <p class="text-sm text-gray-600 mt-1">Thank you for your purchase!</p>
                </div>
                <div class="w-1/2">
                    <div class="flex justify-between mb-2 border-t border-gray-300 pt-2">
                        <span class="text-sm font-bold">Total Price</span>
                        <span class="text-sm font-semibold">₱{{ number_format($receiptSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm italic text-red-700">Discount</span>
                        <span
                            class="text-sm text-red-700 font-semibold">-₱{{ number_format($receiptData['discount'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-800 pt-2">
                        <span class="text-lg font-bold">Total Amount</span>
                        <span
                            class="text-xl font-bold text-pink-600">₱{{ number_format($receiptData['total'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Warranty Terms -->
            <div class="mb-2">
                <h3 class="text-lg font-bold mb-3">WARRANTY TERMS & CONDITIONS</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    <p>1. A warranty which indicates the date it was sold (month, day, year) or a serial number and a
                        warranty slip stating the product's warranty start and end date is used to identify each item
                        sold by Vantech Computers.</p>
                    <p>2. Keep all the boxes.</p>
                    <p>3. All Vantech products carry standard one (1) year warranty except:</p>
                    <p class="pl-4">Fans, Lower Brand PSU, UPS, Peripherals (Keyboard, mouse, mousepad), AVR,<br>
                        Generic/Gaming Case, Headset, Speakers, WiFi Dongle, Webcam, LAN Wire, Flash drive,<br>
                        Software (OS, MS Office, etc).</p>
                </div>
            </div>

            <!-- Signatures -->
            <div class="flex justify-end mt-4">
                <div class="justify-end text-right">
                    <p class="text-sm mb-8">Prepared by:</p>
                    <p class="text-sm font-semibold">{{ strtoupper($preparedBy ?? 'N/A') }}</p>
                    <div class="w-30 border-t border-gray-400"></div>
                    <p class="text-sm text-gray-600 mt-0.5">{{ $preparedByRole ?? 'N/A' }}</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        /**
         * Print receipt
         */
        function printReceipt() {
            window.print();
        }

        /**
         * Print receipt and return to POS
         */
        function printAndReturn() {
            // Print first
            window.print();

            // Wait a moment for print dialog to open, then show return confirmation
            setTimeout(() => {
                Swal.fire({
                    title: 'Return to POS?',
                    text: 'Do you want to return to the Point of Sale system?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Return to POS',
                    cancelButtonText: 'Stay on Receipt',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        goBackToPOS();
                    }
                });
            }, 1000);
        }

        /**
         * Go back to POS system
         */
        function goBackToPOS() {
            window.location.href = "{{ route('pos.itemlist') }}";
        }

        // Auto focus on print button when page loads
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Receipt data loaded:', @json($receiptData));

            // Hide unit price and total price columns if displayTotalOnly is true
            const receiptData = @json($receiptData);
            if (receiptData.displayTotalOnly) {
                // Hide headers
                const priceHeader = document.getElementById('priceHeader');
                const totalPriceHeader = document.getElementById('totalPriceHeader');
                if (priceHeader) priceHeader.style.display = 'none';
                if (totalPriceHeader) totalPriceHeader.style.display = 'none';

                // Hide all price cells in the table
                const priceColumns = document.querySelectorAll('.priceColumn');
                const totalPriceColumns = document.querySelectorAll('.totalPriceColumn');
                priceColumns.forEach(cell => cell.style.display = 'none');
                totalPriceColumns.forEach(cell => cell.style.display = 'none');
            }
        });
    </script>
</body>

</html>