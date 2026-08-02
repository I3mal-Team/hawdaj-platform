/* ---------- Trip Header Interface ---------- */

/**
 * Configuration interface for Trip Header Component
 * @description Defines the structure for trip header data display
 */
export interface ITripHeaderConfig {
  /** Trip title (e.g., "برنامج رحلة 1") */
  title: string;

  /** Date range (e.g., "25/04/2024 - 25/05/2024") */
  dateRange: string;

  /** Show download PDF button */
  showDownloadPdf?: boolean;

  /** Show map/save button */
  showMapButton?: boolean;

  /** Show save trip button */
  showSaveButton?: boolean;

  /** Custom CSS classes */
  customClass?: string;

  /** RTL layout */
  isRtl?: boolean;
}

/**
 * Event emitters interface for Trip Header Component
 */
export interface ITripHeaderEvents {
  /** Emitted when download PDF button is clicked */
  onDownloadPdf?: () => void;

  /** Emitted when map/save button is clicked */
  onMapClick?: () => void;

  /** Emitted when save trip button is clicked */
  onSaveTrip?: () => void;
}


