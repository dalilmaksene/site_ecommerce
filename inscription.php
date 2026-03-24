<?php
$host = 'localhost';
$dbname = 'ecommerce';
$user = 'root';
$pass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $Password = $_POST['password'];

        // Vérifier la longueur du mot de passe (minimum 8 caractères)
        if (strlen($Password) < 8) {
            $erreur = "Le mot de passe doit contenir au moins 8 caractères.";
        } else {
            $password = password_hash($Password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers (first_name, last_name, email, password) VALUES (?,?,?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name,$last_name, $email, $password]);

            header("Location: login.php?msg=compte_cree");
            exit();
        }
    } catch (PDOException $e) {
        $erreur = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>S&D - Inscription</title>
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
            --accent-gradient: linear-gradient(135deg, #a855f7, #00f0ff);
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
            content: ""; position: absolute; top: -30%; left: -20%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(168,85,247,0.06) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        body::after {
            content: ""; position: absolute; bottom: -30%; right: -20%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,240,255,0.06) 0%, transparent 70%);
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
        h2 i { -webkit-text-fill-color: #a855f7; margin-right: 8px; }
        .input-group { margin-bottom: 1rem; position: relative; }
        input {
            width: 100%; padding: 14px 18px;
            background: var(--bg-input); border: 2px solid var(--glass-border);
            border-radius: 12px; color: var(--text-primary); font-family: inherit;
            font-size: 0.95rem; outline: none;
            transition: all 0.3s ease;
        }
        input:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168,85,247,0.12);
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
            box-shadow: 0 8px 25px rgba(168,85,247,0.25);
        }
        p { text-align: center; margin-top: 1.2rem; font-size: 0.9rem; color: var(--text-secondary); }
        a { color: var(--accent-cyan); text-decoration: none; font-weight: 600; transition: color 0.2s; }
        a:hover { color: #bfff00; }

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
    </style>
</head>
<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <div class="auth-card">
        <h2><i class='bx bx-user-plus'></i> Inscription</h2>
        <?php if(isset($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>
        <form method="POST">
            <div class="input-group"><input type="text" name="first_name" placeholder="Prénom" required></div>
            <div class="input-group"><input type="text" name="last_name" placeholder="Nom" required></div>
            <div class="input-group"><input type="email" name="email" placeholder="Adresse Email" required></div>
            <div class="input-group"><input type="password" name="password" placeholder="Mot de passe" required minlength="8" title="8 caractères ou plus"></div>
            <button type="submit">Créer mon compte</button>
        </form>
        <p>Déjà inscrit ? <a href="login.php">Connectez-vous</a></p>
    </div>
    <script src="theme-toggle.js"></script>
</body>
</html>