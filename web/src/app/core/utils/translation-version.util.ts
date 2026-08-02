/**
 * Translation Version Utility
 * 
 * Generates a single timestamp at app startup to use as cache-busting version
 * for translation files. This ensures translations are always fresh.
 */
export class TranslationVersionUtil {
  private static readonly VERSION: string = Date.now().toString();

  /**
   * Get the app startup timestamp version
   * This is generated once when the module loads and remains constant
   * throughout the app lifecycle, ensuring consistent cache busting
   */
  static getVersion(): string {
    return this.VERSION;
  }
}

