import {
  ChangeDetectionStrategy,
  Component,
  Input,
  Output,
  EventEmitter,
  effect,
  inject,
  Signal,
  signal,
  PLATFORM_ID,
  OnInit,
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { ITab } from '../../interfaces';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Component({
  selector: 'app-profile-featured-tabs',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './profile-featured-tabs.component.html',
  styleUrls: ['./profile-featured-tabs.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProfileFeaturedTabsComponent implements OnInit {
  /* ---------- Injected ---------- */
  private readonly publicService = inject(PublicService);
  private readonly platformId = inject(PLATFORM_ID);
  private readonly isBrowser = isPlatformBrowser(this.platformId);

  /* ---------- Signals ---------- */
  private readonly _tabs = signal<ITab[]>([]);
  @Input() selectedTabId: Signal<string | number | null> = signal(null);
  @Input({ required: false }) isLoadingItems: Signal<boolean> = signal(false);
  readonly selectedTab = signal<ITab | null>(null);
  readonly currentLanguage: Signal<string> = signal(this.publicService.getCurrentLanguage?.() ?? 'ar');

  /* ---------- Outputs ---------- */
  @Output() readonly tabSelected = new EventEmitter<ITab>();

  /* ---------- Tabs Input ---------- */
  @Input() set tabs(value: ITab[]) {
    this._tabs.set(value);
  }
  get tabs(): ITab[] {
    return this._tabs();
  }

  constructor() {
    effect(
      () => {
        const currentId = this.selectedTabId();
        const availableTabs = this._tabs();
        if (!availableTabs.length) {
          this.selectedTab.set(null);
          return;
        }

        // Select by current ID
        if (currentId !== null) {
          const tab = availableTabs.find(t => t.id === currentId);
          if (tab) this.selectedTab.set(tab);
        } else if (!this.selectedTab()) {
          this.selectedTab.set(availableTabs[0]);
        } else if (!availableTabs.some(t => t.id === this.selectedTab()?.id)) {
          this.selectedTab.set(null);
        }
      },
      { allowSignalWrites: true }
    );
  }

  ngOnInit(): void {
    const initialTab = this.selectedTab();
    if (initialTab) this.tabSelected.emit(initialTab);
  }

  protected selectTab(tab: ITab): void {
    if (!this.isBrowser || this.isLoadingItems() || this.selectedTab()?.id === tab.id) return;

    this.selectedTab.set(tab);
    this.tabSelected.emit(tab);
  }

  protected getTabTitle(tab: ITab): string {
    return tab.translate?.[this.currentLanguage()] ?? tab.title;
  }

  protected trackByTabId(_index: number, tab: ITab): string | number {
    return tab.id;
  }
}
