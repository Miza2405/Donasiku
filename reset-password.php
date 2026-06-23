<!DOCTYPE html>
<html>
<body>
    <h3>Masukkan Password Baru</h3>
    <form action="api/update-password.php" method="POST">
        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        
        <input type="password" name="password" placeholder="Password Baru" required>
        <button type="submit">Ganti Password</button>
    </form>
</body>
</html>