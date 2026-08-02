import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class HomeService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getHomeData(): Observable<any> {
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.home}`
    );
  }

  getEvents(
    page: any,
    perPage: any,
    search?: any
  ): Observable<any> {
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
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getEvents}`,
      { params: params }
    );
  }
  getTopDestinations(
    page: any,
    perPage: any,
    search?: any
  ): Observable<any> {
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
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getTopDestinations}`,
      { params: params }
    );
  }
  getTravelers(
    page: any,
    perPage: any,
    search?: any
  ): Observable<any> {
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
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getTravelers}`,
      { params: params }
    );
  }
  getSaudiOpening(
    page: any,
    perPage: any,
    search?: any
  ): Observable<any> {
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
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getSaudiOpening}`,
      { params: params }
    );
  }
  getSaudiPlaces(
    page: any,
    perPage: any,
    search?: any
  ): Observable<any> {
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
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getSaudiPlaces}`,
      { params: params }
    );
  }
  getServices(): Observable<any> {
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getServices}`,
    );
  }
  subscribe(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/${roots?.home?.subscribe}`, data);
  }
  leaveInquiry(data: any): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/${roots?.home?.leaveInquiry}`,
      data
    );
  }

  getPlacesDataForMap(page?: any, perPage?: any, search?: any): Observable<any> {
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
    return this.http.get(`${this.apiUrl}/${roots?.home?.placesDataMap}`, { params: params });
  }

  getGlobalMapData(): Observable<any> {
    return this.http.get(
      `${this.apiUrl}/${roots?.home?.getGlobalMapData}`,
    );
  }
  getGlobalSearch(page?: any, perPage?: any, search?: any, region_id?: any, city_id?: any, viewAs?: any): Observable<any> {
    let params = new HttpParams();

    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage != null) {
      params = params.append('per_page', perPage);
    }
    if (search != null) {
      params = params.append('search', search);
    }
    if (region_id != null) {
      params = params.append('region_id', region_id);
    }
    if (city_id != null) {
      params = params.append('city_id', city_id);
    }
    if (viewAs != null) {
      params = params.append('viewAs', viewAs); //events , places , zads , stores
    }
    return this.http.get(`${this.apiUrl}/${roots?.home?.getGlobalSearch}`, { params: params });
  }

  addToFavorite(
    id?: any,
    type?: any
  ): Observable<any> {
    let data: any = {
      favoritable_id: id,
      favoritable_type: type
    }
    return this.http.post(`${this.apiUrl}/${roots?.home?.favorites}`, data);
  }
  saveLocation(id: any, type?: any): Observable<any> {
    let data: any = {
      favoritable_id: id,
      favoritable_type: type
    }
    return this.http.post(`${this.apiUrl}/${roots?.home?.favorites}`, data);
    // if (type == 'place') {
    //   return this.http.post(`${this.apiUrl}/${roots?.home?.getPlaces}/favorite`, data);
    // } else if (type == 'store') {
    //   return this.http.post(`${this.apiUrl}/${roots?.stores?.getStores}/favorite`, data);
    // }
    // else if (type == 'zad') {
    //   return this.http.post(`${this.apiUrl}/${roots?.restaurants?.zads}/favorite`, data);
    // }
    // else {
    //   return this.http.post(`${this.apiUrl}/${roots?.events?.getAllEvents}/favorite`, data);
    // }
  }
}
