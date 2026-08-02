export const HOME_DOWNLOAD_APPS_CONFIG = {
  ariaLabel: {
    ar: 'قسم تحميل تطبيق هودج',
    en: 'Hodj app download section',
    zh: 'Isigaba sokulanda uhlelo lokusebenza lwe-Hodj',
    ru: 'Раздел загрузки приложения Hodj'
  },

  title: {
    ar: 'حمّل تطبيق هودج… واكتشف السعودية بطريقة مختلفة',
    en: 'Download Hodj app and explore your journey with ease',
    zh: 'Landa uhlelo lokusebenza lwe-Hodj ujabulele uhambo lwakho',
    ru: 'Загрузите приложение Hodj и откройте путешествие'
  },

  description: {
    ar: 'كل تجربة، كل قصة، وكل لحظة من رحلتك تبدأ بخطوة… ومع تطبيق هودج، الرحلة أوضح وأسهل. تابع فعالياتك، خطّط مسارك، استكشف الأماكن، وخلّي كل تفاصيلك معاك في جيبك.',
    en: 'Every experience, every story, and every moment begins with a step. With Hodj, your journey is clearer and easier.',
    zh: 'Yonke into iqala ngesinyathelo. Ngohlelo lwe-Hodj, uhambo lwakho luba lula.',
    ru: 'Каждое путешествие начинается с шага. С Hodj всё становится проще.'
  },

  images: {
    fallback: 'assets/images/logo/logo.png'
  },

  stores: {
    googlePlay: {
      href: 'https://play.google.com/store/apps/details?id=com.hawdaj',
      image: 'google-play-badge.png',
      alt: {
        ar: 'تحميل تطبيق هودج من Google Play',
        en: 'Download Hodj app from Google Play',
        zh: 'Landa uhlelo lwe-Hodj ku-Google Play',
        ru: 'Скачать приложение Hodj из Google Play'
      },
      aria: {
        ar: 'تحميل من Google Play',
        en: 'Download from Google Play',
        zh: 'Landa ku-Google Play',
        ru: 'Скачать из Google Play'
      }
    },

    appStore: {
      href: 'https://apps.apple.com/eg/app/hawdaj/id6752390026',
      image: 'app-store-badge.png',
      alt: {
        ar: 'تحميل تطبيق هودج من App Store',
        en: 'Download Hodj app from App Store',
        zh: 'Landa uhlelo lwe-Hodj ku-App Store',
        ru: 'Скачать приложение Hodj из App Store'
      },
      aria: {
        ar: 'تحميل من App Store',
        en: 'Download from App Store',
        zh: 'Landa ku-App Store',
        ru: 'Скачать из App Store'
      }
    }
  },

  floatingImage: {
    image: 'home-download-apps-postioned-image.png',
    alt: {
      ar: 'واجهة تطبيق هودج',
      en: 'Hodj app interface',
      zh: 'Isikhombimsebenzisi sohlelo lwe-Hodj',
      ru: 'Интерфейс приложения Hodj'
    }
  }
} as const;
