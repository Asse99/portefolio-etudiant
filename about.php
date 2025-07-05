<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Carte Profil Étudiant</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f5f9;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .carte-profil {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
        }

        .carte-profil img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #0066cc;
            margin-right: 20px;
        }

        .infos-profil {
            flex: 1;
        }

        .infos-profil h3 {
            margin: 0 0 6px;
            color: #0066cc;
        }

        .infos-profil p {
            margin: 0;
            color: #555;
            font-size: 0.95em;
        }
    </style>
</head>

<body>

    <div class="carte-profil">
        <img src="https://i.pravatar.cc/150?img=8" alt="Photo de profil">
        <div class="infos-profil">
            <h3>Asse Ndiaye</h3>
            <p>Étudiant en cybersécurité passionné par le taekwondo et les projets IT !</p>
        </div>
    </div>

</body>

</html>