import { IMyPropertyDetailsResponseDto, IMyPropertiesResponseDto } from '../dtos';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { inject, Injectable } from '@angular/core';
import { ProfileSettingsManagementCollections } from '../collections/profile-settings.collections';

@Injectable({ providedIn: 'root' })
export class MyPropertiesService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = environment.apiUrl;

  getAllMyProperties(page = 1, perPage = 10, keyword?: string) {
    let params = new HttpParams()
      .set('page', String(page))
      .set('per_page', String(perPage));

    if (keyword) params = params.set('keyword', keyword);

    return this.http.get<IMyPropertiesResponseDto>(`${this.baseUrl}/${ProfileSettingsManagementCollections.MyProperties}`, { params });
  }

  deleteMyProperty(id?: number) {
    if (!id) throw new Error('Property id is required');
    return this.http.delete(`${this.baseUrl}/${ProfileSettingsManagementCollections.MyProperties}/${id}`);
  }

  /** Get Property by id */
  getPropertyById(id: number) {
    return this.http.get<IMyPropertyDetailsResponseDto>(`${this.baseUrl}/${ProfileSettingsManagementCollections.MyProperties}/${id}`);
  }
}
