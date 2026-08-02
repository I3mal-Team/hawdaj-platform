import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { roots } from 'src/app/modules/shared/configs/endPoints';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class StoresService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getStoresStats(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getStores}/stats`);
  }

  getStoresCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getStoresCategories}`);
  }

  getFeatureStores(page?: any, perPage?: any, search?: any, regionId?: any, cityId?: any, category_id?: any, top_visited?: any, top_featured?: any, is_online?: any): Observable<any> {
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
    if (regionId) {
      params = params.append('region_id', regionId);
    }
    if (cityId) {
      params = params.append('city_id', cityId);
    }
    if (category_id != null) {
      params = params.append('category_id', category_id);
    }
    if (top_visited) {
      params = params.append('top_visited ', top_visited);
    }
    if (top_featured) {
      params = params.append('top_featured', top_featured);
    }
    if (is_online != null) {
      params = params.append('is_online', is_online);
    }
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getStores}`, { params: params });
  }

  getTopStores(page?: any, perPage?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    params = params.append('top_stores', true);
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getTopStores}`, { params: params });
  }

  getStoreById(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getStoreById}/${id}`);
  }
  getRelatedStores(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getStores}/${id}/related-stores`);
  }
  sendFeedbackFromStore(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.stores?.sendFeedbackFromStore}`, data);
  }
  getChatGpt(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stores?.getChatGpt}`);
  }
  addChatGpt(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.stores?.addChatGpt}`, data);
  }

  saveStore(id: any, type?: any): Observable<any> {
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
    return this.http.post(`${this.apiUrl}/${roots?.stores?.getStores}/favorite`, data);
  }
}
