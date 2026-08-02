import { ChangeDetectionStrategy, Component, Input, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';

export interface ErrorStateConfig {
  imageUrl?: string | null;
  title?: string;
  message?: string;
  retryLabel?: string;
  onRetry?: () => void;
  backgroundColor?: string;
  gap?: string;
  imgWidth?: string;
}

@Component({
  selector: 'app-error-state-card',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './error-state-card.component.html',
  styleUrls: ['./error-state-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class ErrorStateCardComponent {
  @Input() type?: 'small' | 'large';
  @Input({ required: true }) config!: ErrorStateConfig;
  get backgroundColor(): string {
    return this.config.backgroundColor || '#fff3f3';
  }

  get gap(): string {
    return this.config.gap || '1rem';
  }

  get imgWidth(): string | undefined {
    return this.config.imgWidth;
  }

  get retryLabel(): string {
    return this.config.retryLabel || 'general.retry';
  }

  protected retry(): void {
    this.config.onRetry?.();
  }
}
