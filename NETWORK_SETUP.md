# Network Setup Guide for Mobile App Development

## Quick Start

Your Laravel server is now configured to accept connections from mobile devices on your local network.

## Verify Your Setup

### 1. Find Your Computer's IP Address

Run this command in PowerShell to find your IP address:

```powershell
ipconfig
```

Look for your active network adapter (usually "Wireless LAN adapter Wi-Fi" or "Ethernet adapter"):
```
IPv4 Address. . . . . . . . . . . : 192.168.100.XX
```

### 2. Start the Server (if not already running)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

This makes the server accessible from any device on your network at:
```
http://192.168.100.XX:8000
```

### 3. Configure Windows Firewall

You may need to allow connections on port 8000:

**Option A: PowerShell (Run as Administrator)**
```powershell
New-NetFirewallRule -DisplayName "Laravel Dev Server" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow
```

**Option B: Windows Firewall GUI**
1. Open Windows Defender Firewall
2. Click "Advanced settings"
3. Click "Inbound Rules" → "New Rule"
4. Select "Port" → Next
5. Select "TCP" and enter port "8000"
6. Allow the connection → Next
7. Select all profiles → Next
8. Name it "Laravel Dev Server" → Finish

### 4. Update Flutter App Configuration

Make sure your Flutter app uses the correct IP address:

```dart
// In your Flutter app configuration
final baseUrl = 'http://192.168.100.XX:8000'; // Replace XX with your IP
```

### 5. Test the Connection

From your mobile device:

1. **Test in browser first**: Open `http://192.168.100.XX:8000/api/v1/auth/register` on your phone's browser
2. **Check server logs**: The Laravel console should show the request
3. **Try the app**: Launch your Flutter app and attempt registration

## Troubleshooting

### Connection Timeout (DioError)

**Problem**: The app cannot reach the server.

**Solutions**:
1. ✅ Server is running with `--host=0.0.0.0` (already configured)
2. ✅ CORS is properly configured (already set up)
3. ⚠️ Check Windows Firewall (see above)
4. ⚠️ Ensure phone and computer are on same Wi-Fi network
5. ⚠️ Verify the IP address is correct

### "Connection Refused" Error

**Solutions**:
1. Ensure server is running: `php artisan serve --host=0.0.0.0 --port=8000`
2. Check XAMPP MySQL is running
3. Verify port 8000 is not already in use

### "Network Unreachable" on Android

**Solutions**:
1. Disable mobile data temporarily (use Wi-Fi only)
2. Ensure both devices are on the same network
3. Try pinging the server from your phone using a network utility app

### "Invalid Host Header" Error

This has been resolved by running with `--host=0.0.0.0`.

## Current Configuration

- ✅ Server runs on `0.0.0.0:8000` (accessible from network)
- ✅ CORS headers configured in `bootstrap/app.php`
- ✅ CORS config in `config/cors.php`
- ✅ Sanctum configured for API authentication
- ⚠️ Firewall must allow port 8000

## Next Steps

1. Find your IP using `ipconfig`
2. Allow port 8000 in Windows Firewall
3. Update Flutter app with your actual IP address
4. Test the connection

## Security Note

⚠️ This configuration is for **development only**. 
- Never use `--host=0.0.0.0` in production
- Always use proper authentication
- Consider VPN for remote access in production


