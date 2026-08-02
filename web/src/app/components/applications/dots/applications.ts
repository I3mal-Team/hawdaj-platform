export interface AppItem {
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

/* ---------- Logged User Data ---------- */
export interface ILoggedUserData {
  id: number;
  first_name: string;
  last_name: string;
  full_name: string;
  email: string;
  phone?: string | null;
  photo?: string | null;
  gender?: 'male' | 'female' | string | null;
  created_at: string;
  updated_at: string;
  deleted_at?: string | null;
  provider_id?: string | null;
  provider_type?: string | null;
  api_token?: string | null;
  forget_password_code?: string | null;
  total_points?: number;
  region_id?: number | null;
  token: string;
}
