<?php
session_start();
require_once 'check_session.php';

// Sécurité : si pas connecté, retour à l'index
if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';
$customer_id = $_SESSION['customer_id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        
        // On ne change le mot de passe que s'il est rempli
        if (!empty($_POST['new_password'])) {
            $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $sql = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, password = ? WHERE customer_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $email, $hashed_password, $customer_id]);
        } else {
            $sql = "UPDATE customers SET first_name = ?, last_name = ?, email = ? WHERE customer_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $email, $customer_id]);
        }
        $msg = "Profil mis à jour avec succès !";
    }

    // Récupérer les infos actuelles pour les afficher dans les champs
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $user = $stmt->fetch();

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - S&D</title>
    <link rel="stylesheet" href=""> </head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
    <style>
    :root {
        --accent: #00f0ff;
        --accent-purple: #a855f7;
        --bg-dark: #0a0a0a;
        --bg-card: rgba(20, 20, 20, 0.8);
        --text-primary: #f5f5f5;
        --text-secondary: #a0a0a0;
        --text-muted: #5a5a5a;
        --glass-border: rgba(255, 255, 255, 0.06);
    }

    [data-theme="light"] {
        --bg-dark: #f5f5f7;
        --bg-card: rgba(255, 255, 255, 0.85);
        --text-primary: #1a1a1a;
        --text-secondary: #555555;
        --text-muted: #999999;
        --glass-border: rgba(0, 0, 0, 0.08);
    }

    body {
        background: var(--bg-dark);
        font-family: 'Inter', 'Poppins', sans-serif;
        color: var(--text-primary);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
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
        content: ""; position: absolute; top: -10%; left: -5%;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(168,85,247,0.05) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }

    .profile-container {
        max-width: 600px;
        margin: 80px auto;
        padding: 50px;
        background: var(--bg-card);
        backdrop-filter: blur(24px);
        border-radius: 28px;
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        border: 1px solid var(--glass-border);
        position: relative;
        z-index: 10;
        animation: fadeInUp 0.8s ease-out;
        transition: background 0.4s ease, border 0.4s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-header {
        text-align: center;
        margin-bottom: 45px;
    }

    .profile-header h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: -1.5px;
        margin-bottom: 12px;
        background: linear-gradient(135deg, #00f0ff, #a855f7);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .member-since {
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 14px;
        margin-bottom: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success { background: rgba(191, 255, 0, 0.1); color: #bfff00; border: 1px solid rgba(191, 255, 0, 0.2); }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group input {
        width: 100%;
        padding: 15px 20px;
        background: rgba(255,255,255,0.03);
        border: 2px solid var(--glass-border);
        border-radius: 14px;
        color: var(--text-primary);
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--accent);
        background: rgba(255,255,255,0.06);
        box-shadow: 0 0 0 4px rgba(0, 240, 255, 0.1);
    }

    .btn {
        padding: 16px;
        border-radius: 14px;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        text-align: center;
        border: none;
        display: inline-block;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00f0ff, #a855f7);
        color: #0a0a0a;
        box-shadow: 0 4px 15px rgba(0, 240, 255, 0.15);
    }

    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 240, 255, 0.3);
    }

    .btn-back {
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-align: center;
        display: block;
        margin-top: 20px;
        transition: 0.3s;
    }
    
    .btn-back:hover {
        color: var(--accent);
    }

    .divider {
        margin: 40px 0;
        border: none;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--glass-border), transparent);
    }

    .btn-logout {
        color: #ff4757;
        font-size: 0.95rem;
        font-weight: 800;
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s;
        display: inline-block;
        background: rgba(255, 71, 87, 0.05);
        border: 1px solid rgba(255, 71, 87, 0.1);
    }

    .btn-logout:hover {
        background: #ff4757;
        color: #fff;
        transform: scale(1.05);
    }

    @media (max-width: 600px) {
        .profile-container {
            margin: 20px;
            padding: 30px;
        }
    }
    </style>
</head>
<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <script src="theme-toggle.js"></script>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Mon Profil</h1>
            <p class="member-since">Membre S&D</p>
        </div>

        <?php if(isset($msg)): ?>
            <div class="alert alert-success">✓ <?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="profil.php">
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe (optionnel)</label>
                <input type="password" name="new_password" placeholder="Laisser vide pour ne pas changer">
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>

        <a href="index.php" class="btn-back">← Retour à l'accueil</a>

        <hr class="divider">

        <div style="text-align: center;">
            <a href="logout.php" class="btn-logout">Se déconnecter</a>
        </div>
    </div>
</body>
</html>