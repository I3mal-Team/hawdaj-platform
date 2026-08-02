import { Component, Input } from '@angular/core';
import { DynamicDialogConfig } from 'primeng/dynamicdialog';

@Component({
  selector: 'app-coming-soon-modal',
  templateUrl: './coming-soon-modal.component.html',
  styleUrls: ['./coming-soon-modal.component.scss']
})
export class ComingSoonModalComponent {
  @Input() image: string;
  constructor(public config: DynamicDialogConfig) {
    this.image = this.config.data?.image;
  }
}
