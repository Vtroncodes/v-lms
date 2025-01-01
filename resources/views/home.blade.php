<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>V-LMS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">LMS App</h1>
            <ul class="flex space-x-4">
                <li><a href="/" class="hover:underline">Home</a></li>
                <li><a href="/admin" class="hover:underline">Filament Admin</a></li>
                <li><a href="#registration" class="hover:underline">Register</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold mb-4">Welcome to the V-LMS App</h2>
            <p class="text-gray-600 text-lg">Your one-stop solution for managing learning resources efficiently.</p>
        </div>
    </section>

    <!-- Registration Form -->
    <section id="registration" class="py-16 bg-gray-100">
        <div class="container mx-auto">
            <h3 class="text-3xl font-bold text-center mb-8">Register Now</h3>
            <form action="/register" method="POST" class="bg-white p-8 shadow-md rounded max-w-lg mx-auto">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold">Name</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Dummy Info Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto">
            <h3 class="text-3xl font-bold text-center mb-8">Why Choose LMS App?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-100 p-6 rounded shadow">
                    <h4 class="text-xl font-bold mb-2">Feature 1</h4>
                    <p class="text-gray-600">Description of feature 1 that highlights its benefits.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded shadow">
                    <h4 class="text-xl font-bold mb-2">Feature 2</h4>
                    <p class="text-gray-600">Description of feature 2 that explains how it helps users.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded shadow">
                    <h4 class="text-xl font-bold mb-2">Feature 3</h4>
                    <p class="text-gray-600">Description of feature 3 with emphasis on usability.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 V-LMS App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
