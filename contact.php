<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
    <style>
        :root {
            --accent: #00f0ff;
            --bg-dark: #0a0a0a;
            --bg-card: rgba(20, 20, 20, 0.8);
            --text-primary: #f5f5f5;
            --text-secondary: #a0a0a0;
            --glass-border: rgba(255,255,255,0.06);
        }

        [data-theme="light"] {
            --bg-dark: #f5f5f7;
            --bg-card: rgba(255, 255, 255, 0.85);
            --text-primary: #1a1a1a;
            --text-secondary: #555555;
            --glass-border: rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            transition: background 0.4s ease, color 0.4s ease;
        }

        /* Toggle Theme */
        .theme-toggle {
            position: fixed; bottom: 35px; right: 35px; z-index: 10000;
            width: 58px; height: 58px; border-radius: 50%;
            border: 2px solid var(--accent); background: var(--bg-card);
            backdrop-filter: blur(20px); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: var(--accent);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .theme-toggle:hover { transform: scale(1.15) rotate(15deg); background: var(--accent); color: white; }

        body::before {
            content: ""; position: absolute; top: -20%; right: -10%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,240,255,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        .contact-container {
            max-width: 600px;
            width: 90%;
            margin: 80px auto;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            position: relative;
            animation: fadeInUp 0.8s ease-out;
            transition: background 0.4s ease, border 0.4s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #00f0ff, #a855f7);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .contact-info {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .contact-info p {
            margin: 20px 0;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .contact-info strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .contact-info a {
            color: var(--accent);
            text-decoration: none;
            transition: 0.3s;
            position: relative;
        }

        .contact-info a::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: 0.3s;
        }

        .contact-info a:hover {
            color: #bfff00;
        }
        
        .contact-info a:hover::after {
            width: 100%;
            background: #bfff00;
        }
    </style>
</head>
<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <script src="theme-toggle.js"></script>

<div class="contact-container">
    <h1>Nous contacter</h1>
    <div class="contact-info">
        <p><strong>Adresse :</strong> 123 Rue de la République, 75000 Paris, France</p>
        <p><strong>Téléphone :</strong> <a href="tel:+33123456789">+33 1 23 45 67 89</a></p>
        <p><strong>Email :</strong> <a href="mailto:contact@exemple.com">contact@exemple.com</a></p>
    </div>
    <div style="margin-top: 2.5rem; text-align: center;">
        <a href="index.php" style="color: var(--accent); font-weight: 600; font-size: 0.95rem; transition: 0.3s;">← Retour à l'accueil</a>
    </div>
</div>

</body>
</html>
