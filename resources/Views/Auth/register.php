<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Genealogy</title>
    <style>
        :root { --bg: #ffffff; --text: #000000; }
        body { font-family: sans-serif; background: var(--bg); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { width: 100%; max-width: 360px; padding: 20px; }
        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem; }
        .error-msg { place-self: center; width:fit-content; border: 1px solid black; border-radius: 8px; color:#f20707; text-align: center; padding: 10px; font-size: 0.85rem; margin-bottom: 20px; }
        .success-msg { place-self: center; width:fit-content; border: 1px solid black; border-radius: 8px; color:green; text-align: center; padding: 10px; font-size: 0.85rem; margin-bottom: 20px; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; }
        input { width: 100%; padding: 12px; border: 1px solid #000; box-sizing: border-box; outline: none; }
        button { width: 100%; padding: 14px; background: #000; color: #fff; border: none; font-weight: 700; text-transform: uppercase; cursor: pointer; }
        .link-text { margin-top: 1.5rem; font-size: 0.875rem; text-align: center; }
        .link-text a { color: #000; font-weight: 700; }
    </style>
</head>
<body>

<div class="container">
    <h1>REGISTER</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php elseif ((!empty($successMessage))):?>
        <div class="success-msg"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form action="/register" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($oldInput['username'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Referral Code (Optional)</label>
            <input type="text" name="referral_code" value="<?php echo htmlspecialchars($referralCode ?? ''); ?>">
        </div>

        <button type="submit">Create Account</button>
    </form>

    <div class="link-text">
        Already have an account? <a href="/login">Sign In</a>
    </div>
</div>

<script>
    document.querySelector('form').onsubmit = () => {
        document.querySelector('button').innerText = 'PROCCESSING...';
    };

    const error_msg = document.getElementsByClassName("error-msg")[0];
    const success_msg = document.getElementsByClassName("success-msg")[0];

    if(error_msg){
        setTimeout(()=>{
            error_msg.style.display = "none";
        }, 7000);
    }

    if(success_msg){
        setTimeout(()=>{
            success_msg.style.display = "none";
        }, 7000);
    }
</script>

</body>
</html>