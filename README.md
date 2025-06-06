# Pluma Framework

Pluma é um framework PHP leve e moderno que utiliza sistema de namespaces para organização do código.

## Características

- Sistema de roteamento simples e poderoso
- Arquitetura MVC
- Injeção de dependências
- ORM básico para interação com banco de dados
- Sistema de templates
- Middleware
- PSR-4 autoloading

## Requisitos

- PHP 7.4 ou superior
- Composer

## Instalação

```bash
composer create-project ramysoeg/pluma my-project
cd my-project
```

## Uso Básico

```php
<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';

use Pluma\Core\Application;

$app = new Application();
$app->run();
```

## Licença

MIT