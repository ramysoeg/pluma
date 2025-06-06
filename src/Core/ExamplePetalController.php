<?php

namespace Pluma\Core;

use Pluma\Http\Controller;

/**
 * Example Petal Controller
 */
class ExamplePetalController extends Controller
{
    /**
     * Show the Petal demo page
     */
    public function demo(): string
    {
        return $this->view('examples.petal-demo', [
            'title' => 'Demonstração do Sistema de Templates Petal',
            'description' => 'Esta página demonstra os recursos e a sintaxe do sistema de templates Petal do Pluma Framework.',
            'features' => [
                [
                    'name' => 'Herança de Templates',
                    'description' => 'Estenda layouts base e substitua seções específicas'
                ],
                [
                    'name' => 'Diretivas',
                    'description' => 'Use @if, @foreach, @for, @while e outras diretivas para controle de fluxo'
                ],
                [
                    'name' => 'Escape Automático',
                    'description' => 'Variáveis são automaticamente escapadas para prevenir XSS'
                ],
                [
                    'name' => 'Inclusão de Sub-templates',
                    'description' => 'Inclua outros templates com @include'
                ],
                [
                    'name' => 'Comentários',
                    'description' => 'Adicione comentários que não aparecem no HTML final'
                ],
                [
                    'name' => 'Código PHP Embutido',
                    'description' => 'Execute código PHP diretamente com @php'
                ],
            ],
            'showDemo' => true,
            'user' => [
                'id' => 1,
                'name' => 'Usuário Atual',
                'email' => 'usuario@exemplo.com',
                'isAdmin' => true
            ],
            'users' => [
                [
                    'id' => 1,
                    'name' => 'João Silva',
                    'email' => 'joao@exemplo.com',
                    'isAdmin' => true
                ],
                [
                    'id' => 2,
                    'name' => 'Maria Santos',
                    'email' => 'maria@exemplo.com',
                    'isAdmin' => false
                ],
                [
                    'id' => 3,
                    'name' => 'Pedro Oliveira',
                    'email' => 'pedro@exemplo.com',
                    'isAdmin' => false
                ],
                [
                    'id' => 4,
                    'name' => 'Ana Souza',
                    'email' => 'ana@exemplo.com',
                    'isAdmin' => true
                ],
                [
                    'id' => 5,
                    'name' => 'Carlos Ferreira',
                    'email' => 'carlos@exemplo.com',
                    'isAdmin' => false
                ],
                [
                    'id' => 6,
                    'name' => 'Lúcia Pereira',
                    'email' => 'lucia@exemplo.com',
                    'isAdmin' => false
                ],
            ]
        ]);
    }
}