import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
// Imports necessary for the component
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { catchError, finalize, tap } from 'rxjs/operators';
import { Router } from '@angular/router';
import { Subscription } from 'rxjs';

// Services
import { AuthFirebaseService } from 'src/app/services/auth-firebase.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { AuthService } from 'src/app/services/auth.service';
import { DialogService } from 'primeng/dynamicdialog';
import { ConfirmationService } from 'primeng/api';

// Configs and keys
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

// Modules and Components
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { PersonalInformationComponent } from './personal-information/personal-information.component';
import { AddLandmarkComponent } from './add-landmark/add-landmark.component';
import { ProfileAsideMenuComponent } from './profile-aside-menu/profile-aside-menu.component';
import { ProfileImageComponent } from './profile-image/profile-image.component';
import { ProfileService } from 'src/app/services/profile.service';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { environment } from 'src/environments/environment';
import { ConfirmLogoutComponent } from 'src/app/Common/component/confirm-logout/confirm-logout.component';

@Component({
  selector: 'app-personal-profile',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    ConfirmDialogModule,
    TranslateModule,
    PersonalInformationComponent,
    HeaderComponent,
    NewFooterComponent,
    FooterComponent,
    ProfileAsideMenuComponent,
    ProfileImageComponent,
    LazyLoadSectionDirective
  ],
  templateUrl: './personal-profile.component.html',
  styleUrls: ['./personal-profile.component.scss']
})
export class PersonalProfileComponent implements OnInit {
  private subscriptions: Subscription[] = [];
  currentLanguage: string = '';

  activeTab: number = 0;
  currentProfileData: any;
  socialIcons: any;
  currentPage: any;

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    private confirmationService: ConfirmationService,
    private authFirebaseService: AuthFirebaseService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private profileService: ProfileService,
    private publicService: PublicService,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (this.authService.isLoggedIn()) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.publicService?.recallProfileDataFuntion?.subscribe((res: boolean) => {
        if (res == true) {
          this.getProfileData();
        }
      });
      this.loadLoginInformation();
      this.subscribeToPageData();
      this.publicService?.recallProfileDataLocalStorage?.subscribe((res: any) => {
        if (res == true) {
          this.loadLoginInformation();
        }
      });
    } else {
      this.logOut();
    }
    this.getProfileData();
  }
  private loadLoginInformation(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
        this.currentProfileData = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
        this.socialIcons = this.currentProfileData?.social[0];
      }
      this.updateMetaTags();
    }
  }
  private subscribeToPageData(): void {
    this.publicService.pushUrlData.subscribe({
      next: (res: any) => this.handlePageData(res),
      error: (err: any) => this.alertsService.openToast('error', err)
    });
  }
  private handlePageData(res: any): void {
    if (res && res.page) {
      this.currentPage = res.page;
      console.log(this.currentPage);

      // this.activeTab = res.page;
    }
  }

  // Start Profile Data Functions
  getProfileData(): void {
    this.publicService.show_loader.next(true);
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
  }
  // End Profile Data Functions

  // Start Add Landmark
  addLandmark(): void {
    if (isPlatformBrowser(this.platformId)) {
      const ref = this.dialogService?.open(AddLandmarkComponent, {
        dismissableMask: true,
        width: '100%',
        height: '100%',
        styleClass: 'add-landmark-modal',
      });
      ref.onClose.subscribe((res: any) => {

      });
    }
  }
  // End Add Landmark

  addNewStory(event: any): void {
    if (event.isAddNewStory == true) {
      this.profileService.recallGetCurrent14Stories.next(true);
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
  private updateMetaTags(): void {
    if (!this.currentProfileData?.PersonalData) return;
    const firstName = this.currentProfileData.PersonalData.first_name;
    const lastName = this.currentProfileData.PersonalData.last_name;
    const fullName = `${firstName} ${lastName}`;
    const photoUrl = `${this.currentProfileData.PersonalData.photo}`;
    if (isPlatformBrowser(this.platformId)) {
      this.metadataService.updateTitle(this.publicService.translateTextFromJson('nav.profile') + ' | ' + fullName);
      this.metadataService.updateMetaTagsName([
        { name: 'title', content: this.publicService.translateTextFromJson('nav.profile') + ' | ' + fullName },
      ]);
      this.metadataService.updateMetaTagsProperty([
        { property: 'og:title', content: this.publicService.translateTextFromJson('nav.profile') + ' | ' + fullName },
        { property: 'og:description', content: 'fullName' },
      ]);
      this.metadataService.setSharePreviewImage(photoUrl);
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
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
