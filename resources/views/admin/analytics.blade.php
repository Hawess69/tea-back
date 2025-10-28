@extends('layouts.admin')

@section('title', 'Analytics - Tea Admin')
@section('page-title', 'Analytics')

@section('content')
<!-- Analytics Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Total Views -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 dark:text-green-400 font-medium">+12.5%</span>
                <span class="text-gray-500 dark:text-gray-400">from last month</span>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Users</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ number_format($activeUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
            <div class="text-sm">
                <span class="text-green-600 dark:text-green-400 font-medium">+8.2%</span>
                <span class="text-gray-500 dark:text-gray-400">from last month</span>
            </div>
        </div>
    </div>

    <!-- Engagement Rate -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Engagement Rate</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $engagementRate }}%</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
            <div class="text-sm">
                <span class="text-red-600 dark:text-red-400 font-medium">-2.1%</span>
                <span class="text-gray-500 dark:text-gray-400">from last month</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- User Growth Chart -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">User Growth</h3>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Content Performance Chart -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Content Performance</h3>
            <div class="h-64">
                <canvas id="contentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Top Posts -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Top Performing Posts</h3>
            <div class="space-y-4">
                @foreach($topFeedPosts as $index => $post)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-white">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($post['title'], 30) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $post['author'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $post['votes'] }} votes</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post['comments'] }} comments</p>
                    </div>
                </div>
                @endforeach
                
                @if(count($topFeedPosts) == 0)
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>No feed posts available</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- User Demographics -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">User Demographics</h3>
            <div class="space-y-4">
                @php
                    $totalUsers = array_sum($userDemographics);
                    $colors = ['bg-indigo-500', 'bg-purple-500', 'bg-green-500', 'bg-yellow-500'];
                    $ageGroups = ['18-25', '26-35', '36-45', '45+'];
                @endphp
                
                @foreach($userDemographics as $ageGroup => $count)
                @php
                    $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
                    $colorIndex = array_search($ageGroup, array_keys($userDemographics));
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Age {{ $ageGroup }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="{{ $colors[$colorIndex] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $percentage }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Trends -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Recent Activity Trends</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Last 24 Hours -->
            <div class="text-center">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last 24 Hours</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Users:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_24h']['users'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Posts:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_24h']['posts'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Comments:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_24h']['comments'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Votes:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_24h']['votes'] }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Last 7 Days -->
            <div class="text-center">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last 7 Days</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Users:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_7_days']['users'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Posts:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_7_days']['posts'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Comments:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_7_days']['comments'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Votes:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_7_days']['votes'] }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Last 30 Days -->
            <div class="text-center">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Last 30 Days</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Users:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_30_days']['users'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Posts:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_30_days']['posts'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Comments:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_30_days']['comments'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Votes:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $recentActivity['last_30_days']['votes'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Export Analytics</h3>
        <div class="flex flex-wrap gap-4">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Export CSV
            </button>
            <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Export PDF
            </button>
            <button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Schedule Report
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: @json($userGrowthData['labels']),
            datasets: [{
                label: 'New Users',
                data: @json($userGrowthData['newUsers']),
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4
            }, {
                label: 'Active Users',
                data: @json($userGrowthData['activeUsers']),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Content Performance Chart
    const contentCtx = document.getElementById('contentChart').getContext('2d');
    new Chart(contentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Feed Posts', 'Men Posts', 'Comments', 'Votes'],
            datasets: [{
                data: [
                    {{ $contentPerformanceData['feedPosts'] }},
                    {{ $contentPerformanceData['menPosts'] }},
                    {{ $contentPerformanceData['comments'] }},
                    {{ $contentPerformanceData['votes'] }}
                ],
                backgroundColor: [
                    'rgb(99, 102, 241)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
