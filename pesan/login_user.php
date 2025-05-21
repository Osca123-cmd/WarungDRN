<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Pengguna</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #6dd5fa, #2980b9);
            color: white;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.15);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        input {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
        }
        .submit-btn {
            width: 100%;
            background: #1e3c72;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 700;
        }
        .submit-btn:hover {
            background: #16325c;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Pengguna</h2>
        <form action="user_login_process.php" method="post">
            <div class="form-group">
                <label for="user-username">Username:</label>
                <input type="text" id="user-username" name="username" required aria-required="true" />
            </div>
            <div class="form-group">
                <label for="user-password">Password:</label>
                <input type="password" id="user-password" name="password" required aria-required="true" />
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
</body>
</html>
