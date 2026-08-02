import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { RouterOutlet } from '@angular/router';
import { Component } from '@angular/core';

@Component({
  selector: 'app-errors',
  standalone: true,
  imports: [RouterOutlet],
  templateUrl: './errors.component.html',
  styleUrls: ['./errors.component.scss']
})
export class ErrorsComponent {

  constructor(
    private localizationLanguageService: LocalizationLanguageService
  ) { 
    // localizationLanguageService.updatePathAccordingLang();
  }

}
