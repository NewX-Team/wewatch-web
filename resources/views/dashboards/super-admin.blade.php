<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded border bg-rose-500/10 text-rose-400 border-rose-500/20">
                        Super Admin Access
                    </span>
                    <span class="text-xs text-slate-400">System Control Console</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-white mt-1">
                    System Control & Platform Administration
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700 transition">
                    Export Audit Log
                </button>
                <button class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-500 transition shadow-sm">
                    System Maintenance Mode
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Registered Users</div>
                <div class="text-3xl font-extrabold text-white mt-2">128,490</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-400">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                    <span>+12.4% this month</span>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Active Creators</div>
                <div class="text-3xl font-extrabold text-white mt-2">1,420</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-400">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/></svg>
                    <span>+85 pending approval</span>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Stream Bandwidth</div>
                <div class="text-3xl font-extrabold text-white mt-2">4.8 Tbps</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-slate-400">
                    <span>Peak traffic: 98% capacity</span>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Platform Health</div>
                <div class="text-3xl font-extrabold text-emerald-400 mt-2">99.98%</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-slate-400">
                    <span>All services operational</span>
                </div>
            </div>
        </div>

        <!-- Main Content Area: User & Role Management Table -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-xl">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">Platform Users & Access Roles</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Manage user permissions, role assignments, and system accounts</p>
                </div>
                <div class="flex items-center gap-3">
                    <input type="text" placeholder="Search accounts..." class="px-3 py-1.5 text-xs rounded-lg bg-slate-950 border border-slate-800 text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 w-48">
                    <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 transition">
                        + Add New Account
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">User</th>
                            <th class="px-6 py-3.5 font-semibold">Role</th>
                            <th class="px-6 py-3.5 font-semibold">Status</th>
                            <th class="px-6 py-3.5 font-semibold">Joined Date</th>
                            <th class="px-6 py-3.5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <!-- Row 1 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-300 flex items-center justify-center font-bold text-xs">AV</div>
                                <div>
                                    <div class="font-medium text-white">Alexandre Vance</div>
                                    <div class="text-[11px] text-slate-400">admin@wewatch.test</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded border bg-rose-500/10 text-rose-400 border-rose-500/20">
                                    Super Admin
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-medium text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">Oct 01, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-indigo-400 hover:text-indigo-300 font-medium mr-3">Edit Role</button>
                                <button class="text-slate-400 hover:text-slate-200">Logs</button>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-xs">ER</div>
                                <div>
                                    <div class="font-medium text-white">Elena Rostova</div>
                                    <div class="text-[11px] text-slate-400">creator@wewatch.test</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded border bg-amber-500/10 text-amber-400 border-amber-500/20">
                                    Creator Studio
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-medium text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">Oct 01, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-indigo-400 hover:text-indigo-300 font-medium mr-3">Edit Role</button>
                                <button class="text-slate-400 hover:text-slate-200">Logs</button>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-300 flex items-center justify-center font-bold text-xs">MC</div>
                                <div>
                                    <div class="font-medium text-white">Marcus Chen</div>
                                    <div class="text-[11px] text-slate-400">user@wewatch.test</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded border bg-indigo-500/10 text-indigo-400 border-indigo-500/20">
                                    Standard User
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-medium text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">Oct 01, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-indigo-400 hover:text-indigo-300 font-medium mr-3">Edit Role</button>
                                <button class="text-slate-400 hover:text-slate-200">Logs</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Audit & Security Log Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <h4 class="text-sm font-semibold text-white mb-4">System Audit Trail</h4>
                <div class="space-y-4">
                    <div class="flex items-start justify-between text-xs border-b border-slate-800 pb-3">
                        <div>
                            <div class="font-medium text-slate-200">Role Escalation Request Approved</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Approved Elena Rostova (Creator Studio)</div>
                        </div>
                        <span class="text-[11px] text-slate-500">10 mins ago</span>
                    </div>
                    <div class="flex items-start justify-between text-xs border-b border-slate-800 pb-3">
                        <div>
                            <div class="font-medium text-slate-200">Database Schema Migration Completed</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Migration `add_role_to_users_table` executed cleanly</div>
                        </div>
                        <span class="text-[11px] text-slate-500">42 mins ago</span>
                    </div>
                    <div class="flex items-start justify-between text-xs">
                        <div>
                            <div class="font-medium text-slate-200">CDN Storage Node Re-balanced</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Asia-Southeast node automated failover check</div>
                        </div>
                        <span class="text-[11px] text-slate-500">2 hours ago</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <h4 class="text-sm font-semibold text-white mb-4">Role Access Summary</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span class="font-medium text-white">Super Admin</span>
                        </div>
                        <span class="text-slate-400 font-mono">1 Active Account</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span class="font-medium text-white">Creators</span>
                        </div>
                        <span class="text-slate-400 font-mono">1 Active Account</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                            <span class="font-medium text-white">Standard Users</span>
                        </div>
                        <span class="text-slate-400 font-mono">1 Active Account</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
