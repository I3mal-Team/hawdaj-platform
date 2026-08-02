# Force Update Feature - Setup Guide

## Overview
This feature checks for app updates from Firebase Firestore and displays a dialog to users when an update is available.

## Firebase Firestore Structure

You need to create a collection in Firebase Firestore with the following structure:

### Collection: `app_versions`
### Document: `latest_version`

```json
{
  "version": "4.3.0+9",
  "isMandatory": true
}
```

### Fields:
- **version** (String): The latest app version in the format `MAJOR.MINOR.PATCH+BUILD_NUMBER`
  - Example: `"4.3.0+9"` means version 4.3.0 with build number 9
  
- **isMandatory** (Boolean): 
  - `true`: Forces users to update (dialog cannot be dismissed)
  - `false`: Optional update (users can dismiss and choose "remember my choice")

## App Store Links Configuration

Update the store links in `lib/features/force_update/constants/app_store_links.dart`:

```dart
class AppStoreLinks {
  // Replace with your actual Google Play Store URL
  static const String googlePlayStoreUrl = 'https://play.google.com/store/apps/details?id=YOUR_PACKAGE_NAME';
  
  // Replace with your actual Apple App Store URL
  static const String appleAppStoreUrl = 'https://apps.apple.com/app/idYOUR_APP_ID';
}
```

## How It Works

1. **On App Start**: The app checks Firebase for the latest version
2. **Version Comparison**: Compares current version with Firebase version
3. **Dialog Display**:
   - **Mandatory Update** (`isMandatory: true`): Non-dismissable dialog, no "remember choice" option
   - **Optional Update** (`isMandatory: false`): Dismissable dialog with "remember my choice" checkbox

4. **Remember Choice**: If user checks "remember my choice" and dismisses the dialog, they won't see it again for that specific version

## Testing

1. Set your Firebase `app_versions/latest_version` document
2. Change the `isMandatory` field to test both scenarios
3. Increment the version number to test update detection
4. Clear app data to reset "remembered choices"

## Example Scenarios

### Scenario 1: Critical Update Required
```json
{
  "version": "5.0.0+10",
  "isMandatory": true
}
```
- All users on version < 5.0.0 will see a non-dismissable update dialog
- Users must update to continue using the app

### Scenario 2: Optional Feature Update
```json
{
  "version": "4.5.0+9",
  "isMandatory": false
}
```
- Users on version < 4.5.0 will see a dismissable update dialog
- Users can choose "remember my choice" to not see it again for version 4.5.0
- If a newer version (e.g., 4.6.0) is released later, they will see the dialog again

## Notes

- The version comparison only considers MAJOR.MINOR.PATCH (ignores build number)
- "Remember choice" is stored locally using SharedPreferences
- Clearing app data will reset all remembered choices
- Make sure your Firebase rules allow read access to the `app_versions` collection

