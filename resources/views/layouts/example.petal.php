<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pluma Framework - Exemplos' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Highlight.js para destacar código -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #7209b7;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #f5f7fa;
            padding-top: 56px;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand span {
            color: var(--accent-color);
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        .nav-link.active {
            color: var(--primary-color) !important;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }
        
        .hero h1 {
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .card-title {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        code {
            font-family: 'Fira Code', monospace;
            border-radius: 4px;
        }
        
        pre {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }
        
        .feature-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .feature-icon i {
            font-size: 1.5rem;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        footer a:hover {
            color: white;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            margin-right: 0.5rem;
            transition: background-color 0.3s ease;
        }
        
        .social-icons a:hover {
            background-color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .hero {
                padding: 3rem 0;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">Pluma<span>Framework</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'home' ? 'active' : '' }}" href="/examples">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'templates' ? 'active' : '' }}" href="/examples/templates">Templates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'database' ? 'active' : '' }}" href="/examples/database">Banco de Dados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'forms' ? 'active' : '' }}" href="/examples/forms">Formulários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'docs' ? 'active' : '' }}" href="/docs">Documentação</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentPage === 'github' ? 'active' : '' }}" href="https://github.com/ramysoeg/pluma" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if($showHero ?? true)
    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1>{{ $heroTitle ?? 'Bem-vindo ao Pluma Framework' }}</h1>
            <p>{{ $heroSubtitle ?? 'Um framework PHP moderno com sistema de templates Petal' }}</p>
            @if($heroButton ?? true)
                <a href="/docs" class="btn btn-light btn-lg mt-3">Começar</a>
            @endif
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Pluma Framework</h5>
                    <p>Um framework PHP moderno e elegante para desenvolvimento web rápido e eficiente.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/">Início</a></li>
                        <li><a href="/docs">Documentação</a></li>
                        <li><a href="/examples">Exemplos</a></li>
                        <li><a href="/blog">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Recursos</h5>
                    <ul class="list-unstyled">
                        <li><a href="/docs/templates">Templates</a></li>
                        <li><a href="/docs/database">Banco de Dados</a></li>
                        <li><a href="/docs/routing">Rotas</a></li>
                        <li><a href="/docs/validation">Validação</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Inscreva-se</h5>
                    <p>Receba as últimas atualizações e notícias sobre o Pluma Framework.</p>
                    <form>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Seu email">
                            <button class="btn btn-primary" type="submit">Inscrever</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Pluma Framework. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Highlight.js para destacar código -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('pre code').forEach((el) => {
                hljs.highlightElement(el);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>