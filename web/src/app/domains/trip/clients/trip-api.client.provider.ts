// import { TripInMemoryApiClient } from './main-page-api.inmemory.client';
// import { ITripApiClient } from './i-main-page-api.client';
// import { TripApiClient } from './main-page-api.client';
// import { ApiClientProvider } from '../../../common';
// import { Injectable } from '@angular/core';

// @Injectable({ providedIn: 'root' })
// export class TripApiClientProvider extends ApiClientProvider<ITripApiClient> {
//   constructor(
//     private client: TripApiClient,
//     private inMemoryClient: TripInMemoryApiClient
//   ) {
//     super();
//   }

//   protected override getApiClient(): ITripApiClient {
//     return this.client;
//   }

//   protected override getInMemoryClient(): ITripApiClient {
//     return this.inMemoryClient;
//   }
// }
