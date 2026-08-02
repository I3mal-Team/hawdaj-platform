export interface IStory {
  id: number;
  type: string | null;
  file: string | null;
  text: string | null;
  url?: string | null;
  isNew: boolean,
  status: string,
  total_views: number,
  total_likes: number,
  total_comments: number,
  total_shares: number
}
