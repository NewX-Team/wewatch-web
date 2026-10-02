<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded border bg-amber-500/10 text-amber-400 border-amber-500/20">
                        Creator Studio
                    </span>
                    <span class="text-xs text-slate-400">Content & Studio Management</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-white mt-1">
                    Creator Hub & Media Studio
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700 transition">
                    View Analytics Report
                </button>
                <button class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-lg bg-amber-500 text-slate-950 hover:bg-amber-400 font-bold transition shadow-sm">
                    + Upload New Video / Film
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Creator Analytics Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Video Views</div>
                <div class="text-3xl font-extrabold text-white mt-2">452,180</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-400">
                    <span>+18.2% from last month</span>
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Watch Time</div>
                <div class="text-3xl font-extrabold text-white mt-2">84,200 hrs</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-400">
                    <span>+24.1% engagement rate</span>
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Subscribers</div>
                <div class="text-3xl font-extrabold text-white mt-2">12,450</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-amber-400">
                    <span>+310 new subscribers</span>
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 relative overflow-hidden">
                <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Estimated Earnings</div>
                <div class="text-3xl font-extrabold text-emerald-400 mt-2">$3,840.50</div>
                <div class="flex items-center gap-1 mt-2 text-xs font-medium text-slate-400">
                    <span>Payout schedule: Oct 15</span>
                </div>
            </div>
        </div>

        <!-- Content Library & Management Table -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-xl">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-white">Your Content Library</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Manage published videos, processing queue, and metadata</p>
                </div>
                <div class="flex items-center gap-3">
                    <select class="px-3 py-1.5 text-xs rounded-lg bg-slate-950 border border-slate-800 text-slate-200 focus:outline-none">
                        <option>All Statuses</option>
                        <option>Published</option>
                        <option>Processing</option>
                        <option>Draft</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Title & Media</th>
                            <th class="px-6 py-3.5 font-semibold">Category</th>
                            <th class="px-6 py-3.5 font-semibold">Status</th>
                            <th class="px-6 py-3.5 font-semibold">Views</th>
                            <th class="px-6 py-3.5 font-semibold">Published</th>
                            <th class="px-6 py-3.5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <!-- Video Item 1 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-16 h-10 rounded bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-[10px] text-slate-400">4K HDR</div>
                                <div>
                                    <div class="font-semibold text-white">Shadows of Cyberpunk City - Episode 1</div>
                                    <div class="text-[11px] text-slate-400">45m 12s • 2160p</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">Sci-Fi Series</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Published
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-white">218,400</td>
                            <td class="px-6 py-4 text-slate-400">Sep 28, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-amber-400 hover:text-amber-300 font-medium mr-3">Analytics</button>
                                <button class="text-slate-400 hover:text-slate-200">Edit</button>
                            </td>
                        </tr>

                        <!-- Video Item 2 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-16 h-10 rounded bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-[10px] text-slate-400">1080p</div>
                                <div>
                                    <div class="font-semibold text-white">Behind The Scenes: World Building & Sound Design</div>
                                    <div class="text-[11px] text-slate-400">18m 40s • 1080p</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">Documentary</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                    Processing (85%)
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-500">-</td>
                            <td class="px-6 py-4 text-slate-400">Scheduled (Today)</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-slate-400 hover:text-slate-200">View Progress</button>
                            </td>
                        </tr>

                        <!-- Video Item 3 -->
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-16 h-10 rounded bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-[10px] text-slate-400">DRAFT</div>
                                <div>
                                    <div class="font-semibold text-white">Untitled Indie Short Film Project</div>
                                    <div class="text-[11px] text-slate-400">Draft metadata</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300">Short Film</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-800 text-slate-400 border border-slate-700">
                                    Draft
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-500">-</td>
                            <td class="px-6 py-4 text-slate-400">-</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-amber-400 hover:text-amber-300 font-medium">Continue Editing</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
