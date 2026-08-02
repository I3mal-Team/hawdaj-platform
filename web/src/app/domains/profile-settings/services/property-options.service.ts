import { HttpClient } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import {
  IPricesResponseDto,
  ICitiesResponseDto,
  IRegionsResponseDto,
  ICategoriesResponseDto,
  IFoodCategoriesResponseDto
} from '../dtos';
import { ProfileSettingsManagementCollections } from '../collections/profile-settings.collections';

@Injectable({ providedIn: 'root' })
export class PropertyOptionsService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = environment.apiUrl;

  getPrices() {
    return this.http.get<IPricesResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.Prices}`
    );
  }

  getCities() {
    return this.http.get<ICitiesResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.Cities}`
    );
  }

  getRegions() {
    return this.http.get<IRegionsResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.Regions}`
    );
  }

  getCategories() {
    return this.http.get<ICategoriesResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.Categories}`
    );
  }

  getFoodCategories() {
    return this.http.get<IFoodCategoriesResponseDto>(
      `${this.baseUrl}/${ProfileSettingsManagementCollections.FoodCategories}`
    );
  }
}










