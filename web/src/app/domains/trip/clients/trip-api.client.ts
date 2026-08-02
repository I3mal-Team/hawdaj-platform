// import { HttpClient } from '@angular/common/http';
// import { Injectable } from '@angular/core';
// import { Observable } from 'rxjs';
// import {
//   IQuickAccessCardsRequestDto,
//   IQuickAccessCardsResponseDto,
//   IPodcastStoriesRequestDto,
//   IPodcastStoriesResponseDto,
//   IHomeContentResponseDto
// } from '../dtos';
// import { TripManagementCollections } from '../collections';
// import { ITripApiClient } from './i-main-page-api.client';
// import { CollectionApiClient } from '../../../common';

// @Injectable({ providedIn: 'root' })
// export class TripApiClient implements ITripApiClient {
//   private readonly collectionApiClient: CollectionApiClient;

//   constructor(private readonly http: HttpClient) {
//     this.collectionApiClient = CollectionApiClient.create(
//       TripManagementCollections.Home,
//       this.http
//     );
//   }

//   getQuickAccessCards(): Observable<IQuickAccessCardsResponseDto> {
//     return this.collectionApiClient.get({
//       collectionName: TripManagementCollections.QuickAccessCards()
//     });
//   }

//   getPodcastStories(): Observable<IPodcastStoriesResponseDto> {
//     return this.collectionApiClient.get({
//       collectionName: TripManagementCollections.PodcastStories()
//     });
//   }


//   queryParams = { page: 2, size: 10, sort: 'desc' };
//   body = { name: 'Podcast 1' };
//   id = 'abc123';

//   getHomeContent(params?: any): Observable<IHomeContentResponseDto> {
//     return this.collectionApiClient.get({
//       collectionName: TripManagementCollections.Home,
//       requestOptions: {
//         params: {
//           ...params
//         }
//       }
//     });
//   }
// }

// // GET (with paginationParameters)
// // this.collectionApiClient.get({
// //   collectionName: 'podcasts',
// //   paginationParameters: queryParams
// // });

// // POST (with query params + body)

// // this.collectionApiClient.post({
// //   collectionName: 'podcasts',
// //   body,
// //   paginationParameters: queryParams
// // });

// // PUT
// // this.collectionApiClient.put({
// //   collectionName: 'podcasts',
// //   id,
// //   body,
// //   paginationParameters: queryParams
// // });

// // DELETE
// // this.collectionApiClient.delete({
// //   collectionName: 'podcasts',
// //   id,
// //   paginationParameters: queryParams
// // });
