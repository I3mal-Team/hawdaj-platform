import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { highlightsStatisticsAr, highlightsStatisticsEn, highlightsStatisticsRu, highlightsStatisticsZh } from '../../store/homePage';

@Component({
  selector: 'app-highlights-statistics-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './highlights-statistics-v2.component.html',
  styleUrls: ['./highlights-statistics-v2.component.scss']
})
export class HighlightsStatisticsV2Component {
  currentLanguage: string = '';
  statistics: any = [];
  constructor(private publicService: PublicService) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    this.statistics = this.currentLanguage == 'ar' ? highlightsStatisticsAr: this.currentLanguage == 'ru' ? highlightsStatisticsRu : this.currentLanguage == 'zh' ? highlightsStatisticsZh : highlightsStatisticsEn

  }
}
