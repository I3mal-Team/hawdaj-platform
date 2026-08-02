import { environment } from './../../environments/environment';
import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import 'firebase/compat/auth';
import { initializeApp } from 'firebase/app';
import { AngularFireAuth } from '@angular/fire/compat/auth';
import { PublicService } from '../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from '../modules/shared/configs/localstorage-key';
import {
  signInWithPopup,
  GoogleAuthProvider,
  TwitterAuthProvider,
  initializeAuth,
  browserSessionPersistence,
  browserPopupRedirectResolver,
  signOut,
} from 'firebase/auth';
import { isPlatformBrowser } from '@angular/common';
import { Router } from '@angular/router';
import { catchError, finalize, tap } from 'rxjs/operators';
import { Subscription } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root',
})
export class AuthFirebaseService {
  private subscriptions: Subscription[] = [];
  apiUrl = environment.apiUrl;
  GoogleProvider: GoogleAuthProvider;
  TwitterProvider: TwitterAuthProvider;
  auth: any;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    private publicService: PublicService,
    private authService: AuthService,
    public afAuth: AngularFireAuth,
    private http: HttpClient,
    private router: Router
  ) {
    if (isPlatformBrowser(this.platformId)) {
      const firebaseApp = initializeApp(environment.firebaseConfig);
      this.auth = initializeAuth(firebaseApp, {
        persistence: browserSessionPersistence,
        popupRedirectResolver: browserPopupRedirectResolver,
      });
      this.GoogleProvider = new GoogleAuthProvider();
      this.TwitterProvider = new TwitterAuthProvider();
    }
  }

  // Sign in with Google
  GoogleAuth(): any {
    if (!isPlatformBrowser(this.platformId)) {
      console.warn('GoogleAuth is not supported in server-side context.');
      return;
    }

    signInWithPopup(this.auth, this.GoogleProvider)
      .then((result) => {
        this.processSignInGoogleResult(result, 'google');
      })
      .catch((error) => {
        console.error('Error during Google sign in: ', error.message);
      });
  }
  private processSignInGoogleResult(result: any, providerType: string): void {
    if (result) {
      let formData = new FormData();
      formData.append('name', result.user?.displayName);
      formData.append('email', result.user?.email);
      formData.append('provider_id', result.user?.uid);
      formData.append('provider_type', providerType);
      this.signWithSocial(formData);
    }
  }

  // Sign in with Twitter
  TwitterAuth(): any {
    if (!isPlatformBrowser(this.platformId)) {
      console.warn('TwitterAuth is not supported in server-side context.');
      return;
    }
    signInWithPopup(this.auth, this.TwitterProvider)
      .then((result) => {
        this.processSignInTwitterResult(result, 'twitter');
      })
      .catch((error) => {
        console.error('Error during Twitter sign in: ', error.message);
      });
  }
  private processSignInTwitterResult(result: any, providerType: string): void {
    if (result) {
      let formSocialData: any = result;

      let formData = new FormData();
      formData.append('name', formSocialData?.user?.displayName);
      formData.append('email', formSocialData?.user?.reloadUserInfo?.screenName + '@twitter.com');
      formData.append('provider_id', formSocialData?.user?.uid);
      formData.append('provider_type', providerType);
      this.signWithSocial(formData);
    }
  }


  // Method to log out the user
  async logout(): Promise<void> {
    if (!isPlatformBrowser(this.platformId)) {
      // Handle server-side behavior or simply return if logout functionality is not applicable
      console.log('Logout attempted on server');
      return;
    }

    try {
      await signOut(this.auth);
      console.log('User signed out successfully');
      // Perform any additional actions like redirecting to home page
      this.router.navigate(['/home']);
    } catch (error) {
      console.error('Error during sign out: ', error.message);
    }
  }

  signWithSocial(data: any): void {
    this.publicService.show_loader.next(true);
    this.http.post<any>(`${this.apiUrl}/social/callback`, data).subscribe({
      next: (res) => this.handleResponseLogin(res),
      error: (err) => this.handleErrorLogin(err)
    });
  }
  private handleResponseLogin(res: any): void {
    if (res?.code !== 200) {
      this.handleErrorResponse(res);
      return;
    }

    this.processSuccessfulLogin(res);
  }
  private handleErrorResponse(res: any): void {
    if (res?.message) {
      this.alertsService.openToast('error', res.message);
    }
    this.publicService.show_loader.next(false);
  }
  private processSuccessfulLogin(res: any): void {
    if (isPlatformBrowser(this.platformId)) {
      window.localStorage.setItem(keys?.userLoginData, JSON.stringify(res.data));
      this.getProfileData();
    }
  }
  private handleErrorLogin(err: any): void {
    if (err) {
      this.alertsService.openToast('error', err);
    }
    this.publicService.show_loader.next(false);
  }


  // Start Profile Data Functions
  getProfileData(): void {
    if (isPlatformBrowser(this.platformId)) {
      const resetSubscription: any = this.authService?.profileData()?.pipe(
        tap(res => this.handleProfileDataResponse(res)),
        catchError(async (err) => this.handleError(err)),
        finalize(() => this.publicService.show_loader.next(false))
      ).subscribe();
      this.subscriptions.push(resetSubscription);
    }
  }
  handleProfileDataResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      return;
    }
    localStorage?.setItem(keys.profileData, JSON.stringify(res.data));
    this.publicService.recallProfileDataLocalStorage.next(true);
    this.publicService.closeModal.next(true);
  }
  // End Profile Data Functions

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
