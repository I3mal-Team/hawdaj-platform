export interface TourGuideItem {
  id: number;
  slug: string;
  type: string;
  link: string | null;
  ios_link: string;
  android_link: string;
  image: string;
  title: string;
  description: string;
  active: number;
}
