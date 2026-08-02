/* ---------- Tab Item Interface ---------- */
export interface ITabItem {
  /** Unique identifier for the tab */
  id: string;

  /** Display label for the tab */
  label: string;

  /** Icon name to display with the tab */
  icon?: string;

  /** Whether this tab is currently active */
  active: boolean;

  /** Whether this tab is disabled */
  disabled?: boolean;

  /** Badge count to display on the tab */
  badge?: number;

  /** Custom data to pass when tab is clicked */
  data?: any;
}

/* ---------- Tab List Config Interface ---------- */
export interface ITabListConfig {
  /** Whether multiple tabs can be selected at once */
  isMultiple?: boolean;

  /** Whether to show icons on tabs */
  showIcons?: boolean;

  /** Custom CSS classes to apply */
  customClass?: string;
}
