export function getFormattedIndex(value: number, currentLanguage: string): string {
    const locale = currentLanguage === 'ar' ? 'ar-EG' : 'en-US';
    return new Intl.NumberFormat(locale).format(value);
}
