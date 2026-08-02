import { PrepearTripStepperComponent } from './../../../../domains/trip/components/prepear-trip-stepper/prepear-trip-stepper.component';
import { CreateTripComponent } from '../../../../components/create-trip/create-trip.component';
// import { LoginComponent } from '../../../../components/authentication/components/login/login.component';
import { AuthService } from '../../../../services/auth.service';
import { PublicService } from '../../services/public.service';
import { DialogService } from 'primeng/dynamicdialog';
import { keys } from '../../configs/localstorage-key';
import { CommonModule } from '@angular/common';
import { ConfirmationService } from 'primeng/api';
import { Router, RouterModule } from '@angular/router';
import { Subscription, finalize, tap } from 'rxjs';
import { AuthFirebaseService } from '../../../../services/auth-firebase.service';
import { TranslateModule } from '@ngx-translate/core';
import { InviteToVisitComponent } from './components/invite-to-visit/invite-to-visit.component';
import { UserInfoComponent } from './components/user-info/user-info.component';
import { LanguageSelectorComponent } from './components/language-selector/language-selector.component';
import { SidebarModule } from 'primeng/sidebar';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ResponsiveIfDirectiveNotStandalone } from 'src/app/shared/directives/appResponiveIf.direcitve';
import { Component, HostListener, ChangeDetectorRef, Input, OnInit, AfterViewInit, Inject, PLATFORM_ID, ViewChild, ElementRef, Renderer2 } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { AlertsService } from 'src/app/services/alerts.service';
import { ChangeLanguageSelectorComponent } from '../change-language-selector/change-language-selector.component';
import { MapComponent } from 'src/app/components/home-page/components/map/map.component';
import { LoginPopupComponent } from 'src/app/components/authentication/components/login-popup/login-popup.component';
import { ConfirmLogoutComponent } from 'src/app/Common/component/confirm-logout/confirm-logout.component';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TranslateModule,
    InviteToVisitComponent,
    UserInfoComponent,
    LanguageSelectorComponent,
    MapComponent,
    ChangeLanguageSelectorComponent,
    SidebarModule,
    ConfirmDialogModule,
    ResponsiveIfDirectiveNotStandalone
  ],
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
  providers: [ConfirmationService],
})
export class HeaderComponent implements OnInit, AfterViewInit {
  @ViewChild('navbar', { static: false }) navbarElement: ElementRef;  // Use @ViewChild to safely access DOM

  private unsubscribe: Subscription[] = [];
  isUserLoggedin: boolean = false;
  currentLanguage: any;
  currentLoginInformation: any = null;

  public isVisitMegaMenuVisible = false;
  collapsedMenu: boolean = false;
  scrollDown: boolean = false;
  @Input() collapse: boolean = false;
  page: any;

  displayMenu: boolean = false;
  showMap: boolean = false;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private confirmationService: ConfirmationService,
    private authFirebaseService: AuthFirebaseService,
    public publicService: PublicService,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private cdr: ChangeDetectorRef,
    private router: Router,
    private renderer: Renderer2,
    private elementRef: ElementRef
  ) { }

  ngOnInit(): void {
    this.addClickOutsideListener();
    if (isPlatformBrowser(this.platformId)) {
      this.publicService?.showMap?.next(false);
      this.publicService?.showMap?.subscribe(res => {
        this.showMap = res;
      });
      this.currentLanguage = this.publicService.getCurrentLanguage();
      if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
        this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
      }
    }
    this.isUserLoggedin = this.authService.isLoggedIn();
    this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
      if (res == true) {
        this.isUserLoggedin = this.authService.isLoggedIn();
        if (isPlatformBrowser(this.platformId)) {
          this.currentLanguage = this.publicService.getCurrentLanguage();
          if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
            this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
          }
        }
        this.cdr.detectChanges();
      }
    });

    this.publicService?.pushUrlData?.subscribe((res: any) => {
      this.page = res.page;
    });
  }

  @HostListener('window:scroll', ['$event'])
  handleScroll(event: Event) {
    if (isPlatformBrowser(this.platformId)) {
      this.handleKeyDown();
    }
  }

  ngAfterViewInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.handleKeyDown();
    }
  }

  handleKeyDown() {
    if (isPlatformBrowser(this.platformId)) {
      const element = this.navbarElement?.nativeElement;  // Access the element via @ViewChild safely
      if (element) {
        if (window.pageYOffset > 30 && this.page !== 'applications') {
          element.classList.add('headerScroll');
        } else {
          element.classList.remove('headerScroll');
        }
      } else {
        console.error("Element with class 'navbar' not found");
      }
    }
  }


  onHoverMegaMenu(): void {
    this.isVisitMegaMenuVisible = true;
  }
  onLeaveMegaMenu(): void {
    this.isVisitMegaMenuVisible = false;
  }
  stopClickPropagation(event: Event): void {
    event.stopPropagation();
  }

  pageIn(pages: string[]): boolean {
    return pages.includes(this.page);
  }
  pageNotIn(pages: string[]): boolean {
    return !pages.includes(this.page);
  }
  private documentClickListener!: () => void;
  addClickOutsideListener(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.documentClickListener = this.renderer.listen('document', 'click', (event: MouseEvent) => {
        const clickedInside = this.elementRef.nativeElement.contains(event.target);
        if (!clickedInside && this.collapse == true) {
          this.openPlace()
        }
      });
    }
  }

  openPlace(): void {
    this.collapse = false;
  }

  login(): void {
    const ref = this?.dialogService?.open(LoginPopupComponent, {
      width: '60%',
      height: '700px',
      styleClass: 'auth-dialog',
    });
  }
  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 100011,
    });
  }
  explore(): void {
    this.publicService?.showMap?.next(true);
    this.showMap = true;
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
      this.publicService.recallProfileDataLocalStorage.next(true);
      this.authFirebaseService.logout();
    }
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
