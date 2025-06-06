<?php

namespace Pluma\Core;

use Pluma\Http\Controller;

/**
 * Examples Controller
 */
class ExamplesController extends Controller
{
    /**
     * Show the examples index page
     */
    public function index(): string
    {
        return $this->view('examples.index', [
            'title' => 'Exemplos do Pluma Framework',
            'currentPage' => 'home',
            'examples' => [
                [
                    'title' => 'Templates',
                    'description' => 'Aprenda a usar o sistema de templates Petal para criar views elegantes e expressivas.',
                    'url' => '/examples/templates',
                    'icon' => 'fa-code'
                ],
                [
                    'title' => 'Banco de Dados',
                    'description' => 'Explore o sistema de drivers de banco de dados do Pluma Framework.',
                    'url' => '/examples/database',
                    'icon' => 'fa-database'
                ],
                [
                    'title' => 'Formulários',
                    'description' => 'Veja como criar e validar formulários com o Pluma Framework.',
                    'url' => '/examples/forms',
                    'icon' => 'fa-wpforms'
                ],
                [
                    'title' => 'Rotas',
                    'description' => 'Aprenda a configurar rotas para sua aplicação.',
                    'url' => '/examples/routing',
                    'icon' => 'fa-route'
                ],
                [
                    'title' => 'Autenticação',
                    'description' => 'Implemente autenticação de usuários em sua aplicação.',
                    'url' => '/examples/auth',
                    'icon' => 'fa-lock'
                ],
                [
                    'title' => 'API',
                    'description' => 'Crie APIs RESTful com o Pluma Framework.',
                    'url' => '/examples/api',
                    'icon' => 'fa-cloud'
                ]
            ]
        ]);
    }
    
    /**
     * Show the templates example page
     */
    public function templates(): string
    {
        return $this->view('examples.templates', [
            'title' => 'Templates com Petal',
            'currentPage' => 'templates',
            'heroTitle' => 'Sistema de Templates Petal',
            'heroSubtitle' => 'Um sistema de templates poderoso e elegante para o Pluma Framework'
        ]);
    }
    
    /**
     * Show the database example page
     */
    public function database(): string
    {
        return $this->view('examples.database', [
            'title' => 'Banco de Dados',
            'currentPage' => 'database',
            'heroTitle' => 'Sistema de Banco de Dados',
            'heroSubtitle' => 'Um sistema flexível de drivers de banco de dados para o Pluma Framework',
            'drivers' => [
                [
                    'name' => 'MySQL',
                    'description' => 'O driver mais popular para MySQL e MariaDB.',
                    'icon' => 'fa-database'
                ],
                [
                    'name' => 'PostgreSQL',
                    'description' => 'Driver para o poderoso PostgreSQL.',
                    'icon' => 'fa-elephant'
                ],
                [
                    'name' => 'SQLite',
                    'description' => 'Banco de dados leve baseado em arquivo.',
                    'icon' => 'fa-file-alt'
                ],
                [
                    'name' => 'MongoDB',
                    'description' => 'Banco de dados NoSQL orientado a documentos.',
                    'icon' => 'fa-leaf'
                ]
            ]
        ]);
    }
    
    /**
     * Show the forms example page
     */
    public function forms(): string
    {
        return $this->view('examples.forms', [
            'title' => 'Formulários',
            'currentPage' => 'forms',
            'heroTitle' => 'Formulários com Petal',
            'heroSubtitle' => 'Crie e valide formulários facilmente com o Pluma Framework',
            'errors' => [
                'username' => 'O nome de usuário é obrigatório',
                'email' => 'O email informado não é válido',
                'password' => 'A senha deve ter pelo menos 8 caracteres',
                'terms' => 'Você deve concordar com os termos e condições'
            ],
            'old' => [
                'username' => 'johndoe',
                'email' => 'invalid-email'
            ]
        ]);
    }
}