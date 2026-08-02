import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-save-trip-card',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './save-trip-card.component.html',
  styleUrls: ['./save-trip-card.component.scss']
})
export class SaveTripCardComponent {
  @Input() tripData: any;
}
