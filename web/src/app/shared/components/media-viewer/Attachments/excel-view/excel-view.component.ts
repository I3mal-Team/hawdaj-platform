import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  selector: 'app-excel-view',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './excel-view.component.html',
  styleUrls: ['./excel-view.component.scss']
})
export class ExcelViewComponent {
  @Input() excel: any;

  downloadFile(filePath: string) {
    const fullPath = filePath;
    window.open(fullPath, '_blank');
  }
}
