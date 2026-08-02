import {
  ChangeDetectionStrategy,
  Component,
  EventEmitter,
  Input,
  OnDestroy,
  Output,
  computed,
  signal,
  inject,
  HostListener,
  ElementRef
} from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { PropertyUploadFile } from '../../property-item.model';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon/svg-icon.component';
import { AlertsService } from 'src/app/services/alerts.service';

@Component({
  selector: 'app-property-file-upload',
  standalone: true,
  imports: [CommonModule, SvgIconComponent, TranslateModule],
  templateUrl: './property-file-upload.component.html',
  styleUrls: ['./property-file-upload.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class PropertyFileUploadComponent implements OnDestroy {
  private readonly filesSignal = signal<PropertyUploadFile[]>([]);
  private readonly errorSignal = signal<string | null>(null);
  private readonly alertsService = inject(AlertsService);
  private readonly translateService = inject(TranslateService);
  private readonly elementRef = inject(ElementRef);

  @Input({ required: true }) label = '';
  @Input() descriptionKey = 'properties.upload.supportedTypes';
  @Input() descriptionParams?: Record<string, any>;
  @Input() accept = 'image/png,image/jpeg,image/jpg,image/gif';
  @Input() multiple = false;
  @Input() maxFileSizeMb = 10;

  @Input()
  set files(value: PropertyUploadFile[] | null) {
    this.filesSignal.set(value ? [...value] : []);
  }

  get files(): PropertyUploadFile[] {
    return this.filesSignal();
  }

  @Output() filesChange = new EventEmitter<PropertyUploadFile[]>();

  readonly hasFiles = computed(() => this.filesSignal().length > 0);
  readonly errorMessage = computed(() => this.errorSignal());

  readonly imageFiles = computed(() =>
    this.filesSignal().filter((f) => f.file.type.startsWith('image/'))
  );

  readonly pdfFiles = computed(() =>
    this.filesSignal().filter((f) => f.file.type === 'application/pdf')
  );

  readonly hasImages = computed(() => this.imageFiles().length > 0);
  readonly hasPdfs = computed(() => this.pdfFiles().length > 0);

  ngOnDestroy(): void {
    this.cleanupFiles(this.filesSignal());
  }

  onInputClick(event: Event): void {
    // Prevent form submission when clicking on the file input
    event.stopPropagation();
  }

  onInputKeyDown(event: KeyboardEvent): void {
    // Prevent form submission when pressing Enter on file input
    if (event.key === 'Enter') {
      event.preventDefault();
      event.stopPropagation();
    }
  }

  @HostListener('document:submit', ['$event'])
  onFormSubmit(event: SubmitEvent): void {
    // Prevent form submission when file input dialog is cancelled
    // This happens when user cancels file selection dialog
    const form = event.target as HTMLFormElement;
    const fileInput = this.elementRef.nativeElement.querySelector('input[type="file"]') as HTMLInputElement;

    if (fileInput && form.contains(fileInput)) {
      // Check if the file input was recently interacted with
      const wasRecentlyClicked = fileInput === document.activeElement;
      if (wasRecentlyClicked && (!fileInput.files || fileInput.files.length === 0)) {
        // User cancelled file selection, prevent form submission
        event.preventDefault();
        event.stopPropagation();
      }
    }
  }

  onFileBrowse(event: Event): void {
    event.preventDefault();
    event.stopPropagation();
    const input = event.target as HTMLInputElement;
    this.consumeFiles(input.files);
    input.value = '';
  }

  onDrop(event: DragEvent): void {
    event.preventDefault();
    this.consumeFiles(event.dataTransfer?.files ?? null);
  }

  onDragOver(event: DragEvent): void {
    event.preventDefault();
  }

  onClear(fileId: string): void {
    const remaining: PropertyUploadFile[] = [];
    for (const file of this.filesSignal()) {
      if (file.id === fileId) {
        if (file.previewUrl?.startsWith('blob:')) {
          URL.revokeObjectURL(file.previewUrl);
        }
        continue;
      }
      remaining.push(file);
    }
    this.updateFiles(remaining);
  }

  onPreview(file: PropertyUploadFile): void {
    const previewUrl = file.previewUrl ?? URL.createObjectURL(file.file);
    window.open(previewUrl, '_blank');
    if (!file.previewUrl) {
      this.updateFiles(
        this.filesSignal().map((item) =>
          item.id === file.id ? { ...item, previewUrl } : item
        )
      );
    }
  }

  isImage(file: PropertyUploadFile): boolean {
    return file.file.type.startsWith('image/');
  }

  isPdf(file: PropertyUploadFile): boolean {
    return file.file.type === 'application/pdf';
  }

  getFileSize(file: PropertyUploadFile): { value: string; unit: string } {
    const sizeInMB = file.size / (1024 * 1024);
    const sizeInKB = file.size / 1024;

    if (sizeInMB >= 1) {
      return {
        value: sizeInMB.toFixed(2),
        unit: 'properties.fileSize.mb'
      };
    } else {
      return {
        value: sizeInKB.toFixed(2),
        unit: 'properties.fileSize.kb'
      };
    }
  }

  trackByFileId(_: number, file: PropertyUploadFile): string {
    return file.id;
  }

  private consumeFiles(fileList: FileList | null): void {
    if (!fileList || fileList.length === 0) {
      return;
    }

    const incoming = Array.from(fileList);
    const validFiles = incoming
      .filter((file) => this.isWithinSizeLimit(file))
      .map((file) => this.mapToUploadFile(file));

    if (validFiles.length === 0) {
      return;
    }

    if (!this.multiple && this.filesSignal().length) {
      this.cleanupFiles(this.filesSignal());
    }

    const nextFiles = this.multiple
      ? [...this.filesSignal(), ...validFiles]
      : [validFiles[0]];

    this.errorSignal.set(null);
    this.updateFiles(nextFiles);
  }

  private isWithinSizeLimit(file: File): boolean {
    const isValid = file.size / (1024 * 1024) <= this.maxFileSizeMb;
    if (!isValid) {
      this.errorSignal.set('properties.upload.errors.maxSize');
      this.showErrorToast('properties.upload.errors.maxSize', { size: this.maxFileSizeMb });
    }
    return isValid;
  }

  private mapToUploadFile(file: File): PropertyUploadFile {
    return {
      id: crypto.randomUUID(),
      file,
      name: file.name,
      size: file.size,
      status: 'pending',
      previewUrl: this.createPreviewUrl(file)
    };
  }

  private createPreviewUrl(file: File): string | undefined {
    if (file.type.startsWith('image/')) {
      return URL.createObjectURL(file);
    }
    return undefined;
  }

  private cleanupFiles(files: PropertyUploadFile[]): void {
    files.forEach((file) => {
      if (file.previewUrl?.startsWith('blob:')) {
        URL.revokeObjectURL(file.previewUrl);
      }
    });
  }

  private updateFiles(files: PropertyUploadFile[]): void {
    this.filesSignal.set(files);
    this.filesChange.emit(files);
  }

  private showErrorToast(translationKey: string, params?: Record<string, any>): void {
    const message = this.translateService.instant(translationKey, params);
    this.alertsService.openToast('error', message || translationKey);
  }
}

