import { ITabItem, ITabListConfig } from 'src/app/shared/components/tab-list';

/**
 * Travel Type Filter Tabs Configuration
 * Used in trips list component to filter trips by travel type
 */
export const TRAVEL_TYPE_TABS: ITabItem[] = [
  {
    id: 'all',
    label: 'general.all',
    icon: '',
    active: true,
    data: { type: 'all' }
  },
  {
    id: 'air',
    label: 'createTrip.airTravel',
    icon: 'plane-icon',
    active: false,
    data: { type: 'air' }
  },
  {
    id: 'land',
    label: 'createTrip.landTravel',
    icon: 'car-icon',
    active: false,
    data: { type: 'land' }
  }
];

/**
 * Tab List Component Configuration
 * Controls the appearance and behavior of the tab list
 */
export const TAB_LIST_CONFIG: ITabListConfig = {
  isMultiple: false,
  showIcons: true,
  customClass: ''
};

