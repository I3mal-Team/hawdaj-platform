import { ProfileSettingsRoutesEnum } from "./profile-settings-routes.enum";
import { ITab } from "../interfaces";

export const profileTabsItems: ITab[] = [
  {
    id: 1,
    title: 'Personal Info',
    icon: 'icon-personal-info',
    route: ProfileSettingsRoutesEnum.PERSONAL_INFO,
    translate: {
      en: 'Personal Info',
      ar: 'معلومات الشخصية',
      zh: '个人信息',
      ru: 'Личная информация',
    },
  },
  {
    id: 2,
    title: 'Posts',
    icon: 'icon-posts',
    route: ProfileSettingsRoutesEnum.POSTS,
    translate: {
      en: 'Posts',
      ar: 'المنشورات',
      zh: '帖子',
      ru: 'Публикации',
    },
  },
  {
    id: 3,
    title: 'Tourist Guide Info',
    icon: 'icon-tour-guide',
    route: ProfileSettingsRoutesEnum.TOURIST_GUIDE_INFO,
    translate: {
      en: 'Tourist Guide Info',
      ar: 'معلومات المرشد السياحي',
      zh: '导游信息',
      ru: 'Информация о туроператоре',
    },
  },
  {
    id: 4,
    title: 'Landmarks',
    icon: 'icon-landmarks',
    route: ProfileSettingsRoutesEnum.LANDMARKS,
    translate: {
      en: 'Landmarks',
      ar: 'المعالم',
      zh: '地标',
      ru: 'Достопримечательности',
    },
  },
  {
    id: 5,
    title: 'My Trips',
    icon: 'icon-journeys',
    route: ProfileSettingsRoutesEnum.MY_TRIPS,
    translate: {
      en: 'My Trips',
      ar: 'رحلاتي',
      zh: '我的旅程',
      ru: 'Мои поездки',
    },
  },
  {
    id: 6,
    title: 'My Favourites',
    icon: 'icon-favourites',
    route: ProfileSettingsRoutesEnum.MY_FAVOURITES,
    translate: {
      en: 'My Favourites',
      ar: 'المفضلة',
      zh: '收藏夹',
      ru: 'Избранное',
    },
  },
  {
    id: 7,
    title: 'My Properties',
    icon: 'icon-properties',
    route: ProfileSettingsRoutesEnum.MY_PROPERTIES,
    translate: {
      en: 'My Properties',
      ar: 'ممتلكاتي',
      zh: '我的房产',
      ru: 'Мои объекты',
    },
  },
];
