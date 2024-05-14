<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings Page</title>
<style>
    body { font-family: Arial, sans-serif; }
    .container { width: 80%; margin: auto; padding: 20px; }
    h2 { color: #333; }
    form { margin-top: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="password"], select {
        width: 100%; padding: 8px; margin-top: 5px;
    }
    .button { background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    .button:hover { background-color: #0056b3; }
</style>
</head>
<body>
<div class="container">
    <h1>Settings</h1>
    
    <!-- Profile Section -->
    <section>
        <h2>Profile Settings</h2>
        <form>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="button">Update Profile</button>
        </form>
    </section>

    <!-- Notification Settings -->
    <section>
        <h2>Notification Settings</h2>
        <form>
            <div class="form-group">
                <label for="emailNotifications">Email Notifications</label>
                <select id="emailNotifications" name="emailNotifications">
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="smsNotifications">SMS Notifications</label>
                <select id="smsNotifications" name="smsNotifications">
                    <option value="enabled">Enabled</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
            <button type="submit" class="button">Update Notifications</button>
        </form>
    </section>

    <!-- Privacy Settings -->
    <section>
        <h2>Privacy Settings</h2>
        <form>
            <div class="form-group">
                <label for="profileVisibility">Profile Visibility</label>
                <select id="profileVisibility" name="profileVisibility">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="friends">Friends Only</option>
                </select>
            </div>
            <button type="submit" class="button">Update Privacy</button>
        </form>
    </section>

    <!-- Account Management -->
    <section>
        <h2>Account Management</h2>
        <form>
            <div class="form-group">
                <label for="deleteAccount">Delete Account</label>
                <button type="button" class="button" onclick="confirmDelete()">Delete Account</button>
            </div>
        </form>
    </section>
</div>

<script>
    function confirmDelete() {
        var response = confirm('Are you sure you want to delete your account? This action cannot be undone.');
        if (response) {
            // Logic to handle account deletion
            alert('Account deleted successfully.');
        }
    }
</script>
</body>
</html>
 