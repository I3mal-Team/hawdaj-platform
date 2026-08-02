import { Component, OnInit, EventEmitter, Output, Input, PLATFORM_ID, Inject } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { SkeletonModule } from 'primeng/skeleton';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { ImageModule } from 'primeng/image';
import { ToastModule } from 'primeng/toast';
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  standalone: true,
  imports: [CommonModule, TranslateModule, SkeletonModule, ImageModule, ToastModule],
  selector: 'app-file-upload',
  templateUrl: './file-upload.component.html',
  styleUrls: ['./file-upload.component.scss']
})
export class FileUploadComponent implements OnInit {

  @Input() showFile: boolean = false;
  @Input() isSupportAll: boolean = true;
  @Input() isEdit: boolean = false;
  @Input() showPreview: boolean = false;
  @Input() accept: string = 'image/*';
  @Input() imageSrc: string = '';
  @Input() supports: string = 'PNG, JPG, GIF up to 10MB';

  @Output() uploadHandler: EventEmitter<any> = new EventEmitter();

  dragging: boolean = false;
  loaded: boolean = false;
  isLoading: boolean = false;

  name: string = '';
  imageSize: any;
  type: string = '';

  isBrowser: boolean;

  private allowedTypes: string[] = [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/svg+xml'
  ];

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    private publicService: PublicService
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngOnInit(): void {
    if (this.isEdit && this.isBrowser) {
      this.showFile = true;

      const urlParts: any = this.imageSrc.split('/');
      this.name = urlParts[urlParts.length - 1];
      this.type = 'image';
    }
  }

  // ==================================
  //           FILE CHANGE
  // ==================================
  handleInputChange(e: any): void {
    if (!this.isBrowser) return;

    const file = e.dataTransfer ? e.dataTransfer.files[0] : e.target.files[0];

    // ✔ أهم سطر: يمنع عمل أي حاجة لو المستخدم ضغط Cancel
    if (!file) return;

    // Validate type
    if (!this.isFileTypeAllowed(file)) {
      this.alertsService.openToast(
        'error',
        this.publicService.translateTextFromJson('general.invalidFileType')
      );
      return;
    }

    this.uploadHandler.emit({ file });

    this.formatSizeUnits(file.size);
    this.name = file.name;
    this.type = file.type;

    this.isLoading = true;

    const reader = new FileReader();
    reader.onload = (ev: any) => {
      this.imageSrc = ev.target.result;
      this.showFile = true;
    };
    reader.readAsDataURL(file);

    setTimeout(() => {
      this.isLoading = false;
    }, 800);
  }


  // ==================================
  //           DRAG AND DROP
  // ==================================
  handleDragEnter(): void {
    if (this.isBrowser) {
      this.dragging = true;
      this.showFile = true;
    }
  }

  handleDragLeave(): void {
    if (this.isBrowser) {
      this.dragging = false;
    }
  }

  handleDrop(e: any): void {
    if (this.isBrowser) {
      e.preventDefault();
      this.dragging = false;

      // مهم: drop قد يكون مفيهوش ملفات
      if (!e.dataTransfer.files.length) return;

      this.handleInputChange(e);
      this.showFile = true;
    }
  }

  handleDragOver(event: any): void {
    event.preventDefault();
  }


  // ==================================
  //        READER & ERRORS
  // ==================================
  _handleReaderLoaded(e: any): void {
    if (this.isBrowser) {
      this.isEdit = false;
      this.showFile = true;
      this.imageSrc = e.target.result;
    }
  }

  onImageError(imageSrc: any): void {
    imageSrc = 'assets/images/not-found/no-img.webp';
  }


  // ==================================
  //              REMOVE
  // ==================================
  remove(): void {
    this.showFile = false;
    this.imageSrc = '';
    this.name = '';
    this.type = '';
    this.imageSize = '';
  }


  // ==================================
  //            VALIDATION
  // ==================================
  private isFileTypeAllowed(file: File): boolean {
    return this.allowedTypes.includes(file.type);
  }

  formatSizeUnits(size: any): void {
    if (size >= 1073741824) size = (size / 1073741824).toFixed(2) + " GB";
    else if (size >= 1048576) size = (size / 1048576).toFixed(2) + " MB";
    else if (size >= 1024) size = (size / 1024).toFixed(2) + " KB";
    else if (size > 1) size = size + " bytes";
    else if (size == 1) size = size + " byte";
    else size = "0 bytes";

    this.imageSize = size;
  }
}
