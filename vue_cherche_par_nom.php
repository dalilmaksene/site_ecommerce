<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Recherche de chausssure</title>
  <meta NAME="Author" CONTENT="Sami Youssoufi">
  <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
  <style>
    :root {
      --accent: #00f0ff;
      --accent-purple: #a855f7;
      --bg-dark: #0a0a0a;
      --bg-card: #141414;
      --text-primary: #f5f5f5;
      --text-secondary: #a0a0a0;
      --glass-border: rgba(255,255,255,0.06);
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-dark);
      color: var(--text-primary);
      margin: 0;
      padding: 40px clamp(20px, 5vw, 80px);
      min-height: 100vh;
    }

    h1 {
      text-align: center;
      margin: 40px 0 60px;
      font-family: 'Outfit', sans-serif;
      font-size: clamp(2.5rem, 8vw, 4.5rem);
      font-weight: 900;
      letter-spacing: -2px;
      background: linear-gradient(135deg, #00f0ff, #a855f7);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      max-width: 1000px;
      margin: 0 auto;
      border-collapse: separate;
      border-spacing: 0;
      background-color: rgba(20, 20, 20, 0.6);
      backdrop-filter: blur(20px);
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
      border-radius: 24px;
      overflow: hidden;
      border: 1px solid var(--glass-border);
    }

    th, td {
      padding: 20px 30px;
      text-align: left;
      border-bottom: 1px solid var(--glass-border);
    }

    th {
      background-color: rgba(255, 255, 255, 0.03);
      color: var(--accent);
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-size: 0.9rem;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background-color: rgba(255, 255, 255, 0.03);
    }

    p {
      text-align: center;
      margin-top: 40px;
      color: #ff4757;
      font-weight: 700;
      font-size: 1.2rem;
      font-family: 'Outfit', sans-serif;
    }

    ul {
      list-style-type: none;
      text-align: center;
      margin-top: 60px;
    }

    ul li a {
      text-decoration: none;
      color: var(--text-primary);
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-size: 0.9rem;
      padding: 16px 40px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--glass-border);
      border-radius: 16px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-block;
    }

    ul li a:hover {
      background: var(--accent);
      color: #0a0a0a;
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 240, 255, 0.3);
      border-color: var(--accent);
    }
    [data-theme="light"] {
      --bg-dark: #f5f5f7;
      --bg-card: #ffffff;
      --text-primary: #1a1a1a;
      --text-secondary: #555555;
      --glass-border: rgba(0, 0, 0, 0.08);
    }

    .theme-toggle {
      position: fixed; bottom: 35px; right: 35px; z-index: 10000;
      width: 58px; height: 58px; border-radius: 50%;
      border: 2px solid var(--accent); background: var(--bg-card, #141414);
      backdrop-filter: blur(20px); cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.6rem; color: var(--accent);
      box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .theme-toggle:hover { transform: scale(1.15) rotate(15deg); background: var(--accent); color: white; }
  </style>
</head>
<body>
  <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
  <script src="theme-toggle.js"></script>
  <h1>Recherche</h1>
  <table>
    <tr>
      <th>Marque</th>
      <th>Modele</th>
      <th>Prix</th>
    </tr>
    <?php
    $marque = $_POST["brand_name"];

    include './fonctions.php';
    $les_adherents = array();
    $les_adherents = obtenir_recherche_par_nom($marque);

    $nb = count($les_adherents);

    if ($nb == 0) {
      echo "<p>Aucune paire trouvé</p>";
      die();
    } else {
      $i = 0;
      while ($i < $nb) {
        $un_adherent = $les_adherents[$i];
        $prix = $un_adherent['list_price'];
        $modele = $un_adherent['product_name'];
        $marque = $un_adherent['brand_name'];
        echo "<tr><td>$marque</td><td>$modele</td><td>$prix</td></tr>";
        $i = $i + 1;
      }
    }
    ?>
  </table>
  <ul>
    <li><a href='index.php'>retour accueil</a></li>
  </ul>
</body>
</html>
