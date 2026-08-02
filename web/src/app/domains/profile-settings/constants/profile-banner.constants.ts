import { ProfileBannerData } from "../interfaces";

export const DEFAULT_AVATAR = { src: '/assets/images/default-avatar.png', alt: 'avatar' } as const;
export const DEFAULT_COVER = { src: '/assets/images/default-cover.jpg', alt: 'cover' } as const;

export const STATIC_PROFILE: ProfileBannerData =
  {
    id: 2,
    name: 'إسلام عفيفي',
    email: 'eslam.afify@gmail.com',
    avatar: {
      src: 'https://scontent.fspx1-1.fna.fbcdn.net/v/t39.30808-6/571117363_4324996984380051_8652283842507962856_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=YIMx3MVPwtYQ7kNvwFbQfAh&_nc_oc=AdlBzyPhQlVlt6a4VctMokarKYoYOhiDc2eumu-gYP7A6QbstkyMDOgukY11eIZIdzA&_nc_zt=23&_nc_ht=scontent.fspx1-1.fna&_nc_gid=L4zEjZh3iY1AeodsuRtZAA&oh=00_AfgFaT1ps-Ujc5iUOo06gVCB2NreQzt84PzPo96kAwbEmQ&oe=69114975',
      alt: 'صورة الملف الشخصي لإسلام عفيفي',
    },
    cover: {
      src: 'https://hawdaj.net/new-home-page-panner-web.b54be1e0315bf6ec.webp',
      alt: 'صورة الغلاف لإسلام عفيفي',
    },
    counts: {
      followers: 1280,
      following: 643,
      points: 2400,
    },
    editable: true,
  } as const;
