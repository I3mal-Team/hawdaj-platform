import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { AlertsService } from 'src/app/services/alerts.service';
import { TourGuidesService } from '../../tour-guides.service';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, tap, catchError } from 'rxjs';
import { CommonModule } from '@angular/common';
import { DialogModule } from 'primeng/dialog';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-tour-guide-image',
  standalone: true,
  imports: [CommonModule, TranslateModule, DialogModule],
  templateUrl: './tour-guide-image.component.html',
  styleUrls: ['./tour-guide-image.component.scss']
})
export class TourGuideImageComponent {
  private subscriptions: Subscription[] = [];

  @Input() src: string | null;
  @Input() name: string = '';
  @Output() uploadHandler: EventEmitter<any> = new EventEmitter();
  summaryName: string = '';


  display: boolean = false;
  isLoading: boolean = false;

  constructor(
    private _TourGuidesService: TourGuidesService,
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
    this.name = file?.name;
    this.uploadFile(file);
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
    formData.append('image', file);
    let subscribe: Subscription = this._TourGuidesService?.uploadPhotoFile(formData).pipe(
      tap(res => this.handleUploadFileSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribe);
  }
  private handleUploadFileSuccess(response: any): void {
    if (response?.code == 200) {
      this.src = response?.data?.image;
      // this.publicService.recallProfileDataFuntion.next(true);
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
