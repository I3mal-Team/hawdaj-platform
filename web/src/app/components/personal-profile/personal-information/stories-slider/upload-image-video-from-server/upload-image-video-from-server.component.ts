
import { VideoModalComponent } from '../../../../home-page/components/videos-slider/video-modal/video-modal.component';
import { DynamicSvgComponent } from '../../../../../modules/shared/components/icons/dynamic-svg/dynamic-svg.component';
import { AlertsService } from 'src/app/services/alerts.service';
import { Component, OnInit, EventEmitter, Output, Input } from '@angular/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { SkeletonModule } from 'primeng/skeleton';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ImageModule } from 'primeng/image';
import { ToastModule } from 'primeng/toast';

@Component({
  standalone: true,
  imports: [CommonModule, TranslateModule, SkeletonModule, ImageModule, DynamicSvgComponent, FormsModule, ToastModule],
  selector: 'app-upload-image-video-from-server',
  templateUrl: './upload-image-video-from-server.component.html',
  styleUrls: ['./upload-image-video-from-server.component.scss']
})
export class UploadImageVideoFromServerComponent implements OnInit {
  private subscriptions: Subscription[] = [];

  @Input() showFile: boolean = false;
  @Input() isSupportAll: boolean = true;
  @Input() isEdit: boolean = false;
  @Input() showPreview: boolean = true;
  @Input() accept: string = 'image/*,video/*';
  @Input() imageSrc: string = '';
  @Input() supports: string = 'PNG, JPG, GIF up to 10MB';

  @Output() uploadHandler: EventEmitter<any> = new EventEmitter();

  dragging: boolean = false;
  loaded: boolean = false;
  isLoading: boolean = false;

  name: string = '';
  fileSize: any;
  type: string = '';
  storyName: string = null;
  storyFile: string = null;

  constructor(
    private alertsService: AlertsService,
    private publicService: PublicService,
    private dialogService: DialogService,
    private ref: DynamicDialogRef
  ) { }

  ngOnInit(): void {
    if (this.isEdit) {
      this.showFile = true;
      this.name = this.imageSrc;
      this.type = this.imageSrc;
    }
  }

  handleInputChange(e: any): void {
    var file = e.dataTransfer ? e.dataTransfer.files[0] : e.target.files[0];
    this.fileSize = this.formatSizeUnits(file?.size);
    this.name = file?.name;
    this.type = file?.type;
    let formData = new FormData();
    formData.append('files', file);
    this.storyFile = file;
    this.uploadHandler?.emit({ file: file });
    this.isLoading = true;
    setTimeout(() => {
      this.isLoading = false;
    }, 1000);
    var reader = new FileReader();
    reader.onload = this._handleReaderLoaded?.bind(this);
    reader.readAsDataURL(file);
  }
  _handleReaderLoaded(e: any): void {
    var reader = e.target;
    this.imageSrc = reader.result;
    this.showFile = true;
  }
  // Start Upload File
  uploadFile(file: any): void {
    this.isLoading = true;
    let formData = new FormData();
    formData.append('file', file);
    let subscribe: Subscription = this.publicService?.uploadFile(formData).pipe(
      tap(res => this.handleUploadFileSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribe);
  }
  private handleUploadFileSuccess(response: any): void {
    if (response?.success) {
      this.handleSuccess(response?.message);
      this.publicService.recallProfileDataFuntion.next(true);
      this.handleUploadSuccess(response);
    } else {
      this.handleError(response?.message);
    }
  }
  private handleUploadSuccess(res: any) {
    this.imageSrc = res?.result?.fileName;
    // this.ref.close({ file: res?.result?.fileName, storyName: this.storyName });
  }
  // End Upload File
  handleDragOver(event: DragEvent): void {
    event.preventDefault();
  }

  handleDragEnter(): void {
    this.dragging = true;
    // this.showFile = true;
  }

  handleDragLeave(): void {
    this.dragging = false;
    this.showFile = false;
  }

  handleDrop(e: any): void {
    e.preventDefault();
    this.dragging = false;
    this.handleInputChange(e);
    // this.showFile = true;
  }

  handleImageLoad(): void {
    // this.showFile = true;
  }

  remove(): void {
    this.showFile = false;
  }

  formatSizeUnits(size: any): void {
    if (size >= 1073741824) { size = (size / 1073741824).toFixed(2) + " GB"; }
    else if (size >= 1048576) { size = (size / 1048576).toFixed(2) + " MB"; }
    else if (size >= 1024) { size = (size / 1024).toFixed(2) + " KB"; }
    else if (size > 1) { size = size + " bytes"; }
    else if (size == 1) { size = size + " byte"; }
    else { size = "0 bytes"; }
    return size;
  }

  previewVideo(): void {
    const ref = this.dialogService.open(VideoModalComponent, {
      header: '',
      width: '90%',
      baseZIndex: 10000,
      data: {
        url_video: 'https://videocdn.cdnpk.net/joy/content/video/free/2019-01/large_preview/190111_04_TaksinBridge_Drone_02.mp4?filename=456055_bangkok_thailand_asia_3840x2160.mp4',
        image_video: this.imageSrc,
      },
      styleClass: 'video-modal'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    })
  }

  save(): void {
    this.ref.close({ file: this.storyFile, storyName: this.storyName });
  }

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
    this.showFile = true;
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
    this.showFile = true;
  }
  private setMessage(message: string, type: string): void {
    this.alertsService.openToast(type, message);
    this.isLoading = false;
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}



