import { Component, EventEmitter, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-map-information',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './map-information.component.html',
  styleUrls: ['./map-information.component.scss']
})
export class MapInformationComponent {
  @Input() title: string;
  @Input() thumbnail: string;
  @Output() locationClick = new EventEmitter<void>();

  handleClick() {
    this.locationClick.emit();
  }
}
