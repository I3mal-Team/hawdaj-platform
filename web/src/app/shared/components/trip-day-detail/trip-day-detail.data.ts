/* ---------- Dummy Data for Trip Day Detail Component ---------- */

import { ITripDayData, IPlaceCard } from './trip-day-detail.interface';

/**
 * Dummy place cards data
 */
export const DUMMY_PLACES: IPlaceCard[] = [
  {
    id: '1',
    slug: 'al-daw-al-khafeet-restaurant',
    title: 'مطعم الضوء الخافت',
    description: 'مطعم لبناني فاخر',
    imageUrl: 'assets/images/places/restaurant-1.jpg',
    type: 'restaurant',
    location: 'الرياض، الرياض',
    rating: 4.0,
    reviewsCount: 23
  },
  {
    id: '2',
    slug: 'faraa-store',
    title: 'متجر فراء',
    description: 'متجر أزياء راقي',
    imageUrl: 'assets/images/places/store-1.jpg',
    type: 'store',
    location: 'الرياض، الرياض',
    rating: 4.0,
    reviewsCount: 23
  },
  {
    id: '3',
    slug: 'takka-restaurant',
    title: 'مطعم تكه',
    description: 'مطعم سعودي أصيل',
    imageUrl: 'assets/images/places/restaurant-2.jpg',
    type: 'restaurant',
    location: 'الرياض، الرياض',
    rating: 4.0,
    reviewsCount: 23
  },
  {
    id: '4',
    slug: 'diriyah',
    title: 'الدرعية',
    description: 'موقع تاريخي',
    imageUrl: 'assets/images/places/diriyah.jpg',
    type: 'place',
    location: 'الرياض، الرياض',
    rating: 4.0,
    reviewsCount: 23
  }
];

/**
 * Dummy trip day data
 */
export const DUMMY_TRIP_DAY: ITripDayData = {
  dayNumber: 1,
  dayTitle: 'يومك الأول',
  date: '12 مارس 2025',
  placesCount: 4,
  regionName: 'مدينة الرياض',
  regionDescription: 'عاصمة الترفيه والتنوّع، ومركز الليالي التي لا تُنسى! هنا بتعيش تجارب استثنائية مع فعاليات عالمية وعروض ثقافية مبهرة، وتكتشف متعة التخييم والكشتات في أجواء شتوية فريدة.',
  morning: {
    type: 'morning',
    title: 'في الصباح',
    description: 'ابدأ يومك بفطور لبناني فاخر في "مطعم الضوء الخافت" واستمتع بالأجواء الأنيقة، ثم قم بجولة مريحة في "ذا زون" بين متاجرها الراقية ومقاهيها المميزة.',
    places: [DUMMY_PLACES[0], DUMMY_PLACES[1]],
    icon: 'sun-icon'
  },
  evening: {
    type: 'evening',
    title: 'في المساء',
    description: 'توجه إلى الدرعية عند غروب الشمس لتستمتع بجمال المكان وتجربة لا تُنسى بين مطاعمها الراقية مثل "تكّة" و"الليمونة"، حيث تمتزج النكهات السعودية الأصيلة بأجواء ساحرة.',
    places: [DUMMY_PLACES[2], DUMMY_PLACES[3]],
    icon: 'moon-icon'
  }
};

/**
 * Multiple days dummy data
 */
export const DUMMY_TRIP_DAYS: ITripDayData[] = [
  DUMMY_TRIP_DAY,
  {
    ...DUMMY_TRIP_DAY,
    dayNumber: 2,
    dayTitle: 'يومك الثاني',
    date: '13 مارس 2025'
  }
];


