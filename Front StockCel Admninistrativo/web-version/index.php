<?php
// Verificar si el sistema está instalado
if (!file_exists('config/installed.lock')) {
    header('Location: install.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftwarePar - Sistemas Administrativos a Medida</title>
    <meta name="description" content="Desarrollamos soluciones completas para la gestión de stock, ventas, clientes, facturación y reportes. Adaptadas a tu negocio con modelo de licenciamiento para revendedores.">
    <meta name="keywords" content="sistemas administrativos, desarrollo personalizado, licencias software, revendedores, gestión empresarial">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: hsl(210, 90%, 56%);
            --primary-foreground: hsl(211, 100%, 99%);
            --secondary: hsl(207, 90%, 54%);
            --secondary-foreground: hsl(24, 9.8%, 10%);
            --accent: hsl(204, 94%, 68%);
            --accent-foreground: hsl(24, 9.8%, 10%);
            --background: hsl(0, 0%, 100%);
            --foreground: hsl(20, 14.3%, 4.1%);
            --muted: hsl(60, 4.8%, 95.9%);
            --muted-foreground: hsl(25, 5.3%, 44.7%);
            --card: hsl(0, 0%, 100%);
            --card-foreground: hsl(20, 14.3%, 4.1%);
            --border: hsl(20, 5.9%, 90%);
            --radius: 0.5rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: hsl(var(--foreground));
            overflow-x: hidden;
            background-color: hsl(var(--muted));
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: #6b7280;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: hsl(var(--primary));
        }

        /* Hero Section */
        .hero-bg {
            background: linear-gradient(135deg, hsl(210, 90%, 56%) 0%, hsl(207, 90%, 54%) 50%, hsl(204, 94%, 68%) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-bg h1 {
            font-size: clamp(2.5rem, 5vw, 3.75rem);
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-bg .text-accent {
            color: hsl(var(--accent)) !important;
        }

        .hero-bg p {
            font-size: clamp(1.125rem, 2vw, 1.5rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-hero {
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 8px 8px 0;
        }

        .btn-hero-primary {
            background: white;
            color: hsl(var(--primary));
            border: none;
        }

        .btn-hero-primary:hover {
            background: #f8fafc;
            color: hsl(var(--primary));
            text-decoration: none;
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-outline:hover {
            background: white;
            color: hsl(var(--primary));
            text-decoration: none;
        }

        /* Chevron animation */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease-in-out;
        }

        @keyframes slideUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* Cards */
        .card {
            background: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: calc(var(--radius));
            color: hsl(var(--card-foreground));
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .icon-container {
            width: 4rem;
            height: 4rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .icon-primary { background: hsla(var(--primary), 0.1); color: hsl(var(--primary)); }
        .icon-secondary { background: hsla(var(--secondary), 0.1); color: hsl(var(--secondary)); }
        .icon-accent { background: hsla(var(--accent), 0.1); color: hsl(var(--accent)); }

        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--secondary)) 100%);
        }

        .gradient-secondary {
            background: linear-gradient(135deg, hsl(var(--secondary)) 0%, hsl(var(--accent)) 100%);
        }

        .gradient-accent {
            background: linear-gradient(135deg, hsl(var(--accent)) 0%, #a855f7 100%);
        }

        /* How it Works */
        .how-it-works {
            padding: 100px 0;
            background: white;
        }

        .step-card {
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
        }

        .step-card::after {
            content: '';
            position: absolute;
            top: 30px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), transparent);
            display: none;
        }

        @media (min-width: 768px) {
            .step-card:not(:last-child)::after {
                display: block;
            }
        }

        /* Contact */
        .contact {
            padding: 100px 0;
            background: var(--gradient-1);
            color: white;
        }

        .contact-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            background: white;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: white;
            margin-bottom: 20px;
        }

        .footer a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        /* WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 30px;
            right: 30px;
            background: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            background: #20ba5a;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .services, .how-it-works, .contact {
                padding: 60px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="assets/logo.png" alt="SoftwarePar">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#beneficios">Beneficios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#portfolio">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn" style="background: hsl(var(--primary)); color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; margin-left: 8px;" href="login.php">Acceder</a>
                    </li>
                </ul>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <div class="d-md-none">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="collapse d-md-none" id="mobileNav">
            <div class="bg-white border-top px-3 py-2">
                <a href="#inicio" class="d-block py-2 text-decoration-none text-muted">Inicio</a>
                <a href="#servicios" class="d-block py-2 text-decoration-none text-muted">Servicios</a>
                <a href="#beneficios" class="d-block py-2 text-decoration-none text-muted">Beneficios</a>
                <a href="#portfolio" class="d-block py-2 text-decoration-none text-muted">Portfolio</a>
                <a href="#contacto" class="d-block py-2 text-decoration-none text-muted">Contacto</a>
                <div class="py-2">
                    <a class="btn w-100" style="background: hsl(var(--primary)); color: white; text-decoration: none;" href="login.php">Acceder</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-bg position-relative">
        <div class="container text-center text-white">
            <div class="hero-content animate-slide-up">
                <h1>
                    Sistemas Administrativos
                    <span class="d-block text-accent">a Medida</span>
                </h1>
                <p class="mx-auto" style="max-width: 64rem; opacity: 0.9;">
                    Desarrollamos soluciones completas para la gestión de stock, ventas, clientes, facturación y reportes. Adaptadas a tu negocio con modelo de licenciamiento para revendedores.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="login.php" class="btn btn-primary btn-lg px-4 py-2" style="background: rgba(255, 255, 255, 0.2); border: 2px solid white; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">Acceder al Sistema</a>
                    <a href="#contacto" class="btn btn-outline-light btn-lg px-4 py-2" style="border: 2px solid white; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">Solicitar Presupuesto</a>
                </div>
            </div>
        </div>
        
        <!-- Floating Animation -->
        <div class="position-absolute bottom-0 start-50 translate-middle-x animate-float mb-4">
            <i class="fas fa-chevron-down text-white" style="font-size: 2rem;"></i>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Nuestros Servicios</h2>
                <p class="fs-5 text-muted mx-auto" style="max-width: 48rem;">
                    En SoftwarePar nos especializamos en el desarrollo de sistemas administrativos a medida para negocios, emprendedores, empresas y tiendas
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-container icon-primary">
                                <i class="fas fa-cog" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title mb-3">Desarrollo a Medida</h5>
                            <p class="card-text text-muted">
                                Creamos sistemas completos y personalizados para gestión de stock, ventas, clientes, facturación y reportes adaptados a tu rubro específico
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-container icon-secondary">
                                <i class="fas fa-award" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title mb-3">Adaptación Empresarial</h5>
                            <p class="card-text text-muted">
                                Adaptamos cada sistema a las necesidades específicas de tu empresa, garantizando que se ajuste perfectamente a tus procesos de negocio
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-hover h-100">
                        <div class="card-body p-4 text-center">
                            <div class="icon-container icon-accent">
                                <i class="fas fa-shield-alt" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title mb-3">Licenciamiento para Revendedores</h5>
                            <p class="card-text text-muted">
                                Si querés revender nuestros sistemas, te ofrecemos licencias exclusivas para distribuir bajo tu nombre con soporte completo
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Licensing Model Section -->
    <section class="py-5 text-white" style="background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--secondary)) 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Modelo de Licenciamiento</h2>
                <p class="fs-5 mx-auto" style="opacity: 0.9; max-width: 48rem;">
                    Tres pasos sencillos para expandir tu negocio con nuestros sistemas
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-center mx-auto mb-4" 
                             style="width: 4rem; height: 4rem; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                            <i class="fas fa-cog text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">1. Desarrollamos</h4>
                        <p style="opacity: 0.9;">
                            Creamos el sistema administrativo personalizado para tu rubro y necesidades específicas
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-center mx-auto mb-4" 
                             style="width: 4rem; height: 4rem; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                            <i class="fas fa-award text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">2. Adaptamos</h4>
                        <p style="opacity: 0.9;">
                            Lo personalizamos completamente para tu empresa, ajustándolo a tus procesos y marca
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-center mx-auto mb-4" 
                             style="width: 4rem; height: 4rem; background: rgba(255, 255, 255, 0.2); border-radius: 50%;">
                            <i class="fas fa-shield-alt text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">3. Licenciamos</h4>
                        <p style="opacity: 0.9;">
                            Te otorgamos licencias exclusivas para que puedas revenderlo bajo tu nombre con soporte completo
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="#contacto" class="btn btn-lg" style="background: white; color: #2563eb; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    Consultar Planes de Licencias
                </a>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="beneficios" class="py-5" style="background-color: hsl(var(--muted));">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">¿Por qué elegir SoftwarePar?</h2>
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 2rem; height: 2rem; background: #dcfce7; border-radius: 50%; flex-shrink: 0;">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">✔️ Desarrollamos el sistema</h5>
                                <p class="text-muted mb-0">Creamos soluciones completas adaptadas a tu rubro específico</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 2rem; height: 2rem; background: #dcfce7; border-radius: 50%; flex-shrink: 0;">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">✔️ Lo adaptamos a tu empresa</h5>
                                <p class="text-muted mb-0">Personalización completa para tus procesos y necesidades</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 2rem; height: 2rem; background: #dcfce7; border-radius: 50%; flex-shrink: 0;">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">✔️ Licencias para revender</h5>
                                <p class="text-muted mb-0">Modelo flexible para distribuir bajo tu nombre con soporte completo</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card gradient-bg text-white">
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3" style="background: rgba(255, 255, 255, 0.2); border-radius: 8px;">
                                        <i class="fas fa-users mb-2" style="font-size: 1.5rem;"></i>
                                        <div class="small">Revendedores</div>
                                        <div class="h4 fw-bold mb-0">245</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3" style="background: rgba(255, 255, 255, 0.2); border-radius: 8px;">
                                        <i class="fas fa-key mb-2" style="font-size: 1.5rem;"></i>
                                        <div class="small">Licencias</div>
                                        <div class="h4 fw-bold mb-0">1,840</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-chart-bar mb-3" style="font-size: 3rem;"></i>
                                <div class="h5 fw-semibold">Control Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Funcionalidades del Sistema</h2>
                <p class="fs-5 text-muted mx-auto" style="max-width: 32rem;">
                    Explora las características principales de nuestro sistema de gestión
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover overflow-hidden h-100">
                        <div class="d-flex align-items-center justify-content-center gradient-bg text-white" style="height: 12rem;">
                            <i class="fas fa-chart-bar" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title mb-2">Dashboard Ejecutivo</h5>
                            <p class="card-text text-muted">Panel principal con métricas clave y visualización de datos</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover overflow-hidden h-100">
                        <div class="d-flex align-items-center justify-content-center gradient-secondary text-white" style="height: 12rem;">
                            <i class="fas fa-cog" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title mb-2">Gestión de Usuarios</h5>
                            <p class="card-text text-muted">CRUD completo para administrar revendedores y permisos</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover overflow-hidden h-100">
                        <div class="d-flex align-items-center justify-content-center gradient-accent text-white" style="height: 12rem;">
                            <i class="fas fa-award" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title mb-2">Control de Licencias</h5>
                            <p class="card-text text-muted">Asignación, renovación y monitoreo de licencias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5" style="background-color: hsl(var(--muted));">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Lo que dicen nuestros clientes</h2>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                                     style="width: 3rem; height: 3rem; background: hsl(var(--primary)); border-radius: 50%;">
                                    JS
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Juan Sánchez</h6>
                                    <small class="text-muted">CEO, TechSolutions</small>
                                </div>
                            </div>
                            <p class="text-muted fst-italic mb-0">
                                "SoftwarePar revolucionó nuestra gestión de licencias. La interfaz es intuitiva y el soporte excepcional."
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                                     style="width: 3rem; height: 3rem; background: hsl(var(--secondary)); border-radius: 50%;">
                                    MR
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">María Rodríguez</h6>
                                    <small class="text-muted">Gerente, SoftwareDistrib</small>
                                </div>
                            </div>
                            <p class="text-muted fst-italic mb-0">
                                "El sistema nos permite controlar eficientemente más de 500 licencias. Muy recomendado."
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                                     style="width: 3rem; height: 3rem; background: hsl(var(--accent)); border-radius: 50%;">
                                    CL
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1">Carlos López</h6>
                                    <small class="text-muted">Director, InfoSystems</small>
                                </div>
                            </div>
                            <p class="text-muted fst-italic mb-0">
                                "La implementación fue sencilla y los resultados inmediatos. Excelente inversión."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contacto" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Contáctanos</h2>
                <p class="fs-5 text-muted mx-auto" style="max-width: 32rem;">
                    ¿Listo para mejorar tu gestión de licencias? Hablemos
                </p>
            </div>
            
            <div class="row g-5">
                <div class="col-lg-6">
                    <h3 class="h4 fw-semibold mb-4">Información de Contacto</h3>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 3rem; height: 3rem; background: hsla(var(--primary), 0.1); border-radius: 50%;">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Teléfono</h6>
                                <p class="text-muted mb-0">+54 11 7062 7214</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 3rem; height: 3rem; background: hsla(var(--primary), 0.1); border-radius: 50%;">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Email</h6>
                                <p class="text-muted mb-0">info@softwarepar.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3" 
                                 style="width: 3rem; height: 3rem; background: hsla(var(--primary), 0.1); border-radius: 50%;">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Ubicación</h6>
                                <p class="text-muted mb-0">Buenos Aires, Argentina</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body p-4">
                            <form id="contactForm" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label small fw-medium text-muted">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                                    <div class="invalid-feedback">Por favor ingresa tu nombre</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-medium text-muted">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="tu@email.com" required>
                                    <div class="invalid-feedback">Por favor ingresa un email válido</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mensaje" class="form-label small fw-medium text-muted">Mensaje</label>
                                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" placeholder="Cuéntanos sobre tu proyecto..." required></textarea>
                                    <div class="invalid-feedback">Por favor ingresa tu mensaje</div>
                                </div>
                                
                                <button type="submit" id="submitBtn" class="btn w-100" style="background: #2563eb; color: white; border: none; font-weight: 600; padding: 12px;">
                                    <span id="submitText">Enviar Mensaje</span>
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </form>
                            
                            <!-- Alert para mostrar mensajes -->
                            <div id="alertContainer" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 text-white" style="background-color: #1f2937;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="mb-3">
                        <img src="assets/logo_blanco.png" alt="SoftwarePar" style="height: 48px; width: auto;">
                    </div>
                    <p class="text-muted mb-0">
                        Sistemas administrativos personalizados para empresas con modelo de licenciamiento flexible
                    </p>
                </div>
                
                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Producto</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Características</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Precios</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Seguridad</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Empresa</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Sobre Nosotros</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h6 class="fw-semibold mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Documentación</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Centro de Ayuda</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Estado del Sistema</a></li>
                    </ul>
                </div>
            </div>
            
            <hr style="border-color: #374151; margin: 2rem 0;">
            <div class="text-center">
                <p class="text-muted mb-0">&copy; 2025 SoftwarePar. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <div style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 1050;">
        <button onclick="openWhatsApp()" 
                style="position: relative; background: #25d366; color: white; width: 4rem; height: 4rem; border-radius: 50%; border: none; box-shadow: 0 8px 32px rgba(37, 211, 102, 0.3); transition: all 0.3s ease; cursor: pointer;"
                onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 12px 40px rgba(37, 211, 102, 0.4)';"
                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 32px rgba(37, 211, 102, 0.3)';"
                aria-label="Contactar por WhatsApp">
            <i class="fab fa-whatsapp" style="font-size: 1.75rem;"></i>
            
            <!-- Tooltip -->
            <div style="position: absolute; right: 100%; top: 50%; transform: translateY(-50%); background: #1f2937; color: white; padding: 0.5rem 0.75rem; border-radius: 6px; white-space: nowrap; opacity: 0; transition: opacity 0.3s; pointer-events: none; margin-right: 0.75rem;"
                 onmouseover="this.style.opacity='1';" 
                 onmouseout="this.style.opacity='0';">
                Contactar por WhatsApp
                <div style="position: absolute; left: 100%; top: 50%; transform: translateY(-50%); border: 4px solid transparent; border-left-color: #1f2937;"></div>
            </div>
            
            <!-- Ripple effect -->
            <div style="position: absolute; inset: 0; border-radius: 50%; background: rgba(37, 211, 102, 0.2); animation: pulse 2s infinite;"></div>
        </button>
    </div>

    <script>
        function openWhatsApp() {
            const phoneNumber = "+5491161396633";
            const message = "Hola, me interesa conocer más sobre los sistemas administrativos de SoftwarePar";
            const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, "_blank");
        }
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add pulse animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.5; }
                100% { opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Formulario de contacto
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const alertContainer = document.getElementById('alertContainer');
            
            // Validar formulario
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            
            // Mostrar loading
            submitBtn.disabled = true;
            submitText.textContent = 'Enviando...';
            submitSpinner.classList.remove('d-none');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            const data = {
                nombre: formData.get('nombre'),
                email: formData.get('email'),
                mensaje: formData.get('mensaje')
            };
            
            try {
                const response = await fetch('enviar_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Éxito
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    // Limpiar formulario
                    form.reset();
                    form.classList.remove('was-validated');
                    
                } else {
                    // Error
                    alertContainer.innerHTML = `
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
                
            } catch (error) {
                console.error('Error:', error);
                alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i>
                        Error de conexión. Por favor, inténtalo más tarde o contáctanos por WhatsApp.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            } finally {
                // Restaurar botón
                submitBtn.disabled = false;
                submitText.textContent = 'Enviar Mensaje';
                submitSpinner.classList.add('d-none');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
