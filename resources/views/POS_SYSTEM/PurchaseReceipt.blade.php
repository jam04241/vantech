<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Receipt - Vantech Computers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 70% !important;
                /* reduce font size */

            }

            /* ARRANGEMENT FOR PRINTING*/
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
        }

        .scrollbar-hide {
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Top Bar with Back Button -->
    <div class="no-print bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <button onclick="window.location.href = '{{ route('pos.itemlist') }}'"
                class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-500 transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
        </div>
    </div>

    <!-- Receipt Container -->
    <div class="container mx-auto p-6 scrollbar-hide" style="max-height: calc(100vh - 80px); overflow-y: auto;">
        <div class="print-container bg-white shadow-lg mx-auto" style="width: 210mm; min-height: 200mm; padding: 10mm;">

            <!-- Header with Blue Line -->
            <div class="border-t-8 border-blue-600 mb-6"></div>

            <!-- Company Info and Logo -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-blue-700 mb-2">VANTECH COMPUTERS TRADING</h1>
                    <p class="text-sm text-gray-700">Van Bryan C. Bardillas - Sole Proprietor</p>
                    <p class="text-sm text-gray-700">Non VAT Reg. TIN 505-374240-00000</p>
                    <p class="text-sm text-gray-700">758 F Purok 3, Brgy. Mintal</p>
                    <p class="text-sm text-gray-700">Davao City, Davao del Sur, 8000</p>
                </div>
                <div class="text-right">

                    <img src="{{ asset('images/logo.png') }}" class="w-40 mx-auto py-3 mb-2" />
                    <h3 class="text-lg font-bold text-blue-700">WARRANTY RECEIPT</h3>
                    <p class="text-sm text-gray-700 mt-2">Date: <span
                            class="font-semibold">{{ now()->format('m/d/Y') }}</span></p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="mb-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="text-sm"><span class="font-semibold">Name:</span> <span
                                id="receiptCustomerName">IVAN</span></p>
                        <p class="text-sm"><span class="font-semibold">Payment Method:</span> <span
                                id="receiptPaymentMethod">Cash</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm"><span class="font-semibold">Payable to</span></p>
                        <p class="text-sm font-semibold">VANTECH COMPUTERS</p>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="mb-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-2 text-sm font-semibold">Description</th>
                            <th class="text-center py-2 text-sm font-semibold">Warranty</th>
                            <th class="text-center py-2 text-sm font-semibold">Qty</th>
                            <th class="text-right py-2 text-sm font-semibold">Unit price</th>
                            <th class="text-right py-2 text-sm font-semibold">Total price</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- Note and Totals -->
            <div class="flex justify-between mb-8">
                <div class="w-1/2">
                    <p class="text-sm font-semibold">Note:</p>
                </div>
                <div class="w-1/2">
                    <div class="flex justify-between mb-2 border-t border-gray-300 pt-2">
                        <span class="text-sm font-bold">Total Price</span>
                        <span class="text-sm font-semibold" id="receiptSubtotalAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm italic text-red-700">Discount</span>
                        <span class="text-sm font-semibold" id="receiptDiscountAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium">VAT(3%)</span>
                        <span class="text-sm font-semibold" id="receiptVATAmount">₱0.00</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-800 pt-2">
                        <span class="text-xl font-bold text-pink-600" id="receiptTotalAmount">₱0.00</span>
                    </div>
                </div>
            </div>

            <!-- Warranty Terms -->
            <div class="mb-8">
                <h3 class="text-lg font-bold mb-3">WARRANTY TERMS & CONDITIONS</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    <p>1. A warranty which indicates the date it was sold (month, day, year) or a serial number and a
                        warranty slip stating the product's warranty start and end date is used to identify each item
                        sold by Vantech Computers.</p>
                    <p>2. Keep all the boxes.</p>
                    <p>3. All Vantech products carry standard one (1) year warranty except:</p>
                    <p class="pl-4">- Fans, Lower Brand PSU, UPS, Peripherals (Keyboard, mouse, mousepad), AVR,<br>
                        Generic/Gaming Case, Headset, Speakers, WiFi Dongle, Webcam, LAN Wire, Flash drive,<br>
                        Software (OS, MS Office, etc</p>
                </div>
            </div>

            <!-- Signatures -->
            <div class="flex justify-end mt-12">
                <div class="text-right">
                    <p class="text-sm mb-8">Prepared by: ANDREW SUICO</p>
                    <p class="text-sm">Received by: _______________________</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Load receipt data from sessionStorage
        document.addEventListener('DOMContentLoaded', function () {
            const receiptDataJson = sessionStorage.getItem('receiptData');

            if (receiptDataJson) {
                try {
                    const receiptData = JSON.parse(receiptDataJson);

                    // Update customer info
                    document.getElementById('receiptCustomerName').textContent = receiptData.customerName || 'N/A';
                    document.getElementById('receiptPaymentMethod').textContent = receiptData.paymentMethod || 'N/A';

                    // Update product table with order items - merge duplicates
                    const tbody = document.querySelector('tbody');
                    if (tbody && receiptData.items && receiptData.items.length > 0) {
                        tbody.innerHTML = '';

                        // Merge items with same product name, price, AND warranty
                        const mergedItems = {};
                        receiptData.items.forEach(item => {
                            const key = `${item.productName}|${item.price}|${item.warranty}`;
                            if (mergedItems[key]) {
                                // Add to existing item
                                mergedItems[key].quantity = parseInt(mergedItems[key].quantity) + parseInt(item.quantity);
                                mergedItems[key].subtotal = (parseFloat(mergedItems[key].price) * mergedItems[key].quantity).toFixed(2);
                            } else {
                                // Create new item
                                mergedItems[key] = {
                                    productName: item.productName,
                                    price: item.price,
                                    warranty: item.warranty,
                                    quantity: parseInt(item.quantity),
                                    subtotal: (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)
                                };
                            }
                        });

                        // Display merged items
                        let receiptSubtotal = 0;
                        Object.values(mergedItems).forEach(item => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200';
                            const itemSubtotal = parseFloat(item.subtotal);
                            receiptSubtotal += itemSubtotal;

                            row.innerHTML = `
                                <td class="py-2 text-sm">${item.productName}</td>
                                <td class="text-center py-2 text-sm">${item.warranty || '-'}</td>
                                <td class="text-center py-2 text-sm">${item.quantity}</td>
                                <td class="text-right py-2 text-sm">₱${parseFloat(item.price).toFixed(2)}</td>
                                <td class="text-right py-2 text-sm">₱${itemSubtotal.toFixed(2)}</td>
                            `;
                            tbody.appendChild(row);
                        });

                        // // Add subtotal row (DELETED)
                        // const subtotalRow = document.createElement('tr');
                        // subtotalRow.className = 'border-t-2 border-gray-300 font-semibold';
                        // subtotalRow.innerHTML = `
                        //     <td colspan="4" class="py-2 text-sm text-right">Subtotal:</td>
                        //     <td class="text-right py-2 text-sm">₱${receiptSubtotal.toFixed(2)}</td>
                        // `;
                        // tbody.appendChild(subtotalRow);
                    }

                    // Calculate and update totals section
                    const subtotal = parseFloat(receiptData.subtotal) || 0;
                    const discount = parseFloat(receiptData.discount) || 0;
                    const vat = (subtotal - discount) * 0.03;
                    const total = subtotal - discount + vat;

                    // Update subtotal, VAT, discount, and total amounts
                    document.getElementById('receiptSubtotalAmount').textContent = '₱' + subtotal.toFixed(2);
                    document.getElementById('receiptVATAmount').textContent = '₱' + vat.toFixed(2);
                    document.getElementById('receiptDiscountAmount').textContent = '₱' + discount.toFixed(2);
                    document.getElementById('receiptTotalAmount').textContent = '₱' + total.toFixed(2);

                } catch (error) {
                    console.error('Error loading receipt data:', error);
                }
            }
        });
    </script>

</body>

</html>