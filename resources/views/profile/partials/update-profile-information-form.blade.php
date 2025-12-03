<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("View and update your account's profile information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <!-- Personal Information -->
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @php $fields = ['first_name' => 'First Name', 'last_name' => 'Last Name', 'other_names' => 'Other Names']; @endphp
                @foreach($fields as $field => $label)
                    <div x-data="{ editing: false }" class="bg-white rounded p-3 border">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">{{ __($label) }}</label>
                            <button type="button" @click="editing = !editing" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div x-show="!editing" class="text-gray-900">{{ $user->$field ?: 'Not set' }}</div>
                        <div x-show="editing" x-transition>
                            <x-text-input :id="$field" :name="$field" type="text" class="block w-full text-sm" :value="old($field, $user->$field)" :autofocus="$loop->first" />
                            <x-input-error class="mt-1" :messages="$errors->get($field)" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div x-data="{ editing: false }" class="bg-white rounded p-2 border">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                        <button type="button" @click="editing = !editing" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                    <div x-show="!editing" class="text-gray-900">{{ $user->email }}</div>
                    <div x-show="editing" x-transition>
                        <x-text-input id="email" name="email" type="email" class="block w-full text-sm" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="text-xs mt-2 text-gray-600">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-xs text-gray-600 hover:text-gray-900">Resend verification</button>
                            </p>
                        @endif
                    </div>
                </div>

                <div x-data="{ editing: false }" class="bg-white rounded p-2 border">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">{{ __('Phone Number') }}</label>
                        <button type="button" @click="editing = !editing" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                    <div x-show="!editing" class="text-gray-900">{{ $user->phone_number ?: 'Not set' }}</div>
                    <div x-show="editing" x-transition>
                        <x-text-input id="phone_number" name="phone_number" type="tel" class="block w-full text-sm" :value="old('phone_number', $user->phone_number)" />
                        <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Employment Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @php $empFields = ['employee_code' => 'Employee Code', 'job_title' => 'Job Title', 'role' => 'Role', 'access_scope' => 'Access Scope']; @endphp
                @foreach($empFields as $field => $label)
                    <div x-data="{ editing: false }" class="bg-white rounded p-3 border">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">{{ __($label) }}</label>
                            <button type="button" @click="editing = !editing" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div x-show="!editing" class="text-gray-900 text-sm">{{ $user->$field ?: 'Not set' }}</div>
                        <div x-show="editing" x-transition>
                            <x-text-input :id="$field" :name="$field" type="text" class="block w-full text-sm" :value="old($field, $user->$field)" />
                            <x-input-error class="mt-1" :messages="$errors->get($field)" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Organizational Information -->
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Organizational Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @php $orgFields = ['department_id' => 'Department ID', 'branch' => 'Branch', 'sub_branch' => 'Sub Branch', 'sector' => 'Sector', 'reports_to_user_id' => 'Reports To User ID']; @endphp
                @foreach($orgFields as $field => $label)
                    <div x-data="{ editing: false }" class="bg-white rounded p-3 border">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">{{ __($label) }}</label>
                            <button type="button" @click="editing = !editing" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div x-show="!editing" class="text-gray-900 text-sm">{{ $user->$field ?: 'Not set' }}</div>
                        <div x-show="editing" x-transition>
                            <x-text-input :id="$field" :name="$field" :type="in_array($field, ['department_id', 'reports_to_user_id']) ? 'number' : 'text'" class="block w-full text-sm" :value="old($field, $user->$field)" />
                            <x-input-error class="mt-1" :messages="$errors->get($field)" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end pt-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-4"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
