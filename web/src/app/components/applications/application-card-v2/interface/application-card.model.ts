export interface IApplication {
    id: number;
    slug: string;
    type: string;
    link: string | null;
    ios_link: string;
    android_link: string;
    category: ICategory;
    description: string;
    image: string;
    title: string;
    active: boolean;
    show_in_home: boolean;
}
export interface ICategory {
    id: number;
    name: string;
}
