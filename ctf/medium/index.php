<?php
// Secret key untuk JWT
$secret = "mungkin secret key nya berupa 3 huruf tetapi saya tidak tahu pasti";
$secret_key = $secret;

// Data pengguna (simulasi database)
$users = [
    2 => ['name' => 'Iyan', 'role' => 'iyan', 'partner' => 'Alya', 'address' => 'Jl. Raya No. 12', 'phone' => '08123456789', 'parents' => 'Bapak Iyan & Ibu Iyan', 'flag' => '{flag}', 'photo' => 'iyan.jpeg'],
    3 => ['name' => 'Guest123', 'role' => 'guest123', 'partner' => 'Tidak Ada', 'address' => 'Alamat Tidak Diketahui', 'phone' => 'Tidak Tersedia', 'parents' => 'Tidak Diketahui', 'photo' => 'guest.png']
];

// Fungsi untuk encode JWT
function encodeJWT($user_id, $role, $secret_key) {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // token berlaku selama 1 jam
    $header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64UrlEncode(json_encode([
        "user_id" => $user_id,
        "role" => $role,
        "iat" => $issuedAt,
        "exp" => $expirationTime
    ]));
    
    // Signature
    $signature = base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secret_key, true));
    
    // Gabungkan header, payload, dan signature
    return "$header.$payload.$signature";
}

// Fungsi untuk encode base64 url-safe
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Fungsi untuk decode base64 url-safe
function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

// Fungsi untuk decode JWT
function decodeJWT($jwt, $secret_key) {
    list($header, $payload, $signature) = explode('.', $jwt);
    $expectedSignature = base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secret_key, true));
    
    if ($expectedSignature !== $signature) {
        return null;
    }
    
    $decodedPayload = json_decode(base64UrlDecode($payload), true);
    if ($decodedPayload['exp'] < time()) {
        return null;  // Token expired
    }
    
    return $decodedPayload;
}

// Cek apakah sudah ada JWT di cookie
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_COOKIE['jwt'])) {
        // Jika tidak ada JWT, set cookie dengan nilai guest123
        $default_token = encodeJWT(3, 'guest123', $secret_key); // Guest123 role
        setcookie("jwt", $default_token, time() + 3600, "/", "", false, true); // HttpOnly
    }

    $jwt = $_COOKIE['jwt'];
    $decoded = decodeJWT($jwt, $secret_key);

    if ($decoded === null) {
        echo json_encode(['message' => 'Invalid or expired token']);
        http_response_code(401);
        exit();
    }

    $user_id = $decoded['user_id'];
    $role = $decoded['role'];

    // Menampilkan profil pengguna
    $user_data = $users[$user_id];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #4CAF50;
        }

        .profile-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #fafafa;
        }

        .profile-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .profile-card h2 {
            margin: 10px 0;
            font-size: 24px;
            color: #333;
        }

        .profile-card p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .flag {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
        }

        .message {
            color: red;
            text-align: center;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        /* Responsif */
        @media (max-width: 1200px) {
            .container {
                padding: 20px;
            }

            .profile-card img {
                width: 100px;
                height: 100px;
            }

            .profile-card h2 {
                font-size: 22px;
            }

            .profile-card p {
                font-size: 14px;
            }

            .flag {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .profile-card img {
                width: 90px;
                height: 90px;
            }

            .profile-card h2 {
                font-size: 20px;
            }

            .profile-card p {
                font-size: 12px;
            }

            .flag {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .profile-card img {
                width: 80px;
                height: 80px;
            }

            .profile-card h2 {
                font-size: 18px;
            }

            .profile-card p {
                font-size: 11px;
            }

            .flag {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Halaman Profil Pengguna</h1>
        </div>

        <div class="profile-card">
            <!-- Tampilkan foto profil sesuai role -->
            <img src="<?php echo $user_data['photo']; ?>" alt="Foto Profil">
            <h2><?php echo $user_data['name']; ?></h2>
            <p><strong>Pacar:</strong> <?php echo $user_data['partner']; ?></p>
            <p><strong>Alamat:</strong> <?php echo $user_data['address']; ?></p>
            <p><strong>Nomor Telepon:</strong> <?php echo $user_data['phone']; ?></p>
            <p><strong>Orang Tua:</strong> <?php echo $user_data['parents']; ?></p>
            
            <?php if ($user_data['role'] == 'iyan'): ?>
                <div class="flag">Flag: {flag}</div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>&copy; 2025 YanCTF. Semua Hak Dilindungi.</p>
        </div>
    </div>

</body>
</html>
