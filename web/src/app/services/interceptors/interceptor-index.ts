import { BrowserStateInterceptor } from './browser-state-interceptor.service';
import { HttpInterceptorService } from './http-interceptor.service';
import { HttpErrorInterceptor } from './error-interceptor.service';
import { HTTP_INTERCEPTORS } from "@angular/common/http";
import { CacheInterceptor } from './caching-interceptor';

export const interceptorProviders = [
  {
    provide: HTTP_INTERCEPTORS,
    useClass: HttpInterceptorService,
    multi: true
  },
  {
    provide: HTTP_INTERCEPTORS,
    useClass: HttpErrorInterceptor,
    multi: true
  },
  // { provide: HTTP_INTERCEPTORS, useClass: CacheInterceptor, multi: true },

  {
    provide: HTTP_INTERCEPTORS,
    useClass: BrowserStateInterceptor,
    multi: true
  },
];
