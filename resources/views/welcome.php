<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 2rem;
        }
        header {
            background-color: #35495e;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        .content {
            background-color: #fff;
            padding: 2rem;
            margin-top: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem 0;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><?= htmlspecialchars($title) ?></h1>
        </div>
    </header>
    
    <div class="container">
        <div class="content">
            <h2>Welcome to Pluma Framework</h2>
            <p><?= htmlspecialchars($message) ?></p>
            
            <h3>Getting Started</h3>
            <p>This is a simple example of a view in the Pluma Framework. You can create your own views in the <code>resources/views</code> directory.</p>
            
            <h3>Features</h3>
            <ul>
                <li>Simple and lightweight</li>
                <li>MVC architecture</li>
                <li>Routing system</li>
                <li>Database abstraction</li>
                <li>View rendering</li>
                <li>Dependency injection</li>
            </ul>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <p>Pluma Framework &copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>