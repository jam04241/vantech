<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Acknowledgement Letter - Vantech Computers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
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
            <button onclick="goBack()"
                class="inline-flex items-center gap-2 bg-[#151F28] text-white px-4 py-2 rounded-lg shadow hover:bg-[#0f161e] focus:ring-2 focus:ring-[#151F28] transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Services
            </button>
            <div class="flex gap-2">
                <button onclick="printLetter()"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Letter
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

    <!-- Letter Container -->
    <div class="container mx-auto p-6 scrollbar-hide" style="max-height: calc(100vh - 80px); overflow-y: auto;">
        <div class="print-container bg-white shadow-lg mx-auto" style="width: 210mm; min-height: auto; padding: 10mm;">

            <!-- Print Message -->
            <div class="print-only text-center text-xs text-gray-500 mb-4">
                Printed on: <span id="printDate"></span>
            </div>

            <!-- Header with Blue Line -->
            <div class="border-t-4 border-blue-600 mb-4"></div>

            <!-- Company Info and Logo -->
            <div class="flex justify-between items-start mb-6">
                <div class="w-3/5">
                    <h1 class="text-2xl font-bold text-blue-700 mb-1">VANTECH COMPUTERS TRADING</h1>
                    <p class="text-xs text-gray-600">Van Bryan C. Bardillas - Sole Proprietor</p>
                    <p class="text-xs text-gray-600">Non VAT Reg. TIN 505-374240-00000</p>
                    <p class="text-xs text-gray-600">758 F Purok 3, Brgy. Mintal</p>
                    <p class="text-xs text-gray-600">Davao City, Davao del Sur, 8000</p>
                </div>
                <div class="w-2/5 flex flex-col items-end justify-end">
                    <img src="{{ asset('images/logo.png') }}" class="w-28 h-auto mb-2" />
                    <h2 class="text-lg font-bold text-blue-700 text-right whitespace-nowrap">SERVICE ACKNOWLEDGEMENT
                    </h2>
                    <p class="text-xs text-gray-600 mt-1 text-right">Date: <span id="letterDate"
                            class="font-semibold"></span></p>
                </div>
            </div>

            <!-- Separator -->
            <div class="border-t-2 border-gray-300 mb-3"></div>

            <!-- Letter Content -->
            <div class="mb-4">
                <p class="text-xs text-gray-700 mb-2">Dear Valued Customer,</p>

                <p class="text-xs text-gray-700 mb-2">
                    We acknowledge receipt of your device/item for service. Below are the details of your service order:
                </p>

                <!-- Service Details Card -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 mb-3">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <!-- Left Column -->
                        <div>
                            <p class="font-semibold text-gray-800 mb-2">CUSTOMER INFORMATION</p>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-700">Name:</span>
                                <span id="ackCustomerName" class="text-gray-600">-</span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-700">Date Received:</span>
                                <span id="ackDateReceived" class="text-gray-600">-</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Status:</span>
                                <span id="ackStatus" class="text-gray-600">-</span>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <p class="font-semibold text-gray-800 mb-2">DEVICE INFORMATION</p>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-700">Type of Item:</span>
                                <span id="ackItemType" class="text-gray-600">-</span>
                            </div>
                            <div class="mb-1">
                                <span class="font-semibold text-gray-700">Brand/Model:</span>
                                <span id="ackBrandModel" class="text-gray-600">-</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Service Type:</span>
                                <span id="ackServiceType" class="text-gray-600">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width Section -->
                    <div class="mt-2 pt-2 border-t border-gray-300">
                        <p class="font-semibold text-gray-800 mb-1 text-xs">DESCRIPTION OF WORK</p>
                        <p id="ackDescription" class="text-gray-600 text-xs leading-normal">-</p>
                    </div>
                </div>

                <!-- Service Terms -->
                <p class="text-xs text-gray-700 mb-1">
                    <strong>Estimated Completion Date:</strong> <span id="ackDateCompletion"
                        class="text-gray-600">-</span>
                </p>

                <p class="text-xs text-gray-700 mb-2">
                    <strong>Service Fee:</strong> ₱<span id="ackPrice" class="text-gray-600">0.00</span>
                </p>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2 mb-3">
                    <p class="font-semibold text-yellow-800 text-xs mb-1 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle text-xs"></i>IMPORTANT NOTES
                    </p>
                    <ul class="text-xs text-yellow-700 space-y-0.5 ml-4 list-disc">
                        <li>This letter serves as your proof of service acknowledgement</li>
                        <li>Please keep this letter for your reference and future correspondence</li>
                        <li>We will notify you once your service is completed</li>
                        <li>Parts replacement warranty is covered by Vantech Computers standard warranty</li>
                        <li>The estimated completion date is subject to parts availability</li>
                    </ul>
                </div>

                <!-- Closing -->
                <p class="text-xs text-gray-700 mb-2">
                    Thank you for choosing Vantech Computers. We assure you of our professional and quality service.
                </p>

                <p class="text-xs text-gray-700">
                    Best Regards,
                </p>
            </div>

            <!-- Signatures -->
            <div class="flex justify-between mt-6 pt-3 border-t border-gray-300">
                <div>
                    <p class="text-sm mb-8">Received by:</p>
                    <div class="w-30 border-t border-gray-400 mt-12"></div>
                    <p class="text-sm text-gray-600 mt-0.5">Printed Name Signature</p>
                </div>
                <div class="text-right">
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
         * Populate acknowledgement letter with service data from sessionStorage
         */
        document.addEventListener('DOMContentLoaded', function () {
            const serviceData = JSON.parse(sessionStorage.getItem('serviceData') || '{}');

            if (Object.keys(serviceData).length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Service Data',
                    text: 'Please select a service from the Services form first',
                    confirmButtonColor: '#151F28'
                }).then(() => {
                    goBack();
                });
                return;
            }

            // Set current date
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('letterDate').textContent = formattedDate;
            document.getElementById('printDate').textContent = today.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Populate service details
            document.getElementById('ackCustomerName').textContent = serviceData.customerName || '-';
            document.getElementById('ackDateReceived').textContent = serviceData.dateIn || '-';
            document.getElementById('ackDateCompletion').textContent = serviceData.dateOut || '-';
            document.getElementById('ackStatus').textContent = serviceData.status || '-';
            document.getElementById('ackItemType').textContent = serviceData.type || '-';
            document.getElementById('ackBrandModel').textContent = (serviceData.brand || '-') + ' ' + (serviceData.model || '-');
            document.getElementById('ackServiceType').textContent = serviceData.serviceTypeName || '-';
            document.getElementById('ackDescription').textContent = serviceData.description || '-';
            document.getElementById('ackPrice').textContent = parseFloat(serviceData.totalPrice || 0).toFixed(2);
        });

        /**
         * Print letter
         */
        function printLetter() {
            window.print();
        }

        /**
         * Print and return
         */
        function printAndReturn() {
            window.print();
            setTimeout(() => {
                goBack();
            }, 1000);
        }

        /**
         * Go back to Services page
         */
        function goBack() {
            window.location.href = document.referrer || '/';
        }
    </script>
</body>

</html>