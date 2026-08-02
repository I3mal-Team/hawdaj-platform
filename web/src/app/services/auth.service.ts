import { keys } from '../modules/shared/configs/localstorage-key';
import { JoinUsComponent } from '../components/join-us/join-us.component';
import { environment } from '../../environments/environment';
import { roots } from '../modules/shared/configs/endPoints';
import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { DialogService } from 'primeng/dynamicdialog';
import { isPlatformBrowser } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  apiUrl = environment.apiUrl;

  constructor(
    private dialogService: DialogService,
    private http: HttpClient,
    private router: Router,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
  }

  isLoggedIn(): boolean {
    if (isPlatformBrowser(this.platformId)) {
      if (localStorage.getItem(keys?.userLoginData)) {
        return true;
      }
      return false;
    } else {
      return false; // Default to false in SSR context
    }
  }

  checkIsLogin(): boolean {
    if (this.isLoggedIn()) {
      return true;
    } else {
      this.promptJoinUsDialog();
      return false;
    }
  }

  promptJoinUsDialog() {
    if (isPlatformBrowser(this.platformId)) {
      const ref = this.dialogService.open(JoinUsComponent, {
        width: '30%',
        styleClass: 'join-us-dialog',
        dismissableMask: true
      });

      ref.onClose.subscribe(() => {
        // Handle close event
      });
    }
  }

  login(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.login}`, data);
  }

  register(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.register}`, data);
  }

  forgetPassword(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.forgetPassword}`, data);
  }

  validateResetCode(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.validateResetCode}`, data);
  }

  resetPassword(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.resetPassword}`, data);
  }
  changePassword(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.changePassword}`, data);
  }
  updateProfile(data: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.updateProfile}`, data);
  }
  profileData(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}${roots.auth.profileData}`);
  }
  signOut(): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}${roots.auth.logout}`, {});
  }
}
