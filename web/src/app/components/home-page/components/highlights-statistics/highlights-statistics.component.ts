import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { ChangeDetectionStrategy, Component } from '@angular/core';
import { highlightsStatisticsAr, highlightsStatisticsEn, highlightsStatisticsRu, highlightsStatisticsZh } from '../../store/homePage';

@Component({
  selector: 'highlights-statistics',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './highlights-statistics.component.html',
  styleUrls: ['./highlights-statistics.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,

})
export class HighlightsStatisticsComponent {
  currentLanguage: string = '';
  statistics: any = [];
  constructor(private publicService: PublicService) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    this.statistics = this.currentLanguage == 'ar' ? highlightsStatisticsAr : this.currentLanguage == 'ru' ? highlightsStatisticsRu : this.currentLanguage == 'zh' ? highlightsStatisticsZh : highlightsStatisticsEn

  }
}
