import { ITripApiClient } from './i-trip-api.client';
import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class TripInMemoryApiClient implements ITripApiClient {

  // getHomeContent(): Observable<IHomeContentResponseDto> {
  //   return of(mockHomeData); // ← New combined method
  // }
}
