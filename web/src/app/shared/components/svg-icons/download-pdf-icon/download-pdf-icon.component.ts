/* ---------- Angular Core ---------- */
import { Component, Input, ChangeDetectionStrategy } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-download-pdf-icon',
  standalone: true,
  imports: [CommonModule],
  template: `
    <svg
      [attr.width]="size"
      [attr.height]="size"
      [attr.viewBox]="'0 0 ' + size + ' ' + size"
      [attr.fill]="fill"
      [attr.aria-label]="ariaLabel"
      [attr.role]="'img'"
      xmlns="http://www.w3.org/2000/svg">
      <title>{{ ariaLabel }}</title>
      <path
        d="M9 11V17L11 15"
        [attr.stroke]="color"
        [attr.stroke-width]="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"/>
      <path
        d="M9 17L7 15"
        [attr.stroke]="color"
        [attr.stroke-width]="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"/>
      <path
        d="M22 10V15C22 20 20 22 15 22H9C4 22 2 20 2 15V9C2 4 4 2 9 2H14"
        [attr.stroke]="color"
        [attr.stroke-width]="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"/>
      <path
        d="M22 10H18C15 10 14 9 14 6V2L22 10Z"
        [attr.stroke]="color"
        [attr.stroke-width]="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"/>
    </svg>
  `,
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class DownloadPdfIconComponent {
  @Input() size: number = 24;
  @Input() color: string = '#7939A7';
  @Input() strokeWidth: number = 1.5;
  @Input() fill: string = 'none';
  @Input() ariaLabel: string = 'Download PDF';
}



