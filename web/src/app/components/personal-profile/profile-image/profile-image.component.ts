import { ProfileService } from '../../../services/profile.service';
import { PublicService } from './../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { CommonModule } from '@angular/common';
import { DialogModule } from 'primeng/dialog';

@Component({
  selector: 'app-profile-image',
  standalone: true,
  imports: [CommonModule, TranslateModule, DialogModule],
  templateUrl: './profile-image.component.html',
  styleUrls: ['./profile-image.component.scss']
})
export class ProfileImageComponent {
  private subscriptions: Subscription[] = [];

  @Input() src: string | null;
  @Input() name: string = '';
  @Output() uploadHandler: EventEmitter<any> = new EventEmitter();
  summaryName: string = '';

  display: boolean = false;
  isLoading: boolean = false;

  constructor(
    private profileService: ProfileService,
    private alertsService: AlertsService,
    private publicService: PublicService,
  ) { }

  ngOnInit(): void {
    const fName = this.name.split(" ");
    this.summaryName = fName[0].charAt(0) + fName[1].charAt(0);
  }

  changeProfileImage(): void {
    this.display = true;
  }

  handleInputChange(e: any): void {
    var file = e.dataTransfer ? e.dataTransfer.files[0] : e.target.files[0];
    let formData = new FormData();
    formData.append('files', file);
    this.uploadHandler?.emit({ file: file });
    // this.formatSizeUnits(file?.size);
    this.name = file?.name;
    this.uploadFile(file);
    // this.isLoading = true;
    // setTimeout(() => {
    //   this.isLoading = false;
    // }, 1000);
    // var reader = new FileReader();
    // reader.onload = this._handleReaderLoaded?.bind(this);
    // reader.readAsDataURL(file);
  }
  _handleReaderLoaded(e: any): void {
    var reader = e.target;
    this.src = reader.result;
    this.display = false;
  }
  // Start Upload File
  uploadFile(file: any): void {
    this.isLoading = true;
    let formData = new FormData();
    formData.append('photo', file);
    let subscribe: Subscription = this.profileService?.uploadProfileFile(formData).pipe(
      tap(res => this.handleUploadFileSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribe);
  }
  private handleUploadFileSuccess(response: any): void {
    if (response?.code == 200) {
      this.src = response?.data?.photo;
      this.publicService.recallProfileDataFuntion.next(true);
      this.handleSuccess(response?.message);
    } else {
      this.handleError(response?.message);
    }
    this.isLoading = false;
  }
  // End Upload File

  removeImage(): void {
    this.src = null;
    this.uploadFile(null);
    // this.display = false;
  }
  cancel(): void {
    this.display = false;
  }

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
    this.isLoading = false;
  }
  private setMessage(message: string, type: string): void {
    this.alertsService.openToast(type, message);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
