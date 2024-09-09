<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    country: '',
    phone_number: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

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
});

// Watch for changes in country selection to auto-prefix phone number with the country dial code
watch(() => form.country, (newCountryCode) => {
    const selectedCountry = countries.value.find(country => country.code === newCountryCode);
    if (selectedCountry && !form.phone_number.startsWith(`+${selectedCountry.dialCode}`)) {
        form.phone_number = `${selectedCountry.dialCode} `;
    }
});
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <!-- Name Input -->
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email Input -->
            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Country Selection Dropdown -->
            <div class="mt-4">
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
            <div class="mt-4">
                <InputLabel for="phone_number" value="Phone Number" />
                <TextInput
                    id="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                />
                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <!-- Password Input -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Password Confirmation Input -->
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
