/* ---------- Angular Core ---------- */
import {
  ChangeDetectionStrategy,
  Component,
  EventEmitter,
  Input,
  Output,
  signal,
  computed
} from '@angular/core';
import { CommonModule } from '@angular/common';

/* ---------- Third-party Modules ---------- */
import { TranslateModule } from '@ngx-translate/core';

/* ---------- Interfaces ---------- */
import { ITabItem, ITabListConfig } from './tab-list.interface';

/* ---------- Shared Components ---------- */
import { SvgIconComponent } from '../svg-icon';

@Component({
  selector: 'app-tab-list',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    SvgIconComponent
  ],
  templateUrl: './tab-list.component.html',
  styleUrls: ['./tab-list.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TabListComponent {
  /** ---------- Signals ---------- */
  readonly tabs = signal<ITabItem[]>([]);
  readonly config = signal<ITabListConfig>({
    isMultiple: false,
    showIcons: true,
    customClass: ''
  });

  /** ---------- Computed Signals ---------- */
  readonly activeTabs = computed(() =>
    this.tabs().filter(tab => tab.active)
  );

  /** ---------- Inputs ---------- */
  @Input({ required: true }) set tabItems(value: ITabItem[]) {
    this.tabs.set(value);
  }

  @Input() set tabConfig(value: ITabListConfig) {
    this.config.set({ ...this.config(), ...value });
  }

  /** ---------- Outputs ---------- */
  @Output() readonly tabChange = new EventEmitter<ITabItem | ITabItem[]>();
  @Output() readonly tabClick = new EventEmitter<ITabItem>();

  /** ---------- Methods ---------- */
  protected onTabClick(clickedTab: ITabItem): void {
    if (clickedTab.disabled) {
      return;
    }

    const currentTabs = this.tabs();
    const isMultiple = this.config().isMultiple;

    if (isMultiple) {
      // Special handling for "all" tab
      if (clickedTab.id === 'all') {
        // If "all" is clicked, toggle it and deactivate all others
        const isAllActive = clickedTab.active;
        const updatedTabs = currentTabs.map(tab => {
          if (tab.id === 'all') {
            return { ...tab, active: !isAllActive };
          }
          return { ...tab, active: false };
        });
        this.tabs.set(updatedTabs);
      } else {
        // For other tabs, toggle them and deactivate "all" if it exists
        const updatedTabs = currentTabs.map(tab => {
          if (tab.id === 'all') {
            return { ...tab, active: false };
          }
          if (tab.id === clickedTab.id) {
            return { ...tab, active: !tab.active };
          }
          return tab;
        });
        this.tabs.set(updatedTabs);
      }
      this.tabChange.emit(this.activeTabs());
    } else {
      // Single selection: activate only the clicked tab
      const updatedTabs = currentTabs.map(tab => ({
        ...tab,
        active: tab.id === clickedTab.id
      }));
      this.tabs.set(updatedTabs);
      this.tabChange.emit(clickedTab);
    }

    this.tabClick.emit(clickedTab);
  }

  /** Track by function for performance */
  protected trackByTabId(index: number, tab: ITabItem): string {
    return tab.id;
  }
}


