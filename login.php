<?php
session_start();
$host = 'localhost'; 
$dbname = 'ecommerce'; 
$user = 'root'; 
$pass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user_data = $stmt->fetch();
    
    if ($user_data && password_verify($_POST['password'], $user_data['password'])) {
        // Utiliser les bons noms de colonnes de votre table customers
        $_SESSION['customer_id'] = $user_data['customer_id'];
        $_SESSION['first_name'] = $user_data['first_name'];
        $_SESSION['last_name'] = $user_data['last_name'];
        $_SESSION['email'] = $user_data['email'];
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>S&D - Connexion</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
    <style>
        :root {
            --bg-dark: #0a0a0a;
            --bg-card: rgba(20,20,20,0.85);
            --bg-input: #1e1e1e;
            --text-primary: #f5f5f5;
            --text-secondary: #a0a0a0;
            --glass-border: rgba(255,255,255,0.06);
            --accent-cyan: #00f0ff;
            --accent-purple: #a855f7;
            --accent-gradient: linear-gradient(135deg, #00f0ff, #a855f7);
        }

        [data-theme="light"] {
            --bg-dark: #f5f5f7;
            --bg-card: rgba(255,255,255,0.85);
            --bg-input: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #555555;
            --glass-border: rgba(0,0,0,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; margin: 0;
            position: relative; overflow: hidden;
            transition: background 0.4s ease;
        }
        body::before {
            content: ""; position: absolute; top: -30%; right: -20%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,240,255,0.06) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        body::after {
            content: ""; position: absolute; bottom: -30%; left: -20%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168,85,247,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            padding: 3rem; border-radius: 20px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5);
            border: 1px solid var(--glass-border);
            width: 100%; max-width: 420px;
            position: relative; z-index: 2;
            animation: fadeInUp 0.6s ease-out;
            transition: all 0.4s ease;
        }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        h2 {
            font-family: 'Outfit', sans-serif;
            background: var(--accent-gradient);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center; margin-bottom: 2rem; font-size: 1.6rem;
        }
        h2 i { -webkit-text-fill-color: #00f0ff; margin-right: 8px; }
        input {
            width: 100%; padding: 14px 18px;
            background: var(--bg-input); border: 2px solid var(--glass-border);
            border-radius: 12px; color: var(--text-primary); font-family: inherit;
            font-size: 0.95rem; margin-bottom: 1rem; outline: none;
            transition: all 0.3s ease;
        }
        input:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(0,240,255,0.1);
        }
        input::placeholder { color: #5a5a5a; }
        button {
            width: 100%; padding: 14px;
            background: var(--accent-gradient);
            color: #0a0a0a; border: none; border-radius: 12px;
            cursor: pointer; font-weight: 700; font-size: 0.95rem;
            font-family: 'Outfit', sans-serif; text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,240,255,0.25);
        }

        /* Toggle Theme */
        .theme-toggle {
            position: fixed; bottom: 35px; right: 35px; z-index: 10000;
            width: 58px; height: 58px; border-radius: 50%;
            border: 2px solid var(--accent-cyan); background: var(--bg-card);
            backdrop-filter: blur(20px); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: var(--accent-cyan);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .theme-toggle:hover { transform: scale(1.15) rotate(15deg); background: var(--accent-cyan); color: white; }

        .msg-success { color: #bfff00; text-align: center; font-size: 0.85rem; margin-bottom: 1rem; }
        .msg-error { color: #ff4757; text-align: center; font-size: 0.85rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <div class="auth-card">
        <h2><i class='bx bx-log-in-circle'></i> Connexion</h2>
        
        <?php if(isset($_GET['msg'])) echo "<p class='msg-success'>Compte créé ! Connectez-vous.</p>"; ?>
        <?php if(isset($error)) echo "<p class='msg-error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p style="text-align:center; font-size:0.9rem; margin-top:1rem;">Pas de compte ? <a href="inscription.php" style="color:var(--accent-cyan); text-decoration:none;">S'inscrire</a></p>
    </div>
    <script src="theme-toggle.js"></script>
</body>
</html>