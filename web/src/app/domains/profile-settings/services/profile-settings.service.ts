import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { inject, Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class ProfileSettingsService {
  private readonly http = inject(HttpClient);
  private readonly baseUrlAll = `${environment.apiUrl}/my-trips`;

  // getAllTrips(page = 1, perPage = 8, keyword?: string) {
  //   let params = new HttpParams()
  //     .set('page', String(page))
  //     .set('per_page', String(perPage));

  //   if (keyword) params = params.set('keyword', keyword);

  //   return this.http.get<IProfileSettingsDetailsResponseDto>(this.baseUrlAll, { params });
  // }
}
