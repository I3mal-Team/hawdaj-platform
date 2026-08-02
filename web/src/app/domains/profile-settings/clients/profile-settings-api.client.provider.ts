// import { ProfileSettingsInMemoryApiClient } from './main-page-api.inmemory.client';
// import { IProfileSettingsApiClient } from './i-main-page-api.client';
// import { ProfileSettingsApiClient } from './main-page-api.client';
// import { ApiClientProvider } from '../../../common';
// import { Injectable } from '@angular/core';

// @Injectable({ providedIn: 'root' })
// export class ProfileSettingsApiClientProvider extends ApiClientProvider<IProfileSettingsApiClient> {
//   constructor(
//     private client: ProfileSettingsApiClient,
//     private inMemoryClient: ProfileSettingsInMemoryApiClient
//   ) {
//     super();
//   }

//   protected override getApiClient(): IProfileSettingsApiClient {
//     return this.client;
//   }

//   protected override getInMemoryClient(): IProfileSettingsApiClient {
//     return this.inMemoryClient;
//   }
// }
