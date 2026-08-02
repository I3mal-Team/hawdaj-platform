import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../auth.service';
import { Observable, throwError } from 'rxjs';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { catchError, finalize, tap } from 'rxjs/operators';
import { Router } from '@angular/router';
import { PublicService } from '../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { AuthFirebaseService } from 'src/app/services/auth-firebase.service';
import { keys } from '../../modules/shared/configs/localstorage-key';
import { isPlatformBrowser } from '@angular/common';

@Injectable()
export class HttpErrorInterceptor implements HttpInterceptor {


  constructor(

    private authFirebaseService: AuthFirebaseService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private router: Router
  ) { }

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    return next.handle(request)
      .pipe(
        catchError((error: HttpErrorResponse) => {
          let errorMsg = '';
          if (error.error instanceof ErrorEvent) {
            // console.error('this is client side error');
            errorMsg = `${error.error.message}`;
          }
          else {
            // console.error('this is server side error');
            errorMsg = `${error.error.message}`;

          }
          if (error?.status == 500) {
            // this.router?.navigate(['/error-500']);
          }
          if (error?.status == 403) {
            // window.location.reload();
          }

          if (error?.status == 404) {
          }
          if (error?.status == 400) {
          }
          if (error?.status == 401) {
            // Client-specific operations
            if (isPlatformBrowser(this.platformId)) {
              //Remove all items in local storage, beacuse this request was expired from another device
              this.logOut();
            }
          }
          // console.error(errorMsg);
          return throwError(errorMsg);
        })
      )
  }

  // Start Logout
  logOut(): void {
    this.executeLogout();
  }
  private executeLogout(): void {
    this.publicService.show_loader.next(true);
    const logout$ = this.authService.signOut().pipe(
      tap(res => this.handleLogoutResponse(res)),
      finalize(() => this.publicService.show_loader.next(false))
    );
    logout$.subscribe({
      error: (err: any) => this.alertsService.openToast('error', err)
    });
  }
  private handleLogoutResponse(res: any): void {
    if (res?.code == 200) {
      this.handleSuccess(res?.message);
      this.performLocalLogout();
      this.publicService.recallProfileDataLocalStorage.next(true);
      this.router.navigate(['/home']);
    } else {
      this.handleError(res?.message);
    }
  }
  private performLocalLogout(): void {
    if (isPlatformBrowser(this.platformId)) {
    localStorage.removeItem(keys.prepareStepData);
    localStorage.removeItem(keys.saveTripData);
    localStorage.removeItem(keys.logged);
    localStorage.removeItem(keys.token);
    localStorage.removeItem(keys.userData);
    localStorage.removeItem(keys.profileData);
    localStorage.removeItem(keys.userLoginData);
    this.publicService.recallProfileDataLocalStorage.next(true);
    this.authFirebaseService.logout();
  }
}
  // End Logout

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
  }
  private setMessage(message: string, type: string): void {
    this.alertsService.openToast(type, message);
    this.publicService?.show_loader?.next(false);
  }

}
