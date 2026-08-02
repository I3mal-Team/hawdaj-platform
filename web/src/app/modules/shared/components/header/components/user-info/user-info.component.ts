import { AuthFirebaseService } from './../../../../../../services/auth-firebase.service';
import { AuthService } from '../../../../../../services/auth.service';
import { Component, ElementRef, Inject, OnInit, PLATFORM_ID, Renderer2 } from '@angular/core';
import { PublicService } from './../../../../services/public.service';
import { keys } from './../../../../configs/localstorage-key';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { ConfirmationService } from 'primeng/api';
import { finalize, tap } from 'rxjs/operators';
import { Router, RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { AlertsService } from 'src/app/services/alerts.service';
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { DialogService } from 'primeng/dynamicdialog';
import { ConfirmLogoutComponent } from 'src/app/Common/component/confirm-logout/confirm-logout.component';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TranslateModule,
    ConfirmDialogModule
  ],
  selector: 'app-user-info',
  templateUrl: './user-info.component.html',
  styleUrls: ['./user-info.component.scss'],
  providers: [ConfirmationService]
})
export class UserInfoComponent implements OnInit {
  private unsubscribe: Subscription[] = [];

  currentLoginInformation: any;
  collapse: boolean = false;
  page: any;
  summaryName: string = '';

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private confirmationService: ConfirmationService,
    private authFirebaseService: AuthFirebaseService,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private router: Router,
    private renderer: Renderer2,
    private elementRef: ElementRef,
    private dialogService: DialogService
  ) { }

  ngOnInit(): void {
    this.addClickOutsideListener();
    if (isPlatformBrowser(this.platformId)) {
      if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
        this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
        const fName = this.currentLoginInformation?.PersonalData?.full_name.split(" ");
        this.summaryName = fName[0].charAt(0) + fName[1].charAt(0);
      }
    }

    this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
      if (res === true && isPlatformBrowser(this.platformId)) {
        if (isPlatformBrowser(this.platformId)) {
          if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
            this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
          }
        }
      }
    })
    this.publicService?.pushUrlData?.subscribe((res: any) => {
      this.page = res.page;
    })
  }
  private documentClickListener!: () => void;
  addClickOutsideListener(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.documentClickListener = this.renderer.listen('document', 'click', (event: MouseEvent) => {
        const clickedInside = this.elementRef.nativeElement.contains(event.target);
        if (!clickedInside && this.collapse) {
          this.collapse = false;
        }
      });
    }
  }

  logOut(): void {
    const confirmationMessage = this.publicService.translateTextFromJson('general.areYouSureToLogout');
    const confirmationHeader = this.publicService.translateTextFromJson('general.logout');
    // this.confirmationService.confirm({
    //   message: confirmationMessage,
    //   header: confirmationHeader,
    //   icon: 'pi pi-exclamation-triangle',
    //   accept: () => this.executeLogout()
    // });
    const ref = this?.dialogService?.open(ConfirmLogoutComponent, {
      width: '35%',
      header: this.publicService?.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
      data: {
        title: confirmationMessage,
        onConfirm: () => this.executeLogout()
      }
    },);
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
    }
    this.publicService.recallProfileDataLocalStorage.next(true);
    this.authFirebaseService.logout();
  }

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

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
