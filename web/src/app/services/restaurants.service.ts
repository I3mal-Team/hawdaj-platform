import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RestaurantsService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
  }

  getCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}/categories`);
  }
  getRestaurants(page?: any, perPage?: any, lat?: any, lng?: any, regionId?: any, cityId?: any, categoryId?: any, food_categories?: any, rate?: any, search?: any, nearest?: any): Observable<any> {
    if (isPlatformBrowser(this.platformId)) {
      let params = new HttpParams();
      if (page != null) {
        params = params.append('page', page);
      }
      if (perPage) {
        params = params.append('per_page', perPage);
      }
      if (lat) {
        params = params.append('lat', lat);
      }
      if (lng) {
        params = params.append('lng', lng);
      }
      if (regionId) {
        params = params.append('region_id', regionId);
      }
      if (cityId) {
        params = params.append('city_id', cityId);
      }
      if (categoryId != null) {
        params = params.append('categories', categoryId);
      }
      if (food_categories != null) {
        params = params.append('food_categories[]', food_categories);
      }
      if (rate != null) {
        params = params.append('rate', rate);
      }
      if (search) {
        params = params.append('search', search);
      }
      if (nearest) {
        params = params.append('nearest', nearest);
      }
      return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}`, { params: params });
    }
    return new Observable<any>();
  }

  getRestaurantById(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}/${id}`);
  }
  getRestaurantMenu(id?: any, page?: any, perPage?: any,): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}/${id}/menus`, { params: params });
  }
  getOffers(id?: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}/${id}/offers`);
  }
  favoriteRestaurant(id?: any): Observable<any> {
    let params = new HttpParams();
    if (id != null) {
      params = params.append('id', id);
    }
    return this.http.post(`${this.apiUrl}/${roots?.restaurants?.zads}/favorite`, {}, { params: params });
  }
  getFoodCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.restaurants?.zads}/food-categories`);
  }
}
