<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Receipt - Vantech Computers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .print-container {
                box-shadow: none !important;
                margin: 0 !important;
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
            <button onclick="history.back()"
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
        <div class="print-container bg-white shadow-lg mx-auto" style="width: 210mm; min-height: 297mm; padding: 20mm;">

            <!-- Header with Blue Line -->
            <div class="border-t-8 border-blue-600 mb-6"></div>

            <!-- Company Info and Logo -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-blue-700 mb-2">VANTECH COMPUTERS TRADING</h1>
                    <p class="text-sm text-gray-700">Ivan Bryan C. Randisar - Sole Proprietor</p>
                    <p class="text-sm text-gray-700">Non VAT Reg. TIN 505-374240-00000</p>
                    <p class="text-sm text-gray-700">7598 P7 Bangguingui St., Mintal</p>
                    <p class="text-sm text-gray-700">8000 City of Davao, Davao Del Sur Philippines</p>
                </div>
                <div class="text-right">
                    <div class="bg-gray-800 text-white px-6 py-3 mb-2">
                        <h2 class="text-xl font-bold">VANTECH</h2>
                        <p class="text-xs">COMPUTERS</p>
                    </div>
                    <h3 class="text-lg font-bold text-blue-700">WARRANTY RECEIPT</h3>
                    <p class="text-sm text-gray-700 mt-2">Date: <span
                            class="font-semibold">{{ now()->format('m/d/Y') }}</span></p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="mb-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="text-sm"><span class="font-semibold">Invoice for</span></p>
                        <p class="text-sm"><span class="font-semibold">NAME:</span> IVAN</p>
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
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">RYZEN 7 7700</td>
                            <td class="text-center py-2 text-sm">1 YEAR</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">MSI B650M GAMING PLUS WIFI</td>
                            <td class="text-center py-2 text-sm">1 YEAR</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">TFORCE 32GB (2X16GB) 6000MHZ</td>
                            <td class="text-center py-2 text-sm">1 YEAR</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">FASPEED 1TB M.2 NVME SSD GEN</td>
                            <td class="text-center py-2 text-sm">1 YEAR</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">ASUS DUAL RTX 5060 8GB</td>
                            <td class="text-center py-2 text-sm">1 YEAR</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">COUGAR GEMINI CASE M</td>
                            <td class="text-center py-2 text-sm">30 DAYS</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">ESGAMING 750W 80+ BRONZE PSU</td>
                            <td class="text-center py-2 text-sm">30 DAYS</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">3X RGB FANS W/ REMOTE</td>
                            <td class="text-center py-2 text-sm">7 DAYS</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">THERMALRIGHT ASSASSIN X 120 DIGITAL</td>
                            <td class="text-center py-2 text-sm">7 DAYS</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-2 text-sm">LOGITECH G102 GAMING MOUSE</td>
                            <td class="text-center py-2 text-sm">7 DAYS</td>
                            <td class="text-center py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm"></td>
                            <td class="text-right py-2 text-sm">₱0.00</td>
                        </tr>
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
                        <span class="text-sm">Subtotal</span>
                        <span class="text-sm font-semibold">₱0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm">Adjustments</span>
                        <span class="text-sm"></span>
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-800 pt-2">
                        <span class="text-xl font-bold text-pink-600">₱60,400.00</span>
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
                    <p class="text-sm mb-8">Prepared by: ANDREW GUYO</p>
                    <p class="text-sm">Received by: _______________________</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>