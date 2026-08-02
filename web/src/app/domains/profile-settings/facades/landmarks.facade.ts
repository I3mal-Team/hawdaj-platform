import { inject, Injectable, signal, computed } from '@angular/core';
import { MessageService } from 'primeng/api';
import { TranslateService } from '@ngx-translate/core';
import { ProfileSettingsService } from '../services';
import { ILandmarksResponseDto, ICreateLandmarkRequestDto, ICreateLandmarkResponseDto } from '../dtos';
import { ILandmarkItem } from '../interfaces';
import { LANDMARKS_MOCK_RESPONSE } from '../data/profile-settings-mockup.data';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Injectable({ providedIn: 'root' })
export class LandmarksFacade {
  private readonly profileSettingsService = inject(ProfileSettingsService);
  private readonly messageService = inject(MessageService);
  private readonly translate = inject(TranslateService);
  private readonly publicService = inject(PublicService);
  private hasAttemptedLoad = false;

  readonly landmarksList = signal<ILandmarkItem[]>([]);
  readonly isLoadingLandmarks = signal(false);
  readonly landmarksError = signal<string | null>(null);
  readonly currentPage = signal(1);
  readonly perPage = signal(10);
  readonly total = signal(0);
  readonly lastPage = signal(1);
  readonly isCreatingLandmark = signal(false);

  readonly hasNoData = computed(() => !this.isLoadingLandmarks() && this.landmarksList().length === 0);
  readonly hasError = computed(() => !!this.landmarksError());

  loadLandmarks(page = 1, perPage = 10, force = false): void {
    if (this.isLoadingLandmarks() && !force) return;

    if (!force) {
      if (this.landmarksList().length > 0 && !this.hasAttemptedLoad) return;
    }

    this.hasAttemptedLoad = true;
    this.isLoadingLandmarks.set(true);
    this.landmarksError.set(null);
    this.currentPage.set(page);
    this.perPage.set(perPage);

    this.profileSettingsService.getMyLandmarks(page, perPage).subscribe({
      next: (response) => {
        if (response?.code === 200 && response.data) {
          this.applyLandmarksResponse(response);
        } else {
          this.applyLandmarksFallback(response?.message);
        }
        this.isLoadingLandmarks.set(false);
      },
      error: (err) => {
        this.applyLandmarksFallback(err?.error?.message || err?.message);
        this.isLoadingLandmarks.set(false);
      }
    });
  }

  retryLandmarks(): void {
    this.loadLandmarks(this.currentPage(), this.perPage(), true);
  }

  private applyLandmarksResponse(response: ILandmarksResponseDto): void {
    this.landmarksList.set(response.data.items ?? []);
    this.total.set(response.data.total ?? 0);
    this.lastPage.set(response.data.last_page ?? 1);
    this.landmarksError.set(null);
  }

  private applyLandmarksFallback(message?: string | null): void {
    const fallbackMessage = message || this.translate.instant('general.errorOccur');
    this.landmarksError.set(fallbackMessage);
    this.messageService.add({
      severity: 'error',
      summary: this.translate.instant('general.error'),
      detail: fallbackMessage
    });

    const mock = LANDMARKS_MOCK_RESPONSE;
    if (mock?.data) {
      this.landmarksList.set(mock.data.items ?? []);
      this.total.set(mock.data.total ?? 0);
      this.lastPage.set(mock.data.last_page ?? 1);
    }
  }

  createLandmark(data: ICreateLandmarkRequestDto): void {
    if (this.isCreatingLandmark()) return;

    this.isCreatingLandmark.set(true);

    this.profileSettingsService.createLandmark(data).subscribe({
      next: (response: ICreateLandmarkResponseDto) => {
        if (response?.code === 200) {
          // Reload landmarks after successful creation
          this.loadLandmarks(1, this.perPage(), true);
        } else {
          this.messageService.add({
            severity: 'error',
            summary: this.publicService.translateTextFromJson('general.error'),
            detail: response.message || this.translate.instant('landmarks.createError')
          });
        }
        this.isCreatingLandmark.set(false);
      },
      error: (err) => {
        const errorMessage = err || err?.message || this.translate.instant('landmarks.createError');
        this.messageService.add({
          severity: 'error',
          summary: this.publicService.translateTextFromJson('general.error'),
          detail: errorMessage
        });
        this.isCreatingLandmark.set(false);
      }
    });
  }
}

