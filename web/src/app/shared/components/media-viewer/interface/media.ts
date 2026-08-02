export interface IContent {
  galleries: IMedia[];
  image: string;
  title: string;
}
export interface IMedia {
  file: string;
  poster?: string;
  mime_type: string;
  id?: any;
  type?: string;
}
