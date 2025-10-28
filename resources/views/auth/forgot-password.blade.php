@extends('layouts.app')

@section('title', 'Forgot Password - Tea')
@section('description', 'Reset your Tea account password.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    
    <div class="relative max-w-md w-full">
        <!-- Forgot Password Card -->
        <div class="card p-8 shadow-glow-lg">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl shadow-glow mb-6">
                    <span class="text-2xl font-bold text-white">T</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Forgot Password?
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    No worries! Enter your email and we'll send you a reset link.
                </p>
            </div>
            
            <!-- Forgot Password Form -->
            <form class="space-y-6" x-data="auth()" @submit.prevent="resetPassword($refs.formData)">
                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Email Address
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email"
                            name="email"
                            placeholder="Enter your email"
                            required
                            class="form-input pl-10"
                            x-model="formData.email"
                        />
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full btn-primary px-6 py-3 text-base font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105"
                        :disabled="loading"
                        x-data="{ loading: false }"
                    >
                        <template x-if="loading">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending Reset Link...
                            </div>
                        </template>
                        
                        <template x-if="!loading">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Reset Link
                            </div>
                        </template>
                    </button>
                </div>
                
                <!-- Hidden form data for Alpine.js -->
                <div x-ref="formData" style="display: none;">
                    <input name="email" :value="formData.email">
                </div>
            </form>
            
            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Trust Indicators -->
        <div class="mt-8 text-center">
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Secure Reset
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Privacy Protected
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('auth', () => ({
        formData: {
            email: ''
        },
        loading: false,
        error: null,
        
        async resetPassword(formData) {
            this.loading = true;
            this.error = null;
            
            try {
                const form = new FormData(formData);
                const data = Object.fromEntries(form.entries());
                
                // Simulate API call for now
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                // Show success message
                this.showNotification('Password reset link sent to your email!', 'success');
                
                // Redirect back to login after a delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 3000);
                
            } catch (error) {
                this.error = error.message || 'Failed to send reset link. Please try again.';
                this.showNotification(this.error, 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }));
});
</script>
@endsection