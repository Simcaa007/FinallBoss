<?php

    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }

    $city = $_SESSION['location'];

    require_once('Db.php');
    Db::connect('127.0.0.1', 'finalboss', 'root', '');

    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($city) . "&format=json&limit=1";

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: MojeIoTApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $res = file_get_contents($url, false, $context);
    $data = json_decode($res, true);

    if (!empty($data)) {
        $lat = $data[0]['lat'];
        $lon = $data[0]['lon'];
    }

    $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&mode=json&appid=d8b8d92c46b417ba4686258e6cc97a7d";

    $vysledek = file_get_contents($url);
    $data = json_decode($vysledek, true);

    if (isset($data['main']['temp'])) {
        $teplota = $data['main']['temp'];
    }

    Db::insert('data', [
        'user_id' => $_SESSION['user_id'],
        'temp' => $teplota,
        'created_at' => date("Y-m-d H:i:s"),
        'location' => $_SESSION['location']
    ]);

    $historie = Db::queryAll("SELECT temp, created_at FROM data WHERE location = ? ORDER BY created_at ASC", $city);

    $grafCasy = [];
    $grafTeploty = [];

    foreach ($historie as $radek) {
        $grafCasy[] = date("H:i", strtotime($radek['created_at']));
        $grafTeploty[] = $radek['temp'];
    }

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <title>IoT Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Tady je kompletní CSS přímo v souboru pro jistotu */
        :root { --bg-dark: #1a1c1e; --card-bg: #25282c; --pastel-brown: #C19A6B; --text-light: #e0e0e0; --text-dim: #a0a0a0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg-dark); color: white; margin: 0; }
        .navbar { display: flex; justify-content: space-between; padding: 1rem 10%; background: #111; border-bottom: 1px solid #333; }
        .logo span { color: var(--pastel-brown); }
        .dashboard-container { padding: 2rem 10%; }
        .stats-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
        .card { background: var(--card-bg); padding: 2rem; border-radius: 15px; border: 1px solid #333; }
        .temp-value { font-size: 3rem; color: var(--pastel-brown); display: block; }
        canvas { width: 100% !important; height: auto !important; }
        @media (max-width: 800px) { .stats-grid { grid-template-columns: 1fr; } }
        /* Kontejner pro jméno a tlačítka */
.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.user-name {
    color: var(--text-dim);
    font-size: 0.9rem;
}

/* Skupina tlačítek vedle sebe */
.nav-actions {
    display: flex;
    gap: 0.8rem;
    align-items: center;
}

/* Styl pro tlačítko Nastavení */
.btn-settings {
    color: var(--pastel-brown);
    text-decoration: none;
    font-size: 0.9rem;
    border: 1px solid var(--pastel-brown);
    padding: 0.4rem 0.8rem;
    border-radius: 5px;
    transition: 0.3s;
}

.btn-settings:hover {
    background: var(--pastel-brown);
    color: white;
}

/* Styl pro tlačítko Odhlásit (už máš, jen pro kontrolu) */
.btn-logout {
    color: #ff6b6b;
    text-decoration: none;
    font-size: 0.9rem;
    border: 1px solid #ff6b6b;
    padding: 0.4rem 0.8rem;
    border-radius: 5px;
    transition: 0.3s;
}

.btn-logout:hover {
    background: #ff6b6b;
    color: white;
}
    </style>
</head>
<body>

    <nav class="navbar">
    <div class="logo">IoT<span>Connect</span></div>
    
    <div class="user-info">
        <span class="user-name">Ahoj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        
        <div class="nav-actions">
            <a href="settings.php" class="btn-settings">Nastavení</a>
            <a href="logout.php" class="btn-logout">Odhlásit se</a>
        </div>
    </div>
</nav>

    <div class="dashboard-container">
        <div class="stats-grid">
            <div class="card">
                <h3>Aktuální stav</h3>
                <span class="temp-value"><?php echo round($teplota, 1); ?></span>
                <p>Lokace: <?= $city?></p>
            </div>
            <div class="card">
                <h3>Historie teploty</h3>
                <canvas id="tempChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('tempChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($grafCasy); ?>,
                datasets: [{
                    label: 'Teplota',
                    data: <?php echo json_encode($grafTeploty); ?>,
                    borderColor: '#C19A6B',
                    backgroundColor: 'rgba(193, 154, 107, 0.1)',
                    fill: true
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>