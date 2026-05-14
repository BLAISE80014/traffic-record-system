# Traffic System - Google OAuth Setup

## 🚨 FIXING "invalid_client" Error

The error you're seeing means your Google OAuth credentials aren't properly configured. Follow these steps carefully:

### Step 1: Verify Your Setup

1. **Check if you're using XAMPP**: Since you ran `sudo /opt/lampp/lampp start`, you're using XAMPP
2. **Your project location**: `/home/blaise-rwanda/Documents/TRS` should be symlinked or copied to `/opt/lampp/htdocs/TRS`
3. **Access URL**: `http://localhost/TRS/`

### Step 2: Google Developer Console Setup (Complete Guide)

1. **Go to Google Developer Console**:
   - Visit: https://console.developers.google.com/
   - Sign in with your Google account

2. **Create or Select Project**:
   - Click "Select a project" (top left)
   - Click "New Project"
   - Name: "Traffic System"
   - Click "Create"

3. **Enable Required APIs**:
   - Go to "APIs & Services" → "Library"
   - Search for "Google People API" (not Google+ API)
   - Click it and enable it

4. **Configure OAuth Consent Screen**:
   - Go to "APIs & Services" → "OAuth consent screen"
   - Choose "External" user type
   - Fill in app details:
     - App name: "Traffic System"
     - User support email: your email
     - Developer contact: your email
   - Click "Save and Continue"
   - On Scopes page, click "Save and Continue"
   - On Test users, add your email if needed
   - Click "Save and Continue"

5. **Create OAuth Credentials**:
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "OAuth client ID"
   - Application type: **Web application**
   - Name: "Traffic System Web Client"
   - Authorized redirect URIs: `http://localhost/TRS/google_callback.php`
   - Click "Create"

6. **Copy Credentials**:
   - You'll get Client ID and Client Secret
   - Keep this window open or copy them

### Step 3: Update Your Code

Edit `google_config.php` and replace the placeholders:

```php
define('GOOGLE_CLIENT_ID', 'your-actual-client-id-here');
define('GOOGLE_CLIENT_SECRET', 'your-actual-client-secret-here');
```

### Step 4: Verify Redirect URI

Make sure the redirect URI in Google Console matches exactly:

- **Correct**: `http://localhost/TRS/google_callback.php`
- **Wrong**: `http://localhost:8000/TRS/google_callback.php` (if using built-in PHP server)

### Step 5: Test

1. Visit: `http://localhost/TRS/index.php`
2. Click "Sign Up with Google"
3. You should be redirected to Google for authentication

## 🔧 Troubleshooting

### If you still get "invalid_client":

1. Double-check Client ID and Secret are copied correctly
2. Ensure no extra spaces or characters
3. Verify the redirect URI matches exactly

### If you get "redirect_uri_mismatch":

1. Check that the redirect URI in Google Console matches your config
2. Make sure you're accessing the app via the correct URL

### If you get "access_denied":

1. Make sure the OAuth consent screen is properly configured
2. Check that your Google account is added as a test user if the app is in testing mode

## 📁 Files to Check

- `google_config.php` - OAuth configuration
- `google_callback.php` - Callback handler
- `index.php` - Login/signup form with Google button
- `dashboard.php` - Protected dashboard page

## 🗄️ Database Setup

Don't forget to run the database migration:

```sql
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) UNIQUE NULL;
ALTER TABLE users MODIFY password VARCHAR(255) NULL;
```
