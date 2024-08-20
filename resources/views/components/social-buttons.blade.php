<!-- Social Login Buttons -->
<div class="mt-9 flex flex-col items-center">
    <div class="text-sm text-gray-600 mb-4">{{ __('Or continue with').':' }}</div>
    <div class="flex space-x-4">
        <!-- Facebook Login Button -->
        <a href="{{ route('auth.facebook') }}" class="inline-flex items-center justify-center !p-0 w-12 h-12 rounded-full !bg-[#1877F2] hover:!bg-[#166FE5] text-white">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>

        <!-- Apple Login Button -->
        <a href="{{ route('auth.apple') }}" class="inline-flex items-center justify-center !p-0 w-12 h-12 rounded-full !bg-black hover:!bg-gray-900">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.05 20.28c-.98.95-2.05.88-3.08.41-1.07-.5-2.06-.48-3.2 0-1.42.61-2.15.52-3.01-.41C3.21 15.64 3.9 8.25 9.04 7.91c1.32.07 2.24.69 3.03.73.79.04 1.79-.53 3.34-.71 1.89-.05 3.14.74 3.96 1.82-3.49 2.07-2.63 6.46.68 7.97-.77 1.64-1.78 3.24-3 3.56zM12.03 7.28c-.12-2.81 2.39-5.22 5.21-5.28.23 2.57-2.44 5.32-5.21 5.28z"/>
            </svg>
        </a>

        <!-- Google Login Button -->
        <a href="{{ route('auth.google') }}" class="inline-flex items-center justify-center !p-0 w-12 h-12 rounded-full !bg-white !border-gray-300 !text-gray-700 hover:!bg-gray-100">
            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
        </a>

        <!-- GitHub Login Button -->
        <a href="{{ route('auth.github') }}" class="inline-flex items-center justify-center !p-0 w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>
</div>
