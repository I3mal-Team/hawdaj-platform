import { IProfileSettingsApiClient } from './i-profile-settings-api.client';
import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class ProfileSettingsInMemoryApiClient implements IProfileSettingsApiClient {

  // getHomeContent(): Observable<IHomeContentResponseDto> {
  //   return of(mockHomeData); // ← New combined method
  // }
}
