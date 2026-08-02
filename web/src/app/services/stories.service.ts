import { environment } from '../../environments/environment';
import { HttpClient, HttpParams } from '@angular/common/http';
import { roots } from '../modules/shared/configs/endPoints';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class StoriesService {
  apiUrl: string = environment?.apiUrl;

  constructor(
    private http: HttpClient
  ) { }

  getRecentStories(page?: any, perPage?: any, top_featured?: any, search?: any): Observable<any> {
    let params = new HttpParams();
    if (page != null) {
      params = params.append('page', page);
    }
    if (perPage) {
      params = params.append('per_page', perPage);
    }
    if (top_featured) {
      params = params.append('top_featured', top_featured);
    }
    if (search) {
      params = params.append('search', search);
    }
    return this.http.get(`${this.apiUrl}/${roots?.stories?.getStories}`, { params: params });
  }
  getFeaturedStories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stories?.getStories}/featured`);
  }
  getCategories(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stories?.getCategories}`);
  }

  getStoryById(id: any): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stories?.getStories}/${id}`);
  }
  getReviews(): Observable<any> {
    return this.http.get(`${this.apiUrl}/${roots?.stories?.getReviews}`);
  }
}
