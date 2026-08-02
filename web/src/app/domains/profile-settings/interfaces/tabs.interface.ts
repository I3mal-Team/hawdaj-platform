export interface ITab {
  id: number | string;
  title: string;
  icon?: string | null;
  route?: string | null;
  translate: {
    en: string; // English translation
    ar: string; // Arabic translation (from image)
    zh?: string; // Chinese translation (Mandarin Simplified)
    ru?: string; // Russian translation
  };
}
