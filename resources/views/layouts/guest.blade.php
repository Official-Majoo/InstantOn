<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FNBB Online Registration') }} - @yield('title')</title>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/fnbb-styles.css') }}">

    @stack('styles')

    <style>
        /* Updated Font Styling */
        body {
            font-family: 'Poppins', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }

        .lead {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            letter-spacing: 0.015em;
        }

        .fnbb-brand-panel {
            background: linear-gradient(135deg, #00695c 0%, #00897b 50%, #26a69a 100%);
            position: relative;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .fnbb-brand-content {
            position: relative;
            z-index: 2;
            color: #fff;
            padding: 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        #particle-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .fnbb-brand-features {
            text-align: left;
            max-width: 300px;
            margin: 0 auto;
        }

        .feature-item {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .feature-item i {
            margin-right: 12px;
            color: #ffd54f;
            font-size: 1.1em;
        }

        .fnbb-brand-footer {
            position: relative;
            z-index: 2;
        }

        .fnbb-brand-footer p {
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            text-align: center;
            margin: 0;
        }

        .btn {
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            letter-spacing: 0.03em;
        }

        /* Logo styling */
        .fnbb-brand-content img {
            transition: transform 0.3s ease;
        }

        .fnbb-brand-content img:hover {
            transform: scale(1.05);
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased bg-light">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-lg-5 d-none d-lg-block">
                <div class="fnbb-brand-panel">
                    <canvas id="particle-canvas"></canvas>
                    <div class="fnbb-brand-content">
                        <img src="{{ asset('images/fnbb-logo-white.png') }}" alt="FNBB Logo" class="mb-4"
                             width="200" style="background-color: #fff; padding: 5px; border-radius: 15%;">
                        <h2 class="mb-4">Welcome to FNBB Online Registration</h2>
                        <p class="lead mb-5">Complete your registration quickly and securely from anywhere.</p>
                        <div class="fnbb-brand-features">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Quick and easy registration</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure identity verification</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-clock"></i>
                                <span>24/7 availability</span>
                            </div>
                        </div>
                    </div>
                    <div class="fnbb-brand-footer">
                        <p>First National Bank of Botswana Limited - Registration number BW00000790476</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="guest-content-wrapper">
                    <div class="d-block d-lg-none text-center mb-4">
                        <img src="{{ asset('images/fnbb-logo.png') }}" alt="FNBB Logo" width="150">
                    </div>

                    @include('partials.flash-messages')

                    <div class="guest-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        class EnhancedParticleSystem {
            constructor() {
                this.canvas = document.getElementById('particle-canvas');
                this.ctx = this.canvas.getContext('2d');
                this.particles = [];
                this.mouse = {
                    x: null,
                    y: null,
                    radius: (this.canvas.height/80) * (this.canvas.width/80)
                };
                
                this.init();
                this.setupEventListeners();
                this.animate();
            }

            init() {
                this.resizeCanvas();
                this.createParticles();
            }

            resizeCanvas() {
                const panel = this.canvas.parentElement;
                this.canvas.width = panel.offsetWidth;
                this.canvas.height = panel.offsetHeight;
                this.mouse.radius = ((this.canvas.height/80) * (this.canvas.width/80));
            }

            createParticles() {
                this.particles = [];
                const numberOfParticles = Math.floor((this.canvas.width * this.canvas.height) / 12000);
                
                for (let i = 0; i < numberOfParticles; i++) {
                    this.particles.push(new EnhancedParticle(
                        Math.random() * this.canvas.width,
                        Math.random() * this.canvas.height,
                        this.canvas.width,
                        this.canvas.height
                    ));
                }
            }

            setupEventListeners() {
                // Mouse movement - using window event like original
                window.addEventListener('mousemove', (e) => {
                    this.mouse.x = e.x;
                    this.mouse.y = e.y;
                });

                window.addEventListener('mouseout', () => {
                    this.mouse.x = undefined;
                    this.mouse.y = undefined;
                });

                // Resize handling
                window.addEventListener('resize', () => {
                    this.resizeCanvas();
                    this.createParticles();
                });
            }

            animate() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                
                // Update and draw particles
                this.particles.forEach(particle => {
                    particle.update(this.mouse);
                    particle.draw(this.ctx);
                });

                // Draw connections
                this.drawConnections();

                requestAnimationFrame(() => this.animate());
            }

            drawConnections() {
                for (let a = 0; a < this.particles.length; a++) {
                    for (let b = a + 1; b < this.particles.length; b++) {
                        const dx = this.particles[a].x - this.particles[b].x;
                        const dy = this.particles[a].y - this.particles[b].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < 120) {
                            const opacity = 1 - (distance / 120);
                            this.ctx.strokeStyle = `rgba(255, 255, 255, ${opacity * 0.3})`;
                            this.ctx.lineWidth = 1;
                            this.ctx.beginPath();
                            this.ctx.moveTo(this.particles[a].x, this.particles[a].y);
                            this.ctx.lineTo(this.particles[b].x, this.particles[b].y);
                            this.ctx.stroke();
                        }
                    }
                }
            }
        }

        class EnhancedParticle {
            constructor(x, y, canvasWidth, canvasHeight) {
                this.x = x;
                this.y = y;
                this.baseX = x;
                this.baseY = y;
                this.vx = (Math.random() - 0.5) * 0.8;
                this.vy = (Math.random() - 0.5) * 0.8;
                this.size = Math.random() * 3 + 2;
                this.baseSize = this.size;
                this.density = (Math.random() * 30) + 1;
                this.canvasWidth = canvasWidth;
                this.canvasHeight = canvasHeight;
                this.opacity = Math.random() * 0.5 + 0.3;
                this.pulseSpeed = Math.random() * 0.02 + 0.01;
                this.pulse = 0;
            }

            update(mouse) {
                // Gentle floating movement
                this.x += this.vx;
                this.y += this.vy;

                // Bounce off walls
                if (this.x <= 0 || this.x >= this.canvasWidth) {
                    this.vx = -this.vx;
                }
                if (this.y <= 0 || this.y >= this.canvasHeight) {
                    this.vy = -this.vy;
                }

                // Mouse interaction - exact logic from original code
                if (mouse.x != null && mouse.y != null) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx*dx + dy*dy);
                    
                    if (distance < mouse.radius + this.size){
                        if (mouse.x < this.x && this.x < this.canvasWidth - this.size * 10) {
                            this.x += 10;
                        }
                        if (mouse.x > this.x && this.x > this.size * 10) {
                            this.x -= 10;
                        }
                        if (mouse.y < this.y && this.y < this.canvasHeight - this.size * 10) {
                            this.y += 10;
                        }
                        if (mouse.y > this.y && this.y > this.size * 10) {
                            this.y -= 10;
                        }
                    }
                }

                // Pulsing effect
                this.pulse += this.pulseSpeed;
                this.currentOpacity = this.opacity + Math.sin(this.pulse) * 0.2;
            }

            draw(ctx) {
                ctx.save();
                ctx.globalAlpha = this.currentOpacity;
                
                // Main particle
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                ctx.fill();

                // Glow effect
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size * 2, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
                ctx.fill();

                ctx.restore();
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new EnhancedParticleSystem();
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>

</html>