import { Injectable, signal, inject } from '@angular/core';
import { PropertyOptionsService } from '../services';
import {
    IPriceOption,
    ICityOption,
    IRegionOption,
    ICategoryOption,
    IFoodCategoryOption
} from '../interfaces';
import {
    IPricesResponseDto,
    ICitiesResponseDto,
    IRegionsResponseDto,
    ICategoriesResponseDto,
    IFoodCategoriesResponseDto
} from '../dtos';
import { AlertsService } from 'src/app/services/alerts.service';

@Injectable({ providedIn: 'root' })
export class PropertyOptionsFacade {
    private readonly propertyOptionsService = new PropertyOptionsService();
    private readonly alertsService = inject(AlertsService);

    // ------------------ Prices Signals ------------------
    readonly prices = signal<IPriceOption[]>([]);
    readonly isLoadingPrices = signal(false);
    readonly pricesErrorMessage = signal<string | null>(null);

    // ------------------ Cities Signals ------------------
    readonly cities = signal<ICityOption[]>([]);
    readonly isLoadingCities = signal(false);
    readonly citiesErrorMessage = signal<string | null>(null);

    // ------------------ Regions Signals ------------------
    readonly regions = signal<IRegionOption[]>([]);
    readonly isLoadingRegions = signal(false);
    readonly regionsErrorMessage = signal<string | null>(null);

    // ------------------ Categories Signals ------------------
    readonly categories = signal<ICategoryOption[]>([]);
    readonly isLoadingCategories = signal(false);
    readonly categoriesErrorMessage = signal<string | null>(null);

    // ------------------ Food Categories Signals ------------------
    readonly foodCategories = signal<IFoodCategoryOption[]>([]);
    readonly isLoadingFoodCategories = signal(false);
    readonly foodCategoriesErrorMessage = signal<string | null>(null);

    // ------------------ Load Prices ------------------
    loadPrices() {
        if (this.prices().length > 0) {
            return; // Already loaded
        }

        this.isLoadingPrices.set(true);
        this.pricesErrorMessage.set(null);

        this.propertyOptionsService.getPrices().subscribe({
            next: (res: IPricesResponseDto) => {
                if (res.code === 200) {
                    this.prices.set(res.data);
                } else {
                    const errorMsg = res.message || 'Error loading prices';
                    this.pricesErrorMessage.set(errorMsg);
                    this.alertsService.openToast('error', errorMsg);
                }
                this.isLoadingPrices.set(false);
            },
            error: (err) => {
                const errorMsg = err?.message || 'Error loading prices';
                this.pricesErrorMessage.set(errorMsg);
                this.alertsService.openToast('error', errorMsg);
                this.isLoadingPrices.set(false);
            }
        });
    }

    // ------------------ Load Cities ------------------
    loadCities() {
        if (this.cities().length > 0) {
            return; // Already loaded
        }

        this.isLoadingCities.set(true);
        this.citiesErrorMessage.set(null);

        this.propertyOptionsService.getCities().subscribe({
            next: (res: ICitiesResponseDto) => {
                if (res.code === 200) {
                    this.cities.set(res.data);
                } else {
                    const errorMsg = res.message || 'Error loading cities';
                    this.citiesErrorMessage.set(errorMsg);
                    this.alertsService.openToast('error', errorMsg);
                }
                this.isLoadingCities.set(false);
            },
            error: (err) => {
                const errorMsg = err?.message || 'Error loading cities';
                this.citiesErrorMessage.set(errorMsg);
                this.alertsService.openToast('error', errorMsg);
                this.isLoadingCities.set(false);
            }
        });
    }

    // ------------------ Load Regions ------------------
    loadRegions() {
        if (this.regions().length > 0) {
            return; // Already loaded
        }

        this.isLoadingRegions.set(true);
        this.regionsErrorMessage.set(null);

        this.propertyOptionsService.getRegions().subscribe({
            next: (res: IRegionsResponseDto) => {
                if (res.code === 200) {
                    this.regions.set(res.data);
                } else {
                    const errorMsg = res.message || 'Error loading regions';
                    this.regionsErrorMessage.set(errorMsg);
                    this.alertsService.openToast('error', errorMsg);
                }
                this.isLoadingRegions.set(false);
            },
            error: (err) => {
                const errorMsg = err?.message || 'Error loading regions';
                this.regionsErrorMessage.set(errorMsg);
                this.alertsService.openToast('error', errorMsg);
                this.isLoadingRegions.set(false);
            }
        });
    }

    // ------------------ Load Categories ------------------
    loadCategories() {
        if (this.categories().length > 0) {
            return; // Already loaded
        }

        this.isLoadingCategories.set(true);
        this.categoriesErrorMessage.set(null);

        this.propertyOptionsService.getCategories().subscribe({
            next: (res: ICategoriesResponseDto) => {
                if (res.code === 200) {
                    this.categories.set(res.data);
                } else {
                    const errorMsg = res.message || 'Error loading categories';
                    this.categoriesErrorMessage.set(errorMsg);
                    this.alertsService.openToast('error', errorMsg);
                }
                this.isLoadingCategories.set(false);
            },
            error: (err) => {
                const errorMsg = err?.message || 'Error loading categories';
                this.categoriesErrorMessage.set(errorMsg);
                this.alertsService.openToast('error', errorMsg);
                this.isLoadingCategories.set(false);
            }
        });
    }

    // ------------------ Load Food Categories ------------------
    loadFoodCategories() {
        if (this.foodCategories().length > 0) {
            return; // Already loaded
        }

        this.isLoadingFoodCategories.set(true);
        this.foodCategoriesErrorMessage.set(null);

        this.propertyOptionsService.getFoodCategories().subscribe({
            next: (res: IFoodCategoriesResponseDto) => {
                if (res.code === 200) {
                    this.foodCategories.set(res.data);
                } else {
                    const errorMsg = res.message || 'Error loading food categories';
                    this.foodCategoriesErrorMessage.set(errorMsg);
                    this.alertsService.openToast('error', errorMsg);
                }
                this.isLoadingFoodCategories.set(false);
            },
            error: (err) => {
                const errorMsg = err?.message || 'Error loading food categories';
                this.foodCategoriesErrorMessage.set(errorMsg);
                this.alertsService.openToast('error', errorMsg);
                this.isLoadingFoodCategories.set(false);
            }
        });
    }
}

