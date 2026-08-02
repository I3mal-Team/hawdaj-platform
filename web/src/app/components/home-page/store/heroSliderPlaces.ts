import { keys } from '../../../modules/shared/configs/localstorage-key';

// Define a function to get the current language, with a fallback to 'en'
function getCurrentLanguage(): string {
  if (typeof window !== 'undefined') {
    return window.localStorage.getItem(keys.language) || 'ar';
  }
  return 'ar'; // Fallback language
}

export const heroPlacesResponsiveOptions: any = [
  {
    center: true,
    breakpoint: '1199px',
    numVisible: 2,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '991px',
    numVisible: 2,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '767px',
    numVisible: 2,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '567px',
    numVisible: 1,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '420px',
    numVisible: 1,
    numScroll: 1
  }
];

export const heroPlacesResponsiveOptionsGallaries: any = [
  {
    center: true,
    breakpoint: '1199px',
    numVisible: 3,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '991px',
    numVisible: 3,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '767px',
    numVisible: 3,
    numScroll: 1
  },
  {
    center: true,
    breakpoint: '420px',
    numVisible: 3,
    numScroll: 1
  }
];

export const eventsSlideConfig: any = {
  "slidesToShow": 2.5, "slidesToScroll": 1, "autoplay": false, navigator: true,
  prevArrow: '',
  nextArrow: '',
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2.5,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 1.5,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 2.5,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
      }
    }
  ]
};
export const saudiOpeningSlideConfig: any = {
  "slidesToShow": 1, "slidesToScroll": 1, "autoplay": true, navigator: true,
  prevArrow: '',
  nextArrow: '',
};
export const mapResultDataConfig: any = {
  // "slidesToShow": 4, "slidesToScroll": 4, "autoplay": false, navigator: true,
  // prevArrow: '',
  // nextArrow: '',
  // responsive: [
  //   {
  //     breakpoint: 1024,
  //     settings: {
  //       slidesToShow: 3,
  //       slidesToScroll: 1,
  //       arrows: true,
  //     }
  //   },
  //   {
  //     breakpoint: 767,
  //     settings: {
  //       slidesToShow: 2,
  //       slidesToScroll: 1,
  //       arrows: true,
  //     }
  //   },
  //   {
  //     breakpoint: 480,
  //     settings: {
  //       slidesToShow: 1,
  //       slidesToScroll: 1,
  //       arrows: true,
  //     }
  //   }
  // ]
  slidesPerView: 4,
  navigation: false,
  pagination: { clickable: true },
  scrollbar: { draggable: true },
  // autoplay: {
  //   delay: 5000,
  //   disableOnInteraction: false,
  // },
  breakpoints: {
    280: {
      slidesPerView: 1,
      spaceBetween: 12,
    },
    320: {
      slidesPerView: 1.5,
      spaceBetween: 12,
    },
    400: {
      slidesPerView: 1.5,
      spaceBetween: 12,
    },
    468: {
      slidesPerView: 3,
      spaceBetween: 12,
    },
    768: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
    992: {
      slidesPerView: 4,
      spaceBetween: 30,
    },
    1200: {
      slidesPerView: 4,
      spaceBetween: 30,
    },
  }

};

let currentLanguage = getCurrentLanguage();
export const restaurantSlideConfig: any = {
  "slidesToShow": 3, "slidesToScroll": 1, "autoplay": false, navigator: false, spaceBetween: 20,
  prevArrow: '',
  nextArrow: '',
  responsive: [
    // {
    //   breakpoint: 1300,
    //   settings: {
    //     slidesToShow: 3,
    //     slidesToScroll: 1,
    //     arrows: true,
    //   }
    // },
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 766,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
      }
    }
  ],
  rtl: currentLanguage == 'ar' ? true : false
};
