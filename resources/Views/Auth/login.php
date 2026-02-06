<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Genealogy</title>
    <style>
        :root {
            --bg: #ffffff;
            --text: #000000;
            --border: #e5e7eb;
            --input-bg: #fafafa;
            --error: #000000;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 360px;
            padding: 20px;
        }

        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; letter-spacing: -0.025em; }

        .error-msg {
            border: 1px solid var(--text);
            padding: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .form-group { margin-bottom: 1.5rem; }

        label { display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem; }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--text);
            background: var(--bg);
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background: var(--text);
            color: var(--bg);
            border: 1px solid var(--text);
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        button:hover { background: #333333; }

        .link-text { margin-top: 1.5rem; font-size: 0.875rem; text-align: center; }

        .link-text a { color: var(--text); font-weight: 700; text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h1>SIGN IN</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Continue</button>
    </form>

    <div class="link-text">
        New here? <a href="/register">Create Account</a>
    </div>
</div>

</body>
</html>