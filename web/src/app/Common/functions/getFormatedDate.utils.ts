export function getFormattedDate(dateString: string | null, currentLanguage: string, index?: number): string {
    if (!dateString) return '';

    let date = new Date(dateString);
    if (index) {
        date.setDate(date.getDate() + index);
    }

    let locale: string;
    switch (currentLanguage) {
        case 'ar':
            locale = 'ar-EG';
            break;
        case 'zh':
            locale = 'zh-CN';
            break;
        case 'ru':
            locale = 'ru-RU';
            break;
        default:
            locale = 'en-US';
    }

    let formattedDate = date.toLocaleDateString(locale, { day: 'numeric', month: 'short', year: 'numeric' });

    return formattedDate.replace(/\s/g, ' '); // Ensure proper spacing
}