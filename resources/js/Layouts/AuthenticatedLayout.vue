<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);

// State untuk status perbesaran sidebar (false = besar, true = kecil)
const isCollapsed = ref(false);

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};

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

// Menu Navigasi Sidebar
const navigation = [
    {
        name: "Dashboard",
        href: route("dashboard"),
        active: "dashboard",
        icon: "📊",
    },
    {
        name: "Jadwal Travel",
        href: route("admin.schedules.index"),
        active: "admin.schedules.*",
        icon: "📅",
    },
    {
        name: "Akun Driver",
        href: route("admin.drivers.index"),
        active: "admin.drivers.*",
        icon: "👨‍✈️",
    },
    {
        name: "Armada Mobil",
        href: route("admin.vehicles.index"),
        active: "admin.vehicles.*",
        icon: "🚐",
    },
    {
        name: "Rute Perjalanan",
        href: route("admin.routes.index"),
        active: "admin.routes.*",
        icon: "🗺️",
    },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50/75 dark:bg-gray-900 flex">
        <!-- ================= 1. DESKTOP SIDEBAR ================= -->
        <aside
            :class="[
                isCollapsed ? 'md:w-20' : 'md:w-64',
                'hidden md:flex md:flex-col sticky top-0 h-screen bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-800 z-30 transition-all duration-300 ease-in-out',
            ]"
        >
            <!-- Logo Section & Toggle Button -->
            <div
                class="h-16 flex items-center justify-between px-4 border-b border-gray-100 dark:border-gray-800"
            >
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 overflow-hidden"
                >
                    <span
                        v-show="!isCollapsed"
                        class="font-extrabold text-xl tracking-tight text-gray-900 dark:text-white whitespace-nowrap transition-opacity duration-300"
                    >
                        <span class="text-blue-600 dark:text-blue-400"
                            >Fazza</span
                        >
                        Travel
                    </span>
                    <span
                        v-show="isCollapsed"
                        class="font-extrabold text-xl text-blue-600 dark:text-blue-400 mx-auto"
                    >
                        F
                    </span>
                </Link>

                <!-- Toggle Collapse Button -->
                <button
                    @click="toggleSidebar"
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition"
                    :title="
                        isCollapsed ? 'Perbesar Sidebar' : 'Perkecil Sidebar'
                    "
                >
                    <svg
                        class="w-5 h-5 transition-transform duration-300"
                        :class="{ 'rotate-180': isCollapsed }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                        />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto p-3 space-y-1">
                <p
                    v-show="!isCollapsed"
                    class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 dark:text-gray-500 whitespace-nowrap"
                >
                    Menu Utama
                </p>

                <NavLink
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :active="route().current(item.active)"
                    :class="[
                        isCollapsed ? 'justify-center px-0' : 'px-3',
                        'flex items-center py-2.5 text-sm rounded-xl transition duration-150 ease-in-out font-medium',
                    ]"
                    :title="isCollapsed ? item.name : ''"
                >
                    <span :class="[isCollapsed ? 'me-0' : 'me-3', 'text-lg']">
                        {{ item.icon }}
                    </span>
                    <span v-show="!isCollapsed" class="truncate">
                        {{ item.name }}
                    </span>
                </NavLink>
            </div>

            <!-- Bottom User Profile Section -->
            <div class="p-3 border-t border-gray-100 dark:border-gray-800">
                <Dropdown align="top" :width="isCollapsed ? '48' : '48'">
                    <template #trigger>
                        <button
                            type="button"
                            :class="[
                                isCollapsed
                                    ? 'justify-center p-2'
                                    : 'justify-between p-2',
                                'w-full flex items-center rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 text-left',
                            ]"
                        >
                            <div class="flex items-center gap-x-3 truncate">
                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300"
                                >
                                    {{
                                        getUserInitials(
                                            $page.props.auth.user.name,
                                        )
                                    }}
                                </span>
                                <div v-show="!isCollapsed" class="truncate">
                                    <p
                                        class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-400 dark:text-gray-500 truncate"
                                    >
                                        {{ $page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                            <svg
                                v-show="!isCollapsed"
                                class="h-4 w-4 text-gray-400 shrink-0 ms-1"
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
                    </template>

                    <template #content>
                        <div
                            class="px-4 py-2 text-xs text-gray-400 dark:text-gray-500"
                        >
                            Pengaturan Akun
                        </div>
                        <DropdownLink :href="route('profile.edit')">
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
        </aside>

        <!-- ================= 2. MAIN CONTENT WRAPPER ================= -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Top Navbar Header -->
            <nav
                class="md:hidden sticky top-0 z-40 border-b border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-800"
            >
                <div
                    class="px-4 sm:px-6 flex h-16 items-center justify-between"
                >
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-2"
                    >
                        <ApplicationLogo
                            class="block h-8 w-auto fill-current text-indigo-600 dark:text-indigo-400"
                        />
                        <span class="font-bold text-gray-800 dark:text-white"
                            >Admin Panel</span
                        >
                    </Link>

                    <!-- Hamburger Button Mobile -->
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300"
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
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Responsive Mobile Menu Dropdown -->
                <div
                    v-show="showingNavigationDropdown"
                    class="border-b border-gray-200 bg-white px-4 pt-2 pb-4 space-y-2 dark:border-gray-700 dark:bg-gray-800"
                >
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        <span class="me-3">{{ item.icon }}</span>
                        {{ item.name }}
                    </Link>

                    <div
                        class="border-t border-gray-200 pt-3 dark:border-gray-700"
                    >
                        <div class="flex items-center px-3 mb-2">
                            <div
                                class="me-3 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300"
                            >
                                {{
                                    getUserInitials($page.props.auth.user.name)
                                }}
                            </div>
                            <div>
                                <div
                                    class="text-sm font-medium text-gray-800 dark:text-gray-200"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>
                        <Link
                            :href="route('profile.edit')"
                            class="block px-3 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >Profile</Link
                        >
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full text-left px-3 py-2 rounded-md text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20"
                            >Log Out</Link
                        >
                    </div>
                </div>
            </nav>

            <!-- Header Slot (Page Title) -->
            <header
                v-if="$slots.header"
                class="bg-white shadow-sm border-b border-gray-100 dark:bg-gray-800 dark:border-gray-800"
            >
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Flash Alert Container -->
            <div
                v-if="page.props.flash?.success || page.props.flash?.error"
                class="mx-auto w-full max-w-7xl px-4 pt-4 sm:px-6 lg:px-8"
            >
                <div
                    v-if="page.props.flash?.success"
                    class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300"
                >
                    {{ page.props.flash.success }}
                </div>
                <div
                    v-if="page.props.flash?.error"
                    class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-800 dark:bg-rose-900/30 dark:text-rose-300"
                >
                    {{ page.props.flash.error }}
                </div>
            </div>

            <!-- Main Page Content -->
            <main class="flex-1 py-6">
                <slot />
            </main>
        </div>
    </div>
</template>
