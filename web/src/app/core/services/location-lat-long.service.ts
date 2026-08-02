import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

export interface IUpdateLocationRequestDto {
  lat: number;
  lng: number;
}

@Injectable({
  providedIn: 'root',
})
export class LocationLatLongService {
  private http = inject(HttpClient);
  private readonly apiUrl = `${environment.apiUrl}/update-location`;

  updateLocation(data: IUpdateLocationRequestDto): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }
}
