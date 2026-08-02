import { AuthFirebaseService } from './../../../services/auth-firebase.service';
import { AuthService } from '../../../services/auth.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from './../../../modules/shared/configs/localstorage-key';
// Modules
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';

// Components
import { UploadImageVideoFromServerComponent } from '../personal-information/stories-slider/upload-image-video-from-server/upload-image-video-from-server.component';
import { ChangePasswordComponent } from '../change-password/change-password.component';
import { AddLandmarkComponent } from '../add-landmark/add-landmark.component';

// Services
import { PublicService } from './../../../modules/shared/services/public.service';
import { Subscription, catchError, finalize, tap } from 'rxjs';
import { DomSanitizer } from '@angular/platform-browser';
import { Router, RouterModule } from '@angular/router';
import { DialogService } from 'primeng/dynamicdialog';
import { ConfirmationService } from 'primeng/api';
import { Component, EventEmitter, Output } from '@angular/core';
import { ProfileService } from 'src/app/services/profile.service';
import { ConfirmLogoutComponent } from 'src/app/Common/component/confirm-logout/confirm-logout.component';

interface IAsideMenu {
  id: string,
  icon: string | any,
  text: string,
  route?: string,
  type: string
}

@Component({
  selector: 'app-profile-aside-menu',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule],
  templateUrl: './profile-aside-menu.component.html',
  styleUrls: ['./profile-aside-menu.component.scss']
})
export class ProfileAsideMenuComponent {
  private subscriptions: Subscription[] = [];
  storyData: any;
  @Output() addNewStoryHandler = new EventEmitter();

  asideMenuLinks: IAsideMenu[] = [
    {
      id: 'info',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="23" height="23" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
      <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
      <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
    </svg>`),
      text: 'labels.personalInformation',
      route: '/Profile/Information',
      type: 'route'
    },
    {
      id: 'tourGuideInfo',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="23" height="23" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
      <path d="M12 9h.01" />
      <path d="M11 12h1v4h1" />
    </svg>`),
      text: 'titles.tourGuideInfo',
      route: '/Profile/tour-guide-info',
      type: 'route'
    },
    {
      id: 'myProperties',
      icon: this.sanitizer.bypassSecurityTrustHtml(`
    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 20 20" fill="none">
      <path d="M5.58367 15.6253H3.45868C1.92535 15.6253 1.04199 14.742 1.04199 13.2086V3.45868C1.04199 1.92535 1.92535 1.04199 3.45868 1.04199H7.042C8.57534 1.04199 9.45864 1.92535 9.45864 3.45868V5.00033C9.45864 5.34199 9.17531 5.62533 8.83364 5.62533C8.49197 5.62533 8.20864 5.34199 8.20864 5.00033V3.45868C8.20864 2.60868 7.892 2.29199 7.042 2.29199H3.45868C2.60868 2.29199 2.29199 2.60868 2.29199 3.45868V13.2086C2.29199 14.0586 2.60868 14.3753 3.45868 14.3753H5.58367C5.92534 14.3753 6.20867 14.6587 6.20867 15.0003C6.20867 15.342 5.92534 15.6253 5.58367 15.6253Z" fill="currentColor"/>
      <path d="M12.4673 18.9583H7.60064C5.92564 18.9583 4.95898 17.9917 4.95898 16.3167V7.01665C4.95898 5.34165 5.92564 4.375 7.60064 4.375H12.4673C14.1423 4.375 15.1006 5.34165 15.1006 7.01665V16.3167C15.1006 17.9917 14.1423 18.9583 12.4673 18.9583ZM7.60064 5.625C6.60064 5.625 6.20898 6.01665 6.20898 7.01665V16.3167C6.20898 17.3167 6.60064 17.7083 7.60064 17.7083H12.4673C13.459 17.7083 13.8506 17.3167 13.8506 16.3167V7.01665C13.8506 6.01665 13.459 5.625 12.4673 5.625H7.60064Z" fill="currentColor"/>
      <path d="M16.542 15.6253H14.4753C14.1336 15.6253 13.8503 15.342 13.8503 15.0003C13.8503 14.6587 14.1336 14.3753 14.4753 14.3753H16.542C17.392 14.3753 17.7086 14.0586 17.7086 13.2086V3.45868C17.7086 2.60868 17.392 2.29199 16.542 2.29199H12.9586C12.1086 2.29199 11.792 2.60868 11.792 3.45868V5.00033C11.792 5.34199 11.5087 5.62533 11.167 5.62533C10.8253 5.62533 10.542 5.34199 10.542 5.00033V3.45868C10.542 1.92535 11.4253 1.04199 12.9586 1.04199H16.542C18.0753 1.04199 18.9586 1.92535 18.9586 3.45868V13.2086C18.9586 14.742 18.0753 15.6253 16.542 15.6253Z" fill="currentColor"/>
      <path d="M11.6663 9.79199H8.33301C7.99134 9.79199 7.70801 9.50866 7.70801 9.16699C7.70801 8.82533 7.99134 8.54199 8.33301 8.54199H11.6663C12.008 8.54199 12.2913 8.82533 12.2913 9.16699C12.2913 9.50866 12.008 9.79199 11.6663 9.79199Z" fill="currentColor"/>
      <path d="M11.6663 12.292H8.33301C7.99134 12.292 7.70801 12.0087 7.70801 11.667C7.70801 11.3253 7.99134 11.042 8.33301 11.042H11.6663C12.008 11.042 12.2913 11.3253 12.2913 11.667C12.2913 12.0087 12.008 12.292 11.6663 12.292Z" fill="currentColor"/>
      <path d="M10 18.958C9.65833 18.958 9.375 18.6747 9.375 18.333V15.833C9.375 15.4913 9.65833 15.208 10 15.208C10.3417 15.208 10.625 15.4913 10.625 15.833V18.333C10.625 18.6747 10.3417 18.958 10 18.958Z" fill="currentColor"/>
    </svg>
  `),
      text: 'titles.myProperties',
      route: '/Profile/my-properties',
      type: 'route'
    },
    {
      id: 'trips',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
        d="M16.5161 9.43439C16.9624 9.86431 17.5483 10.0797 18.1341 10.0797C18.7199 10.0797 19.3058 9.86431 19.7521 9.43439L21.4212 7.82473C23.2345 6.03723 23.2345 3.12956 21.4212 1.34298C20.5434 0.477643 19.3755 0.000976562 18.1341 0.000976562C16.8927 0.000976562 15.7248 0.477643 14.846 1.34298C13.0337 3.13048 13.0337 6.03814 14.8535 7.83206L16.5161 9.43439ZM19.5299 4.38906C19.5299 5.14806 18.905 5.76406 18.135 5.76406C17.3651 5.76406 16.7402 5.14806 16.7402 4.38906C16.7402 3.63006 17.3651 3.01406 18.135 3.01406C18.905 3.01406 19.5299 3.63006 19.5299 4.38906ZM22.7835 15.5843C22.7835 17.6065 21.1153 19.251 19.064 19.251H12.5548C12.0415 19.251 11.6249 18.8403 11.6249 18.3343C11.6249 17.8283 12.0415 17.4176 12.5548 17.4176H19.064C20.0896 17.4176 20.9238 16.5954 20.9238 15.5843C20.9238 14.5732 20.0896 13.751 19.064 13.751H8.83522C6.78388 13.751 5.11567 12.1065 5.11567 10.0843C5.11567 8.06214 6.78388 6.41764 8.83522 6.41764H11.6249C12.1382 6.41764 12.5548 6.82831 12.5548 7.33431C12.5548 7.84031 12.1382 8.25098 11.6249 8.25098H8.83522C7.80955 8.25098 6.97544 9.07323 6.97544 10.0843C6.97544 11.0954 7.80955 11.9176 8.83522 11.9176H19.064C21.1153 11.9176 22.7835 13.5621 22.7835 15.5843ZM9.7651 18.3343C9.7651 18.8403 9.34851 19.251 8.83522 19.251H7.44039L5.66337 21.4702C5.48204 21.7975 5.13426 22.001 4.75673 22.001H4.45917C4.10395 22.001 3.86032 21.649 3.98864 21.3226L4.80601 19.251H2.64867C2.23766 19.251 1.84711 19.0722 1.58209 18.7624L0.562935 17.5707C0.286758 17.0941 0.635466 16.501 1.19247 16.501C1.38496 16.501 1.56907 16.5761 1.70484 16.71L2.73701 17.4176H4.82089L3.99236 15.3478C3.86125 15.0215 4.10581 14.6676 4.46195 14.6676H4.73906C5.11753 14.6676 5.4653 14.8711 5.6457 15.1984L7.43946 17.4176H8.83429C9.34758 17.4176 9.76417 17.8283 9.76417 18.3343H9.7651Z"
        fill="currentColor" />
    </svg>`),
      text: 'profile.myTravel',
      route: '/trips/list',
      type: 'route'
    },
    {
      id: 'addNewLandmark',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg width="24" height="23" viewBox="0 0 24 23" fill="none" xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink">
      <mask id="mask0_127_280" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24"
        height="23">
        <rect x="0.466309" width="23.3317" height="23" fill="url(#pattern0_127_280)" />
      </mask>
      <g mask="url(#mask0_127_280)">
        <rect x="-0.159912" width="25.76" height="28.52" fill="currentColor" />
      </g>
      <defs>
        <pattern id="pattern0_127_280" patternContentUnits="objectBoundingBox" width="1" height="1">
          <use xlink:href="#image0_127_280" transform="matrix(0.00390625 0 0 0.00396259 0 -0.00721152)" />
        </pattern>
        <image id="image0_127_280" width="256" height="256"
          xlink:href="data:image/png;base64,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" />
      </defs>
    </svg>`),
      text: 'profile.addNewLandmark',
      type: 'click'
    },
    {
      id: 'addStory',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6951 16.5V5.5H12.5548V16.5H10.6951Z"
        fill="currentColor" />
      <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2043 11.9166H6.04565V10.0833H17.2043V11.9166Z"
        fill="currentColor" />
      <path fill-rule="evenodd" clip-rule="evenodd"
        d="M7.47727 1.78029C7.98704 1.55713 8.51883 1.37378 9.0685 1.23431L9.53207 3.00979C9.08307 3.12371 8.64866 3.27347 8.23209 3.45583L7.47727 1.78029ZM2.61947 6.2146C3.15669 5.23403 3.85355 4.35167 4.67555 3.60108L5.93937 4.94605C5.2658 5.56112 4.69525 6.28376 4.25585 7.0858L2.61947 6.2146ZM1.39624 10.9999C1.39624 10.4375 1.44305 9.88508 1.53323 9.34654L3.36818 9.6451C3.29446 10.0854 3.25602 10.5378 3.25602 10.9999C3.25602 11.462 3.29446 11.9145 3.36818 12.3548L1.53323 12.6533C1.44305 12.1148 1.39624 11.5624 1.39624 10.9999ZM4.67554 18.3988C3.85355 17.6481 3.15669 16.7659 2.61947 15.7853L4.25585 14.9141C4.69525 15.7161 5.2658 16.4387 5.93937 17.0538L4.67554 18.3988ZM9.0685 20.7655C8.51883 20.6261 7.98704 20.4427 7.47727 20.2196L8.23208 18.544C8.64866 18.7264 9.08307 18.8761 9.53207 18.9901L9.0685 20.7655Z"
        fill="currentColor" />
      <path fill-rule="evenodd" clip-rule="evenodd"
        d="M19.994 11C19.994 6.44367 16.2471 2.75002 11.625 2.75002V0.916687C17.2742 0.916687 21.8538 5.43115 21.8538 11C21.8538 16.5689 17.2742 21.0834 11.625 21.0834V19.25C16.2471 19.25 19.994 15.5564 19.994 11Z"
        fill="currentColor" />
    </svg>`),
      text: 'profile.addStory',
      type: 'click'
    },
    {
      id: 'changePassword',
      icon: this.sanitizer.bypassSecurityTrustHtml(`<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
        d="M17.1236 8.53125V5.90625C17.1236 2.64403 14.4414 0 11.1321 0C7.82287 0 5.1407 2.64403 5.1407 5.90625V8.53125C4.03761 8.53125 3.14355 9.41259 3.14355 10.5V12.4688V13.125V14.4375V15.0938C3.14355 18.356 5.82572 21 9.13499 21H13.1293C16.4386 21 19.1207 18.356 19.1207 15.0938V14.4375V13.125V12.4688V10.5C19.1207 9.41194 18.226 8.53125 17.1236 8.53125ZM6.47213 5.90625C6.47213 3.36919 8.55848 1.3125 11.1321 1.3125C13.7058 1.3125 15.7921 3.36919 15.7921 5.90625V8.53125H14.4607V5.90756C14.4607 4.095 12.9708 2.62631 11.1321 2.62631C9.29343 2.62631 7.80356 4.095 7.80356 5.90756V8.53125H6.47213V5.90625ZM13.795 5.90625V5.90822V8.53125H8.46928V5.90756V5.90625C8.46928 4.45659 9.66157 3.28125 11.1321 3.28125C12.6027 3.28125 13.795 4.45659 13.795 5.90625ZM17.7893 12.4688V13.125V14.4375V15.0938C17.7893 17.6262 15.6983 19.6875 13.1293 19.6875H9.13499C6.566 19.6875 4.47499 17.6262 4.47499 15.0938V14.4375V13.125V12.4688V10.5C4.47499 10.1377 4.77323 9.84375 5.1407 9.84375C5.58473 9.84375 6.0281 9.84375 6.47213 9.84375H15.7921C16.2355 9.84375 16.6789 9.84375 17.1236 9.84375C17.4904 9.84375 17.7893 10.1377 17.7893 10.5V12.4688Z"
        fill="currentColor" />
      <path
        d="M11.1322 12.4688C10.3973 12.4688 9.80078 13.0561 9.80078 13.7813C9.80078 14.1796 10.0225 14.9363 10.2448 15.5348C10.4259 16.0217 10.6536 16.4049 11.1322 16.4049C11.6528 16.4049 11.8385 16.0256 12.0203 15.5413C12.2453 14.9415 12.4636 14.1809 12.4636 13.7813C12.4636 13.0561 11.8672 12.4688 11.1322 12.4688Z"
        fill="currentColor" />
    </svg>`),
      text: 'profile.changePassword',
      type: 'click'
    }
  ];

  url: string = '';

  constructor(
    private authFirebaseService: AuthFirebaseService,
    private confirmationService: ConfirmationService,
    private profileService: ProfileService,
    private publicService: PublicService,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private sanitizer: DomSanitizer,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.url = this.router.url;
  }

  clickTab(type: string): void {
    if (type == 'addNewLandmark') {
      this.addLandmark();
    }
    if (type == 'addStory') {
      this.uploadStory();
    }
    if (type == 'changePassword') {
      this.changePassword();
    }
  }

  // Start Upload New Story Modal
  uploadStory(): void {
    const ref = this.dialogService?.open(UploadImageVideoFromServerComponent, {
      header: this.publicService?.translateTextFromJson('profile.addNewStory'),
      dismissableMask: false,
      width: '35%',
      height: '70%',
      styleClass: 'custom-modal',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
        this.storyData = res;
        this.addNewStory();
      }
    });
  }
  // End Upload New Story Modal
  // Start Upload Sotory Functions
  addNewStory(): void {
    this.publicService.show_loader.next(true);
    let formData = new FormData();
    formData.append('type', 'file');
    formData.append('file', this.storyData?.file);
    formData.append('text', this.storyData?.storyName);
    // formData.append('storyText', this.storyData?.storyName);
    let addStorySubscribe: Subscription = this.profileService?.addNewStory(formData).pipe(
      tap(res => this.handleAddStorySuccess(res)),
      catchError(err => this.handleError(err)),
      finalize(() => this.finalizeLoading())
    ).subscribe();
    this.subscriptions.push(addStorySubscribe);
  }
  private handleAddStorySuccess(response: any): void {
    if (response?.code == 200) {
      this.handleSuccess(response?.message);
      this.addNewStoryHandler.emit({ isAddNewStory: true });
    } else {
      this.handleError(response?.message);
    }
  }
  private finalizeLoading(): void {
    this.publicService.show_loader.next(false);
  }
  // End Upload Sotory Functions


  // Start Add Landmark
  addLandmark(): void {
    const ref = this.dialogService?.open(AddLandmarkComponent, {
      dismissableMask: true,
      width: '100%',
      height: '100%',
      styleClass: 'add-landmark-modal',
    });
    ref.onClose.subscribe((res: any) => {

    });
  }
  // End Add Landmark

  changePassword(): void {
    const ref = this.dialogService.open(ChangePasswordComponent, {
      header: this.publicService.translateTextFromJson('labels.changePassword'),
      dismissableMask: false,
      width: '35%'
    });
    ref.onClose.subscribe((res: boolean) => {
      if (res) {
        // Handle the change password close event
      }
    });
  }

  // Start Logout
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

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription?.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
