export interface ProfileBannerImage {
  src: string;
  alt?: string | null;
}

export interface ProfileBannerCounts {
  followers: number;
  following: number;
  points: number;
}

export interface ProfileBannerData {
  id?: string | number | null;
  name: string;
  email?: string | null;
  avatar: ProfileBannerImage;
  cover: ProfileBannerImage;
  counts: ProfileBannerCounts;
  editable?: boolean;
}
