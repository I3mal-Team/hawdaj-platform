export interface Price {
  id: number;
  name: string | null;
  isSelected: boolean;
  description: string | null
}

export interface Category {
  id: number;
  name: string | null;
  icon: string | null;
  isChecked: boolean
}
