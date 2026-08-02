import { Injectable } from '@angular/core';
import {
  HttpEvent,
  HttpInterceptor,
  HttpHandler,
  HttpRequest,
  HttpResponse
} from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { tap, shareReplay } from 'rxjs/operators';

@Injectable()
export class CacheInterceptor implements HttpInterceptor {

  constructor() {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Skip non-GET requests
    if (req.method !== 'GET') {
      return next.handle(req);
    }

    // Get the cache expiration time from request params, default to 300 seconds
    const cacheTime = req.params.get('cacheTime') ? +req.params.get('cacheTime') * 1000 : 300000;
    const cacheKey = req.urlWithParams;

    // Check if request is already cached
    const cached = localStorage.getItem(cacheKey);
    if (cached) {
      const cachedResponse = JSON.parse(cached);
      if (Date.now() < cachedResponse.expiry) {
        return of(new HttpResponse({ body: cachedResponse.data }));
      } else {
        // If cache is expired, remove it
        localStorage.removeItem(cacheKey);
      }
    }

    // Make the request and cache the response
    return next.handle(req).pipe(
      tap(event => {
        if (event instanceof HttpResponse) {
          const dataToCache = {
            data: event.body,
            expiry: Date.now() + cacheTime
          };
          localStorage.setItem(cacheKey, JSON.stringify(dataToCache));
        }
      }),
      shareReplay(1)
    );
  }
}


// without local storage
// intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
//     // Skip non-GET requests
//     if (req.method !== 'GET') {
//       return next.handle(req);
//     }

//     // Get the cache expiration time from request params, default to 300 seconds
//     const cacheTime = req.params.get('cacheTime') ? +req.params.get('cacheTime') * 1000 : 300000;
//     const cacheKey = req.urlWithParams;

//     // Check if request is already cached
//     const cachedResponse = this.cache.get(cacheKey);
//     if (cachedResponse && (Date.now() < cachedResponse.expiry)) {
//       return of(new HttpResponse({ body: cachedResponse.data }));
//     }

//     // Make the request and cache the response
//     return next.handle(req).pipe(
//       tap(event => {
//         if (event instanceof HttpResponse) {
//           this.cache.set(cacheKey, {
//             data: event.body,
//             expiry: Date.now() + cacheTime
//           });
//         }
//       }),
//       shareReplay(1)
//     );
//   }
// }
