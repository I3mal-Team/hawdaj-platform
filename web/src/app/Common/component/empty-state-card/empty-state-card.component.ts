import { ChangeDetectionStrategy, Component, EventEmitter, Input, Output, computed, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

export interface EmptyStateConfig {
  imageUrl?: string | null;
  title?: string;
  message?: string;
  backgroundColor?: string;
  gap?: string;
  hasButton?: boolean;
  buttonText?: string;
  imgWidth?: string;
  isLoading?: boolean;
}

@Component({
  selector: 'app-empty-state-card',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './empty-state-card.component.html',
  styleUrls: ['./empty-state-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class EmptyStateCardComponent {
  @Input() type?: 'small' | 'large';
  @Input({ required: true }) config!: EmptyStateConfig;
  @Input() isLoading?: boolean;
  @Output() action = new EventEmitter<void>();

  // Computed getters to replace optional chaining
  readonly hasImage = computed(() => !!this.config.imageUrl);
  readonly hasTitle = computed(() => !!this.config.title);
  readonly hasMessage = computed(() => !!this.config.message);
  readonly hasButton = computed(() => !!this.config.hasButton);

  readonly backgroundColor = computed(() => this.config.backgroundColor || '#f9f9f9');
  readonly gap = computed(() => this.config.gap || '1rem');

  readonly effectiveImgWidth = computed(() => this.config.imgWidth || 'auto');
  readonly buttonLoading = computed(() => this.isLoading || this.config.isLoading);

  onClick() {
    if (!this.buttonLoading()) {
      this.action.emit();
    }
  }
}
