# Google Sign-In Configuration Fix Guide

## Problem

The Google Sign-In is failing with "Invalid JWT token format" and empty provider_id because the `google-services.json` file is missing OAuth client configuration.

## Root Cause

The `oauth_client` arrays in `google-services.json` are empty, which means:

- Google Sign-In cannot generate proper ID tokens
- The JWT token parsing fails
- The provider_id field becomes empty
- The server returns 422 error: "حقل provider id مطلوب."

## Immediate Fix Applied

I've implemented a robust fallback system in the code that:

1. First tries to extract provider ID from JWT ID token (if available)
2. Falls back to using Google user ID directly from `GoogleSignInAccount.id`
3. As last resort, creates a hash from email address

## Complete Solution Steps

### Step 1: Get App SHA-1 Fingerprint

#### For Debug Build:

```bash
# Windows (using keytool from Java)
keytool -list -v -keystore "%USERPROFILE%\.android\debug.keystore" -alias androiddebugkey -storepass android -keypass android

# Look for SHA1 fingerprint in the output
```

#### For Release Build:

```bash
# Replace with your actual keystore path and details
keytool -list -v -keystore "path\to\your\release.keystore" -alias your-key-alias
```

### Step 2: Configure Google Cloud Console

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Select project: `hawdaj-c131c`
3. Navigate to **APIs & Services** > **Credentials**
4. Click **Create Credentials** > **OAuth 2.0 Client IDs**
5. Choose **Android** as application type
6. Fill in:
   - **Name**: Hawdaj Android App
   - **Package name**: `com.hawdaj` (or your actual package name)
   - **SHA-1 certificate fingerprint**: Paste the SHA-1 from Step 1

### Step 3: Update Firebase Project

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select **hawdaj-c131c** project
3. Click on **Project Settings** (gear icon)
4. Go to **General** tab
5. Under **Your apps** section, find your Android app
6. Click **Add fingerprint** and add the SHA-1 from Step 1
7. Download the new `google-services.json` file

### Step 4: Replace Configuration File

1. Replace `android/app/google-services.json` with the newly downloaded file
2. The new file should contain populated `oauth_client` arrays like:

```json
{
  "oauth_client": [
    {
      "client_id": "730395305822-xxxxxxxxxxxxxxxxx.apps.googleusercontent.com",
      "client_type": 1,
      "android_info": {
        "package_name": "com.hawdaj",
        "certificate_hash": "your_sha1_hash"
      }
    }
  ]
}
```

### Step 5: Clean and Rebuild

```bash
flutter clean
flutter pub get
flutter build apk --debug  # or --release
```

### Step 6: Test

1. Install the new APK on device
2. Try Google Sign-In
3. Check logs for successful provider ID extraction

## Verification

After applying the fix, you should see logs like:

```
I/flutter: Starting Google Sign-In process...
I/flutter: Google user signed in: user@gmail.com
I/flutter: Google user ID: 1234567890
I/flutter: ID Token available: true
I/flutter: Successfully extracted provider ID from JWT: 1234567890
I/flutter: Google login data: {provider_type: google, provider_id: 1234567890, email: user@gmail.com, name: User Name}
```

## Temporary Workaround

The code changes I've made will allow Google Sign-In to work even without proper JWT tokens by using the Google user ID directly. However, for production, it's recommended to have proper OAuth configuration.

## Additional Notes

- Make sure your app's package name matches across Firebase, Google Cloud Console, and your app
- For iOS, you'll need similar configuration in `GoogleService-Info.plist`
- The SHA-1 fingerprint must match the signing certificate used to build your app
- For production, use your release keystore's SHA-1 fingerprint

## Code Changes Made

1. **GoogleAuthModel**: Added `googleUserId` field
2. **SocialAuthService**:
   - Added robust provider ID extraction with fallbacks
   - Enhanced error handling and logging
   - Improved Google Sign-In validation
3. **SocialAuthCubit**: Added better error messages for users

The app should now work with Google Sign-In, but for optimal performance and security, complete the OAuth configuration steps above.
