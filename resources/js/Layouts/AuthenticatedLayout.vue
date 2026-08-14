<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);

// Mengambil data page props untuk akses user & flash message
const page = usePage();

// Helper untuk mengambil inisial nama pengguna
const getUserInitials = (name) => {
    if (!name) return "U";
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-50/75 dark:bg-gray-900">
            <!-- Top Navbar -->
            <nav
                class="sticky top-0 z-40 border-b border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-800"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-indigo-600 dark:text-indigo-400"
                                    />
                                </Link>
                            </div>

                            <!-- Desktop Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <!-- Right Header / User Dropdown -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-x-2 rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-600 transition duration-150 ease-in-out hover:text-gray-900 focus:outline-none dark:bg-gray-800 dark:text-gray-300 dark:hover:text-white"
                                            >
                                                <!-- Avatar Initials -->
                                                <span
                                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300"
                                                >
                                                    {{
                                                        getUserInitials(
                                                            $page.props.auth
                                                                .user.name,
                                                        )
                                                    }}
                                                </span>

                                                <span>{{
                                                    $page.props.auth.user.name
                                                }}</span>

                                                <svg
                                                    class="-me-0.5 ms-1 h-4 w-4 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div
                                            class="px-4 py-2 text-xs text-gray-400 dark:text-gray-500"
                                        >
                                            Pengaturan Akun
                                        </div>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <div
                                            class="border-t border-gray-100 dark:border-gray-700"
                                        />
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger Button (Mobile) -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:hover:bg-gray-700 dark:focus:bg-gray-700"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Mobile Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="border-b border-gray-200 bg-white sm:hidden dark:border-gray-700 dark:bg-gray-800"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-700"
                    >
                        <div class="flex items-center px-4">
                            <div
                                class="me-3 flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300"
                            >
                                {{
                                    getUserInitials($page.props.auth.user.name)
                                }}
                            </div>
                            <div>
                                <div
                                    class="text-base font-medium text-gray-800 dark:text-gray-200"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow-sm dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Flash Alert Container -->
            <div
                v-if="page.props.flash?.success || page.props.flash?.error"
                class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8"
            >
                <div
                    v-if="page.props.flash?.success"
                    class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300"
                >
                    {{ page.props.flash.success }}
                </div>
                <div
                    v-if="page.props.flash?.error"
                    class="rounded-md border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-800 dark:bg-rose-900/30 dark:text-rose-300"
                >
                    {{ page.props.flash.error }}
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-6">
                <slot />
            </main>
        </div>
    </div>
</template>
