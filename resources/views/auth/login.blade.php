<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem POS Enterprise - Log Masuk</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 380px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            background: #f8fafc;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #4f46e5;
            outline: none;
        }

        .btn {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #4338ca;
        }

        .alert {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2 style="text-align: center; margin-top: 0; color: #1e293b;">POS Enterprise</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 30px;">
            Please authenticate your identity.</p>

        <!-- Tempat menampung Pesan dari Controller -->
        @if(session('error'))
            <div class="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div
                style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- DEMO INFO BOX -->
        <div
            style="background-color: #f1f5f9; border: 1px dashed #94a3b8; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; color: #475569;">
            <strong style="display:block; margin-bottom: 8px; color: #334155;">🔑 Credential Demo:</strong>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span><strong>Admin:</strong> super@mail.com</span>
                <span><strong>Pass:</strong> superadmin123</span>
            </div>
            <div
                style="display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 5px; margin-top: 5px;">
                <span><strong>Kasir:</strong> kasir1@mail.com</span>
                <span><strong>Pass:</strong> kasir123</span>
            </div>
        </div>

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="admin@pos.com" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Bisa dicoba: rahasia123">
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
    </div>

</body>

</html>