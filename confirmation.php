<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=index.php">
    <title>Paiement réussi - S&D</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
    <style>
        :root {
            --accent: #bfff00;
            --bg-dark: #0a0a0a;
            --bg-card: #141414;
            --text-primary: #f5f5f5;
            --text-secondary: #a0a0a0;
            --glass-border: rgba(255,255,255,0.06);
        }

        body {
            background: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            overflow: hidden;
        }

        .success-card {
            text-align: center;
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(24px);
            padding: 60px;
            border-radius: 28px;
            box-shadow: 0 30px 70px rgba(0,0,0,0.5);
            border: 1px solid var(--glass-border);
            max-width: 500px;
            width: 90%;
            animation: bounceIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes bounceIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .icon-check {
            font-size: 90px;
            color: var(--accent);
            margin-bottom: 25px;
            display: inline-block;
            filter: drop-shadow(0 0 20px rgba(191, 255, 0, 0.3));
        }

        h1 { 
            font-family: 'Outfit', sans-serif;
            color: var(--text-primary); 
            margin-bottom: 15px; 
            font-size: 2.2rem;
            letter-spacing: -1px;
        }

        p { color: var(--text-secondary); font-size: 1rem; line-height: 1.6; }

        .loader {
            margin-top: 35px;
            height: 4px;
            width: 140px;
            background: rgba(255,255,255,0.05);
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .loader::after {
            content: "";
            position: absolute;
            left: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #00f0ff, #bfff00);
            animation: load 3s linear forwards;
        }

        @keyframes load {
            to { width: 100%; }
        }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon-check">✔</div>
    <h1>Paiement effectué !</h1>
    <p>Merci pour votre achat. Vos sneakers arrivent bientôt.</p>
    <p style="font-size: 0.8rem;">Redirection vers l'accueil dans quelques secondes...</p>
    <div class="loader"></div>
</div>

</body>
</html>