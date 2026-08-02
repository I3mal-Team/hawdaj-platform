import { inject, Injectable, signal, computed } from '@angular/core';
import { MessageService } from 'primeng/api';
import { TranslateService } from '@ngx-translate/core';
import { ProfileSettingsService } from '../services';
import { IFavoritesResponseDto } from '../dtos';
import { IFavoriteItem, FavoritableType } from '../interfaces';
import { FAVORITES_MOCK_RESPONSE } from '../data/profile-settings-mockup.data';

@Injectable({ providedIn: 'root' })
export class FavoritesFacade {
  private readonly profileSettingsService = inject(ProfileSettingsService);
  private readonly messageService = inject(MessageService);
  private readonly translate = inject(TranslateService);
  private hasAttemptedLoad = false;

  readonly favoritesList = signal<IFavoriteItem[]>([]);
  readonly isLoadingFavorites = signal(false);
  readonly favoritesError = signal<string | null>(null);
  readonly currentPage = signal(1);
  readonly perPage = signal(10);
  readonly total = signal(0);
  readonly lastPage = signal(1);

  readonly hasNoData = computed(() => !this.isLoadingFavorites() && this.favoritesList().length === 0);
  readonly hasError = computed(() => !!this.favoritesError());

  // Grouped favorites by type
  readonly placesFavorites = computed(() =>
    this.favoritesList().filter((item) => item.favoritable_type === 'place')
  );
  readonly storesFavorites = computed(() =>
    this.favoritesList().filter((item) => item.favoritable_type === 'store')
  );
  readonly eventsFavorites = computed(() =>
    this.favoritesList().filter((item) => item.favoritable_type === 'event')
  );
  readonly zadsFavorites = computed(() =>
    this.favoritesList().filter((item) => item.favoritable_type === 'zad_elgadel')
  );

  loadFavorites(page = 1, perPage = 10, force = false): void {
    if (this.isLoadingFavorites() && !force) return;

    if (!force) {
      if (this.favoritesList().length > 0 && !this.hasAttemptedLoad) return;
    }

    this.hasAttemptedLoad = true;
    this.isLoadingFavorites.set(true);
    this.favoritesError.set(null);
    this.currentPage.set(page);
    this.perPage.set(perPage);

    this.profileSettingsService.getMyFavorites(page, perPage).subscribe({
      next: (response) => {
        if (response?.code === 200 && response.data) {
          this.applyFavoritesResponse(response);
        } else {
          this.applyFavoritesFallback(response?.message);
        }
        this.isLoadingFavorites.set(false);
      },
      error: (err) => {
        this.applyFavoritesFallback(err?.error?.message || err?.message);
        this.isLoadingFavorites.set(false);
      }
    });
  }

  retryFavorites(): void {
    this.loadFavorites(this.currentPage(), this.perPage(), true);
  }

  private applyFavoritesResponse(response: IFavoritesResponseDto): void {
    this.favoritesList.set(response.data.items ?? []);
    this.total.set(response.data.total ?? 0);
    this.lastPage.set(response.data.last_page ?? 1);
    this.favoritesError.set(null);
  }

  private applyFavoritesFallback(message?: string | null): void {
    const fallbackMessage = message || this.translate.instant('general.errorOccur');
    this.favoritesError.set(fallbackMessage);
    this.messageService.add({
      severity: 'error',
      summary: this.translate.instant('general.error'),
      detail: fallbackMessage
    });

    const mock = FAVORITES_MOCK_RESPONSE;
    if (mock?.data) {
      this.favoritesList.set(mock.data.items ?? []);
      this.total.set(mock.data.total ?? 0);
      this.lastPage.set(mock.data.last_page ?? 1);
    }
  }
}

