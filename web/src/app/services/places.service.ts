import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { BehaviorSubject, Observable } from 'rxjs';
import { Injectable } from '@angular/core';
import { PublicService } from '../modules/shared/services/public.service';

@Injectable({
  providedIn: 'root'
})
export class PlacesService {
  apiUrl: string = environment?.apiUrl;

  categoriesListSubj = new BehaviorSubject<any>(null);

  constructor(
    private publicService: PublicService,
    private http: HttpClient,
  ) { }

  getPlaces(page?: any, perPage?: any, search?: any, placeName?: any, regionId?: any, cityId?: any, categoryId?: any, subCategoryId?: any, priceId?: any, top_visited?: any, top_featured?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    if (search) {
      params = params.append('search', search);
    }
    if (placeName) {
      params = params.append('search', placeName);
    }
    if (regionId) {
      params = params.append('region_id', regionId);
    }
    if (cityId) {
      params = params.append('city_id', cityId);
    }
    if (categoryId != null) {
      params = params.append('category_id', categoryId);
    }
    if (subCategoryId) {
      params = params.append('sub_category_id', subCategoryId);
    }
    if (priceId) {
      params = params.append('price_id', priceId);
    }
    if (top_visited) {
      params = params.append('top_visited ', top_visited);
    }
    if (top_featured) {
      params = params.append('top_featured', top_featured);
    }
    return this.http.get(`${this.apiUrl}/${roots?.home?.getPlaces}`, { params: params });
  }
  getPLaceById(id: any): Observable<any> {
    // return this.http.get('http://api.talbinah.net/site/blogs');
    return this.http.get(`${this.apiUrl}/${roots?.home?.getPlaces}/${id}`);
  }
  getRelatedPlaces(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.home?.getPlaces}/${id}/related-places`);
  }

  getRegions(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getRegions}`);
  }
  getCities(regionId: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getRegions}/${regionId}/cities`);
  }

  getCategories(page?: any, perPage?: any, search?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    if (search) {
      params = params.append('search', search);
    }
    return this.http.get(`${this.apiUrl}/${roots?.places?.getCategories}`, { params: params });
  }
  getSubCategories(categoryId: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getSubCategories}/${categoryId}`);
  }

  getPrices(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getPrices}`);
  }

  getHeroSliderPlaces(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getHeroSliderPlaces}`);
  }
  getPlacesNames(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getPlacesNames}`);
  }
  getTripSteps(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getTripSteps}`);
  }

  getLanguages(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getLanguages}`);
  }

  sendFeedbackFromPlaces(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.places?.sendFeedbackFromPlaces}`, data);
  }
  getChatGpt(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.places?.getChatGpt}`);
  }
  addChatGpt(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.places?.addChatGpt}`, data);
  }

  savePlace(id: any, type?: any): Observable<any> {
    // let params = new HttpParams();
    // if (id != null) {
    //   params = params.append('id', id);
    // }
    // if (type) {
    //   params = params.append('type', type);
    // }
    let data: any = {
      id: id,
      type: type
    }
    return this.http.post(`${this.apiUrl}/${roots?.home?.getPlaces}/favorite`, data);
  }

  getPricesDescriptions(): any {
    let pricesDescriptions = {
      free: this.publicService.translateTextFromJson('createTrip.freeDescription'),
      low: this.publicService.translateTextFromJson('createTrip.lowDescription'),
      medium: this.publicService.translateTextFromJson('createTrip.medDescription'),
      high: this.publicService.translateTextFromJson('createTrip.highDescription')
    }
    return pricesDescriptions;
  }
}
