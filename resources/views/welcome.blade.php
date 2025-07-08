<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attirio - AI Chat Assistant</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'typing': 'typing 3.5s steps(40, end)',
                        'blink': 'blink 1s infinite',
                        'wave': 'wave 2s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(50px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        'gradient-x': {
                            '0%, 100%': { 'background-position': '0% 50%' },
                            '50%': { 'background-position': '100% 50%' }
                        },
                        typing: {
                            'from': { width: '0' },
                            'to': { width: '100%' }
                        },
                        blink: {
                            '0%, 50%': { opacity: '1' },
                            '51%, 100%': { opacity: '0' }
                        },
                        wave: {
                            '0%, 100%': { transform: 'rotate(0deg)' },
                            '25%': { transform: 'rotate(20deg)' },
                            '75%': { transform: 'rotate(-20deg)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(99, 102, 241, 0.3)' },
                            '100%': { boxShadow: '0 0 40px rgba(99, 102, 241, 0.8)' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 50%, #312e81 100%);
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .text-gradient {
            background: linear-gradient(45deg, #3b82f6, #1e40af, #60a5fa);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-x 4s ease infinite;
        }

        .typing-effect {
            overflow: hidden;
            border-right: 2px solid #3b82f6;
            white-space: nowrap;
            margin: 0 auto;
            animation: typing 3.5s steps(40, end), blink 1s infinite;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) {
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            top: 80%;
            left: 80%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            top: 40%;
            left: 90%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            top: 90%;
            left: 10%;
            animation-delay: 1s;
        }

        .particle:nth-child(5) {
            top: 10%;
            left: 60%;
            animation-delay: 3s;
        }

        .feature-card {
            transition: all 0.3s ease;
            transform-style: preserve-3d;
        }

        .feature-card:hover {
            transform: translateY(-10px) rotateY(5deg);
        }

        .icon-bounce {
            animation: bounce-slow 2s infinite;
        }

        .gradient-border {
            background: linear-gradient(45deg, #3b82f6, #1e40af);
            padding: 2px;
            border-radius: 20px;
        }

        .gradient-border-inner {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            padding: 2rem;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #3b82f6, #1e40af);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 70%;
            right: 10%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #60a5fa, #2563eb);
            border-radius: 30%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #1d4ed8, #1e3a8a);
            border-radius: 20%;
            animation-delay: 4s;
        }

        @media (max-width: 768px) {
            .typing-effect {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Header -->
    <header class="w-full py-4 px-6 glass-effect relative z-10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3 animate-fade-in">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg animate-glow">
                    <i class="fas fa-brain text-white text-lg animate-pulse"></i>
                </div>
                <span class="text-white text-xl font-bold">Attirio</span>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center space-x-4 animate-fade-in">
                <a href="{{ route('login') }}"
                    class="text-white/80 hover:text-white px-4 py-2 text-sm font-medium transition-all duration-300 hover:scale-105">
                    Sign In
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 border border-white/30">
                        Sign Up
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-16 relative z-10">
        <!-- Hero Section -->
        <div class="text-center mb-20">
            <!-- Animated Icon -->
            <div class="relative inline-block mb-12 animate-slide-up">
                <div
                    class="w-40 h-40 mx-auto bg-gradient-to-br from-indigo-400 via-purple-500 to-pink-500 rounded-full flex items-center justify-center animate-float shadow-2xl">
                    <i class="fas fa-robot text-white text-6xl animate-wave"></i>
                </div>
                <!-- Orbiting Elements -->
                <div class="absolute inset-0 animate-spin" style="animation-duration: 20s;">
                    <div
                        class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-2 w-4 h-4 bg-blue-400 rounded-full animate-pulse">
                    </div>
                    <div
                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-2 w-4 h-4 bg-purple-400 rounded-full animate-pulse">
                    </div>
                    <div
                        class="absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-2 w-4 h-4 bg-pink-400 rounded-full animate-pulse">
                    </div>
                    <div
                        class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-2 w-4 h-4 bg-indigo-400 rounded-full animate-pulse">
                    </div>
                </div>
            </div>

            <!-- Animated Heading -->
            <div class="mb-8 animate-slide-up" style="animation-delay: 0.2s;">
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-tight">
                    <span class="text-gradient">AI Chat</span>
                    <br>
                    <span class="typing-effect">Assistant</span>
                </h1>
            </div>

            <!-- Subtitle -->
            <div class="animate-slide-up" style="animation-delay: 0.4s;">
                <p class="text-xl md:text-2xl text-white/90 max-w-4xl mx-auto font-light leading-relaxed mb-4">
                    Experience the future of conversation with our advanced AI assistant
                </p>
                <p class="text-lg text-white/70 max-w-3xl mx-auto">
                    Get intelligent, instant responses for any question, task, or creative project
                </p>
            </div>

            <!-- CTA Button -->
            <div class="mt-12 animate-slide-up" style="animation-delay: 0.6s;">
                @auth
                    <a href="{{ route('chat.index') }}"
                        class="group inline-flex items-center bg-gradient-to-r from-white to-gray-100 hover:from-gray-100 hover:to-white text-gray-900 px-12 py-5 rounded-full font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-white/20">
                        <i class="fas fa-comments mr-4 text-2xl group-hover:animate-bounce"></i>
                        Start Chatting Now
                        <i class="fas fa-arrow-right ml-4 text-xl group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="group inline-flex items-center bg-gradient-to-r from-white to-gray-100 hover:from-gray-100 hover:to-white text-gray-900 px-12 py-5 rounded-full font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-white/20">
                        <i class="fas fa-rocket mr-4 text-2xl group-hover:animate-bounce"></i>
                        Get Started Free
                        <i class="fas fa-arrow-right ml-4 text-xl group-hover:translate-x-1 transition-transform"></i>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Features Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-20">
            <!-- Feature 1 -->
            <div class="feature-card gradient-border animate-slide-up" style="animation-delay: 0.8s;">
                <div class="gradient-border-inner text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 shadow-lg animate-glow">
                        <i class="fas fa-bolt text-white text-3xl icon-bounce"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-4 text-2xl">Lightning Fast</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Get instant responses powered by cutting-edge AI technology. No waiting, just seamless
                        conversation.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card gradient-border animate-slide-up" style="animation-delay: 1s;">
                <div class="gradient-border-inner text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mb-6 shadow-lg animate-glow">
                        <i class="fas fa-shield-alt text-white text-3xl icon-bounce"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-4 text-2xl">Secure & Private</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Your conversations are protected with enterprise-grade security and privacy measures.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card gradient-border animate-slide-up" style="animation-delay: 1.2s;">
                <div class="gradient-border-inner text-center">
                    <div
                        class="w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-6 shadow-lg animate-glow">
                        <i class="fas fa-brain text-white text-3xl icon-bounce"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-4 text-2xl">Advanced AI</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Powered by the latest AI models for intelligent, context-aware conversations.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto text-center animate-slide-up"
            style="animation-delay: 1.4s;">
            <div class="glass-effect p-8 rounded-2xl">
                <div class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-users text-indigo-300 mr-2"></i>
                    100K+
                </div>
                <div class="text-white/80">Active Users</div>
            </div>
            <div class="glass-effect p-8 rounded-2xl">
                <div class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-comments text-purple-300 mr-2"></i>
                    1M+
                </div>
                <div class="text-white/80">Conversations</div>
            </div>
            <div class="glass-effect p-8 rounded-2xl">
                <div class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-clock text-pink-300 mr-2"></i>
                    24/7
                </div>
                <div class="text-white/80">Available</div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-12 text-center text-white/60 text-sm border-t border-white/10 mt-20 glass-effect">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Attirio') }}. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="hover:text-white transition-colors">
                        <i class="fab fa-github text-xl"></i>
                    </a>
                    <a href="#" class="hover:text-white transition-colors">
                        <i class="fab fa-discord text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth animations on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('[class*="animate-"]').forEach(el => {
            observer.observe(el);
        });

        // Add dynamic particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 6000);
        }

        // Create particles periodically
        setInterval(createParticle, 3000);

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add parallax effect to background shapes
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.5;
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    </script>
</body>

</html>