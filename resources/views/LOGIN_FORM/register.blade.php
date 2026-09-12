<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vantech Computers - Employee Registration</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

@php
    /**
     * Which step each field lives on. Used to reopen the form on the first
     * step that failed server-side validation, so the applicant is not left
     * staring at a step with no visible error.
     */
    $stepFields = [
        1 => ['first_name', 'middle_name', 'last_name', 'phone_number', 'gender'],
        2 => ['street', 'barangay', 'city'],
        3 => ['username', 'password', 'password_confirmation'],
    ];

    $stepTitles = [1 => 'Personal', 2 => 'Address', 3 => 'Account'];

    $initialStep = 1;
    foreach ($stepFields as $index => $fields) {
        if ($errors->hasAny($fields)) {
            $initialStep = $index;
            break;
        }
    }

    $inputClass =
        'w-full px-4 py-2.5 bg-gray-800/40 border border-gray-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition backdrop-blur-sm';
    $labelClass = 'block text-sm font-medium text-gray-300 mb-1.5';
@endphp

<body class="min-h-screen font-sans">
    <div class="fixed inset-0 bg-cover bg-center bg-no-repeat z-0"
        style="background-image: url('{{ asset('images/vantechBG.svg') }}'); filter: blur(8px); transform: scale(1.1);">
    </div>
    <div class="fixed inset-0 bg-black bg-opacity-50 z-10"></div>

    <div class="relative z-20 min-h-screen flex items-center justify-center p-4 py-10">
        <div
            class="w-full max-w-3xl bg-gray-800/60 rounded-2xl shadow-2xl border border-gray-600/50 backdrop-blur-md p-6 sm:p-8 lg:p-10">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/80 rounded-2xl mb-4 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2 drop-shadow">Employee Registration</h1>
                <p class="text-gray-300 drop-shadow text-sm">
                    Apply for an account at <span class="font-semibold text-blue-200">Vantech Computers</span>
                </p>
            </div>

            @if (session('registered'))
                {{-- Application is in. The form is replaced entirely so there is
                     one obvious next action. --}}
                <div class="p-6 bg-green-500/20 border border-green-400/50 rounded-xl backdrop-blur-sm text-center">
                    <svg class="w-14 h-14 text-green-300 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-200 font-semibold text-lg mb-2">Registration submitted</p>
                    <p class="text-green-100/90 text-sm max-w-md mx-auto">{{ session('success') }}</p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 bg-blue-600/80 hover:bg-blue-700/90 text-white font-medium rounded-lg transition">
                            Go to login
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-6 py-2.5 border border-gray-500/60 text-gray-200 hover:bg-gray-700/40 rounded-lg transition">
                            Register another
                        </a>
                    </div>
                </div>
            @else
                @if (session('error'))
                    <div
                        class="mb-6 p-4 bg-red-500/25 border border-red-400/50 rounded-lg text-red-200 text-sm backdrop-blur-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/25 border border-red-400/50 rounded-lg backdrop-blur-sm">
                        <p class="text-red-200 font-semibold text-sm mb-2">Please correct the following:</p>
                        <ul class="list-disc list-inside text-red-100/90 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Step indicator --}}
                <nav aria-label="Registration progress" class="mb-8">
                    <ol class="flex items-center justify-between gap-1">
                        @foreach ($stepTitles as $number => $title)
                            <li class="flex items-center {{ $number < count($stepTitles) ? 'flex-1' : '' }}">
                                <div class="flex flex-col items-center gap-1.5 shrink-0">
                                    <span data-indicator="{{ $number }}"
                                        class="js-step-dot flex items-center justify-center w-9 h-9 rounded-full border-2 text-sm font-semibold transition
                                            border-gray-500 text-gray-400 bg-gray-800/40">
                                        <span class="js-dot-number">{{ $number }}</span>
                                        <svg class="js-dot-check hidden w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                    <span data-indicator-label="{{ $number }}"
                                        class="js-step-label text-[11px] sm:text-xs font-medium text-gray-400 transition">
                                        {{ $title }}
                                    </span>
                                </div>
                                @if ($number < count($stepTitles))
                                    <span data-connector="{{ $number }}"
                                        class="js-step-line flex-1 h-0.5 mx-2 -mt-5 rounded bg-gray-600 transition"></span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>

                <form method="POST" action="{{ route('register.store') }}" id="registerForm"
                    data-initial-step="{{ $initialStep }}" novalidate>
                    @csrf

                    {{-- ============ STEP 1: Personal ============ --}}
                    <section data-step="1" class="js-step">
                        <h2
                            class="text-sm font-semibold text-blue-200 uppercase tracking-wide mb-4 pb-2 border-b border-gray-600/50">
                            Personal Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="first_name" class="{{ $labelClass }}">
                                    First Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="first_name" name="first_name"
                                    value="{{ old('first_name') }}" required class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label for="middle_name" class="{{ $labelClass }}">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name"
                                    value="{{ old('middle_name') }}" class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label for="last_name" class="{{ $labelClass }}">
                                    Last Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                    required class="{{ $inputClass }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="phone_number" class="{{ $labelClass }}">
                                    Phone Number <span class="text-red-400">*</span>
                                </label>
                                <input type="tel" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number') }}" placeholder="09XXXXXXXXX" required
                                    class="{{ $inputClass }}">
                            </div>
                            <div>
                                <span class="{{ $labelClass }}">
                                    Gender <span class="text-red-400">*</span>
                                </span>
                                <div class="flex items-center gap-6 h-[46px]">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="gender" value="male" required
                                            {{ old('gender') === 'male' ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-500 focus:ring-blue-400">
                                        <span class="text-gray-200 text-sm">Male</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="gender" value="female"
                                            {{ old('gender') === 'female' ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-500 focus:ring-blue-400">
                                        <span class="text-gray-200 text-sm">Female</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ============ STEP 2: Address ============ --}}
                    <section data-step="2" class="js-step">
                        <h2
                            class="text-sm font-semibold text-blue-200 uppercase tracking-wide mb-4 pb-2 border-b border-gray-600/50">
                            Address
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="street" class="{{ $labelClass }}">Street</label>
                                <input type="text" id="street" name="street" value="{{ old('street') }}"
                                    class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label for="barangay" class="{{ $labelClass }}">Barangay</label>
                                <input type="text" id="barangay" name="barangay" value="{{ old('barangay') }}"
                                    class="{{ $inputClass }}">
                            </div>
                            <div>
                                <label for="city" class="{{ $labelClass }}">City / Province</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="{{ $inputClass }}">
                            </div>
                        </div>

                        <p class="mt-3 text-xs text-gray-400">
                            You can leave any part of your address blank and update it later.
                        </p>
                    </section>

                    {{-- ============ STEP 3: Account ============ --}}
                    <section data-step="3" class="js-step">
                        <h2
                            class="text-sm font-semibold text-blue-200 uppercase tracking-wide mb-4 pb-2 border-b border-gray-600/50">
                            Login Details
                        </h2>

                        <div class="mb-4">
                            <label for="username" class="{{ $labelClass }}">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                autocomplete="off" required minlength="4" class="{{ $inputClass }}">
                            <p class="mt-1 text-xs text-gray-400">
                                At least 4 characters. Letters, numbers, dashes and underscores only.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="{{ $labelClass }}">
                                    Password <span class="text-red-400">*</span>
                                </label>
                                <input type="password" id="password" name="password" required minlength="8"
                                    autocomplete="new-password" class="{{ $inputClass }}">
                                <p class="mt-1 text-xs text-gray-400">At least 8 characters, with letters and
                                    numbers.</p>
                            </div>
                            <div>
                                <label for="password_confirmation" class="{{ $labelClass }}">
                                    Confirm Password <span class="text-red-400">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    required autocomplete="new-password" class="{{ $inputClass }}">
                                <p id="passwordMatchHint" class="mt-1 text-xs text-gray-400"></p>
                            </div>
                        </div>

                        {{-- Sets expectations before they submit --}}
                        <div
                            class="mt-5 flex items-start gap-3 p-4 bg-blue-500/15 border border-blue-400/40 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5 text-blue-300 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-blue-100/90 text-sm">
                                Your account will not work until the owner reviews and activates it. You will not be
                                able to log in immediately after registering. Your position is assigned by the owner
                                as part of that review.
                            </p>
                        </div>
                    </section>

                    {{-- Navigation --}}
                    <div class="flex items-center justify-between gap-3 mt-8 pt-6 border-t border-gray-600/50">
                        <button type="button" id="backBtn"
                            class="px-6 py-2.5 border border-gray-500/60 text-gray-200 rounded-lg hover:bg-gray-700/40 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Back
                        </button>

                        <span id="stepCounter" class="text-xs text-gray-400"></span>

                        <div class="flex items-center gap-3">
                            <button type="button" id="nextBtn"
                                class="px-8 py-2.5 bg-gradient-to-r from-blue-600/80 to-blue-700/80 hover:from-blue-700/90 hover:to-blue-800/90 text-white font-semibold rounded-lg shadow-lg shadow-blue-500/30 transition focus:outline-none focus:ring-2 focus:ring-blue-400">
                                Next
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-8 py-2.5 bg-gradient-to-r from-green-600/80 to-green-700/80 hover:from-green-700/90 hover:to-green-800/90 text-white font-semibold rounded-lg shadow-lg shadow-green-500/30 transition focus:outline-none focus:ring-2 focus:ring-green-400">
                                Submit Registration
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="text-blue-300 hover:text-blue-200 font-medium underline">Log in</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('registerForm');
            if (!form) return;

            const steps = Array.from(form.querySelectorAll('.js-step'));
            const total = steps.length;
            const backBtn = document.getElementById('backBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const counter = document.getElementById('stepCounter');

            // Steps are only split up once JS is running. Without it the form
            // stays a single scrollable page that still submits correctly.
            let current = Number(form.dataset.initialStep) || 1;

            const stepEl = n => steps.find(s => Number(s.dataset.step) === n);

            function paintIndicator() {
                document.querySelectorAll('.js-step-dot').forEach(dot => {
                    const n = Number(dot.dataset.indicator);
                    const num = dot.querySelector('.js-dot-number');
                    const check = dot.querySelector('.js-dot-check');
                    const label = document.querySelector(`[data-indicator-label="${n}"]`);

                    dot.className = 'js-step-dot flex items-center justify-center w-9 h-9 rounded-full border-2 text-sm font-semibold transition ';
                    if (n < current) {
                        dot.className += 'border-green-400 bg-green-500/80 text-white';
                        num.classList.add('hidden');
                        check.classList.remove('hidden');
                    } else if (n === current) {
                        dot.className += 'border-blue-400 bg-blue-600/80 text-white ring-4 ring-blue-400/20';
                        num.classList.remove('hidden');
                        check.classList.add('hidden');
                    } else {
                        dot.className += 'border-gray-500 text-gray-400 bg-gray-800/40';
                        num.classList.remove('hidden');
                        check.classList.add('hidden');
                    }

                    if (label) {
                        label.className = 'js-step-label text-[11px] sm:text-xs font-medium transition ' +
                            (n === current ? 'text-blue-200' : n < current ? 'text-green-300' : 'text-gray-400');
                    }
                });

                document.querySelectorAll('.js-step-line').forEach(line => {
                    const n = Number(line.dataset.connector);
                    line.className = 'js-step-line flex-1 h-0.5 mx-2 -mt-5 rounded transition ' +
                        (n < current ? 'bg-green-400' : 'bg-gray-600');
                });
            }

            function render() {
                steps.forEach(s => { s.hidden = Number(s.dataset.step) !== current; });

                backBtn.disabled = current === 1;
                nextBtn.hidden = current === total;
                submitBtn.hidden = current !== total;
                counter.textContent = `Step ${current} of ${total}`;

                paintIndicator();
            }

            function go(n) {
                current = Math.min(Math.max(n, 1), total);
                render();
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            /** Validate only the fields on one step, reporting the first problem. */
            function stepIsValid(n) {
                const fields = stepEl(n).querySelectorAll('input, select, textarea');
                for (const field of fields) {
                    if (!field.checkValidity()) {
                        field.reportValidity();
                        return false;
                    }
                }
                return true;
            }

            // Password confirmation is checked in the browser so the applicant
            // is not sent back here by the server for a typo.
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const matchHint = document.getElementById('passwordMatchHint');

            function checkMatch() {
                if (!password || !confirmation) return;

                if (confirmation.value === '') {
                    confirmation.setCustomValidity('');
                    matchHint.textContent = '';
                    return;
                }

                if (password.value !== confirmation.value) {
                    confirmation.setCustomValidity('Passwords do not match.');
                    matchHint.textContent = 'Passwords do not match.';
                    matchHint.className = 'mt-1 text-xs text-red-300';
                } else {
                    confirmation.setCustomValidity('');
                    matchHint.textContent = 'Passwords match.';
                    matchHint.className = 'mt-1 text-xs text-green-300';
                }
            }

            password?.addEventListener('input', checkMatch);
            confirmation?.addEventListener('input', checkMatch);

            nextBtn.addEventListener('click', () => {
                if (stepIsValid(current)) go(current + 1);
            });

            backBtn.addEventListener('click', () => go(current - 1));

            // Enter should advance through the form, not submit it early.
            form.addEventListener('keydown', e => {
                if (e.key !== 'Enter' || e.target.tagName === 'TEXTAREA') return;
                if (current < total) {
                    e.preventDefault();
                    if (stepIsValid(current)) go(current + 1);
                }
            });

            form.addEventListener('submit', e => {
                checkMatch();

                // Guard every step, not just the visible one: a hidden invalid
                // field cannot be reported by the browser and would otherwise
                // fail silently.
                for (let n = 1; n <= total; n++) {
                    if (!stepIsValid(n)) {
                        e.preventDefault();
                        go(n);
                        setTimeout(() => stepIsValid(n), 300);
                        return;
                    }
                }

                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
            });

            render();
        })();
    </script>
</body>

</html>
