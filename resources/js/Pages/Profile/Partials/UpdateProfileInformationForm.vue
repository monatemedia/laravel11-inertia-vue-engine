<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

// Define the form with additional fields
const form = useForm({
    name: user.name,
    email: user.email,
    country: user.country || '', // Assuming country is available on the user object
    phone_number: user.phone_number || '', // Assuming phone_number is available on the user object
});

// initialise country list
const countries = ref([]);

onMounted(() => {
    // Get countries via API
    axios.get('https://restcountries.com/v3.1/all')
        .then(response => {
            countries.value = response.data
                .map(country => ({
                    name: country.name.common,
                    code: country.cca2,
                    dialCode: country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : ''), // Add dial code
                }))
                .sort((a, b) => a.name.localeCompare(b.name)); // Sort alphabetically
        });

    // Watch for changes in country selection to auto-prefix phone number with the country dial code
    watch(() => form.country, (newCountryCode) => {
        const selectedCountry = countries.value.find(country => country.code === newCountryCode);
        if (selectedCountry && !form.phone_number.startsWith(`+${selectedCountry.dialCode}`)) {
            form.phone_number = `${selectedCountry.dialCode} `;
        }
    });
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <!-- Name Input -->
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email Input -->
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Country Selection Dropdown -->
            <div>
                <InputLabel for="country" value="Country" />
                <select
                    id="country"
                    class="mt-1 block w-full border-gray-300 dark:bg-gray-800 dark:text-white rounded-md"
                    v-model="form.country"
                >
                    <option value="">Select your country</option>
                    <option v-for="country in countries" :key="country.code" :value="country.code">
                        {{ country.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.country" />
            </div>

            <!-- Phone Number Input -->
            <div>
                <InputLabel for="phone_number" value="Phone Number" />
                <TextInput
                    id="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                />
                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <!-- Email Verification Status -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
