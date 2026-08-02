import { Injectable, signal, computed } from '@angular/core';
import { MyPropertiesService } from '../services';
import { IMyPropertyDetailsResponseDto, IPropertyItem, IMyPropertiesResponseDto } from '../dtos';
import { AlertsService } from 'src/app/services/alerts.service';
import { inject } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class PropertiesFacade {
  private readonly _MyPropertiesService = new MyPropertiesService();
  private readonly alertsService = inject(AlertsService);

  // ------------------ Properties List Signals ------------------
  readonly propertiesList = signal<IPropertyItem[]>([]);
  readonly isLoadingPropertiesList = signal(false);
  readonly isDeletingPropertiesFromList = signal(false);
  readonly propertiesListErrorMessage = signal<string | null>(null);
  readonly propertiesListStatusMessage = signal<string | null>(null);
  readonly currentPage = signal(1);
  readonly perPage = signal(10);
  readonly total = signal(0);
  readonly keyword = signal<string | null>(null);
  readonly selectedType = signal<'all' | 'place' | 'store' | 'event' | 'zad'>('place');

  // Internal storage for totals by type
  private totalsByType = new Map<'place' | 'store' | 'event' | 'zad', number>();

  // ------------------ Computed Signals ------------------
  // Helper function to filter properties by search query
  private filterBySearch(items: IPropertyItem[], query: string): IPropertyItem[] {
    if (!query || query.trim() === '') {
      return items;
    }
    const searchTerm = query.toLowerCase().trim();
    return items.filter(item => 
      item.title?.toLowerCase().includes(searchTerm) ||
      item.description?.toLowerCase().includes(searchTerm) ||
      item.address?.toLowerCase().includes(searchTerm) ||
      item.city?.name?.toLowerCase().includes(searchTerm) ||
      item.region?.name?.toLowerCase().includes(searchTerm)
    );
  }

  readonly placesList = computed(() => {
    if (this.selectedType() === 'all' || this.selectedType() === 'place') {
      const places = this.allPropertiesByType.get('place') || [];
      return this.filterBySearch(places, this.keyword() || '');
    }
    return [];
  });

  readonly storesList = computed(() => {
    if (this.selectedType() === 'all' || this.selectedType() === 'store') {
      const stores = this.allPropertiesByType.get('store') || [];
      return this.filterBySearch(stores, this.keyword() || '');
    }
    return [];
  });

  readonly eventsList = computed(() => {
    if (this.selectedType() === 'all' || this.selectedType() === 'event') {
      const events = this.allPropertiesByType.get('event') || [];
      return this.filterBySearch(events, this.keyword() || '');
    }
    return [];
  });

  readonly zadList = computed(() => {
    if (this.selectedType() === 'all' || this.selectedType() === 'zad') {
      const zads = this.allPropertiesByType.get('zad') || [];
      return this.filterBySearch(zads, this.keyword() || '');
    }
    return [];
  });

  readonly hasError = computed(() => !!this.propertiesListErrorMessage());
  
  // Computed total based on selected type
  readonly currentTotal = computed(() => {
    const type = this.selectedType();
    if (type === 'all') {
      return this.total();
    }
    return this.totalsByType.get(type) || 0;
  });

  // Computed hasNoData based on selected type and search
  readonly hasNoData = computed(() => {
    if (this.isLoadingPropertiesList() || this.hasError()) {
      return false;
    }
    
    const type = this.selectedType();
    if (type === 'all') {
      const allFiltered = this.filterBySearch(this.propertiesList(), this.keyword() || '');
      return allFiltered.length === 0;
    }
    
    const currentList = this.allPropertiesByType.get(type) || [];
    const filteredList = this.filterBySearch(currentList, this.keyword() || '');
    return filteredList.length === 0;
  });

  // Internal storage for all properties grouped by type
  private allPropertiesByType = new Map<'place' | 'store' | 'event' | 'zad', IPropertyItem[]>();

  // ------------------ Property Details Signals ------------------
  readonly propertyData = signal<IPropertyItem | null>(null);
  readonly isLoadingPropertyDetails = signal(false);
  readonly isDeletingPropertyDetails = signal(false);
  readonly propertyDetailsErrorMessage = signal<string | null>(null);
  readonly propertyDetailsStatusMessage = signal<string | null>(null);

  // ------------------ Properties List Methods ------------------
  loadProperties(page = 1, perPage = 10, append = false, keyword?: string, type?: 'all' | 'place' | 'store' | 'event' | 'zad') {
    this.isLoadingPropertiesList.set(true);
    this.propertiesListErrorMessage.set(null);
    this.propertiesListStatusMessage.set(null);
    this.currentPage.set(page);
    this.perPage.set(perPage);
    if (keyword !== undefined) {
      this.keyword.set(keyword);
    }
    if (type) {
      this.selectedType.set(type);
    }

    this._MyPropertiesService.getAllMyProperties(page, perPage, keyword).subscribe({
      next: (res: IMyPropertiesResponseDto) => {
        if (res.code === 200) {
          // Combine all properties from all types
          const allProperties: IPropertyItem[] = [];
          
          // Process places
          if (res.data.places?.items) {
            const places = res.data.places.items.map(item => ({
              ...item,
              type: 'place' as const
            }));
            this.allPropertiesByType.set('place', places);
            this.totalsByType.set('place', res.data.places.total || 0);
            allProperties.push(...places);
          } else {
            this.totalsByType.set('place', 0);
          }

          // Process stores
          if (res.data.stores?.items) {
            const stores = res.data.stores.items.map(item => ({
              ...item,
              type: 'store' as const
            }));
            this.allPropertiesByType.set('store', stores);
            this.totalsByType.set('store', res.data.stores.total || 0);
            allProperties.push(...stores);
          } else {
            this.totalsByType.set('store', 0);
          }

          // Process events
          if (res.data.events?.items) {
            const events = res.data.events.items.map(item => ({
              ...item,
              type: 'event' as const,
              date_from: item.date_from || null,
              date_to: item.date_to || null
            }));
            this.allPropertiesByType.set('event', events);
            this.totalsByType.set('event', res.data.events.total || 0);
            allProperties.push(...events);
          } else {
            this.totalsByType.set('event', 0);
          }

          // Process zad_elgadels
          if (res.data.zad_elgadels?.items) {
            const zads = res.data.zad_elgadels.items.map(item => ({
              ...item,
              type: 'zad' as const
            }));
            this.allPropertiesByType.set('zad', zads);
            this.totalsByType.set('zad', res.data.zad_elgadels.total || 0);
            allProperties.push(...zads);
          } else {
            this.totalsByType.set('zad', 0);
          }

          // Calculate total from all types
          const totalCount = (res.data.places?.total || 0) + 
                           (res.data.stores?.total || 0) + 
                           (res.data.events?.total || 0) + 
                           (res.data.zad_elgadels?.total || 0);

          // Update propertiesList with new array reference to trigger computed signals
          if (append) {
            this.propertiesList.update(old => [...old, ...allProperties]);
          } else {
            this.propertiesList.set([...allProperties]);
          }

          this.total.set(totalCount);
          this.propertiesListStatusMessage.set('Properties loaded successfully');
        } else {
          const errorMsg = res.message || 'Error loading properties';
          this.propertiesListErrorMessage.set(errorMsg);
          this.alertsService.openToast('error', errorMsg);
        }
        this.isLoadingPropertiesList.set(false);
      },
      error: (err) => {
        const errorMsg = err?.error?.message || err?.message || 'Error loading properties';
        this.propertiesListErrorMessage.set(errorMsg);
        this.alertsService.openToast('error', errorMsg);
        this.isLoadingPropertiesList.set(false);
      }
    });
  }

  // ------------------ Delete Property Methods ------------------
  deleteProperty(id?: number, onSuccess?: () => void) {
    if (!id) return;
    this.isDeletingPropertiesFromList.set(true);
    this.propertiesListErrorMessage.set(null);
    this.propertiesListStatusMessage.set(null);

    // Find the property to get its type before deletion
    let propertyType: 'place' | 'store' | 'event' | 'zad' | null = null;
    for (const [type, items] of this.allPropertiesByType.entries()) {
      const found = items.find(item => item.id === id);
      if (found) {
        propertyType = type;
        break;
      }
    }

    this._MyPropertiesService.deleteMyProperty(id).subscribe({
      next: (res: any) => {
        if (res?.code === 200) {
          // Remove from local storage
          if (propertyType) {
            const currentList = this.allPropertiesByType.get(propertyType) || [];
            const updatedList = currentList.filter(item => item.id !== id);
            this.allPropertiesByType.set(propertyType, updatedList);
            
            // Update total for this type
            const currentTotal = this.totalsByType.get(propertyType) || 0;
            this.totalsByType.set(propertyType, Math.max(0, currentTotal - 1));
            
            // Update propertiesList
            this.propertiesList.update(list => list.filter(item => item.id !== id));
            
            // Update total
            this.total.update(t => Math.max(0, t - 1));
          }
          
          this.propertiesListStatusMessage.set('Property deleted successfully');
          this.alertsService.openToast('success', res?.message || 'Property deleted successfully');
          onSuccess?.();
        } else {
          const errorMsg = res?.message || 'Error deleting property';
          this.propertiesListErrorMessage.set(errorMsg);
          this.alertsService.openToast('error', errorMsg);
        }
        this.isDeletingPropertiesFromList.set(false);
      },
      error: (err) => {
        const errorMsg = err?.error?.message || err?.message || 'Error deleting property';
        this.propertiesListErrorMessage.set(errorMsg);
        this.alertsService.openToast('error', errorMsg);
        this.isDeletingPropertiesFromList.set(false);
      }
    });
  }

  // ------------------ Saved Property Details Methods ------------------
  loadSavedPropertyById(id: number) {
    if (!id) return;
    this.isLoadingPropertyDetails.set(true);
    this.propertyDetailsErrorMessage.set(null);
    this.propertyDetailsStatusMessage.set(null);

    this._MyPropertiesService.getPropertyById(id).subscribe({
      next: (res: IMyPropertyDetailsResponseDto) => {
        if (res?.code === 200) {
          this.propertyData.set(res.data);
          this.propertyDetailsStatusMessage.set('Property loaded successfully');
        } else {
          this.propertyDetailsErrorMessage.set(res?.message || 'Error loading property');
        }
        this.isLoadingPropertyDetails.set(false);
      },
      error: (err) => {
        this.propertyDetailsErrorMessage.set(err?.error?.message || err?.message || 'Error loading property');
        this.isLoadingPropertyDetails.set(false);
      }
    });
  }
}
