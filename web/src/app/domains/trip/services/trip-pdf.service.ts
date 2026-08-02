import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { ITripDayData } from 'src/app/shared/components/trip-day-detail/trip-day-detail.interface';
import { ITripHeaderConfig } from 'src/app/shared/components/trip-header/trip-header.interface';
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';

@Injectable({ providedIn: 'root' })
export class TripPdfService {
    constructor(
        @Inject(PLATFORM_ID) private platformId: Object,
        private alertsService: AlertsService,
        private publicService: PublicService,
    ) { }

    openTripPlanPdf(
        header: ITripHeaderConfig,
        days: ITripDayData[],
        language: string
    ): void {
        if (!isPlatformBrowser(this.platformId)) return;
        if (!days || !days.length) return;

        const html = this.buildTripPdfHtml(header, days, language);
        const blob = new Blob([html], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const printWindow = window.open(url, '_blank', 'noopener,noreferrer');
        if (!printWindow) {
            URL.revokeObjectURL(url);
            return;
        }
        printWindow.onload = () => {
            printWindow.focus();
            URL.revokeObjectURL(url);
        };
    }

    private buildTripPdfHtml(header: ITripHeaderConfig, days: ITripDayData[], language: string): string {
        const daySections = days.map(day => `
      <section class="day">
        <div class="day__header">
          <div>
            <p class="day__number">${this.publicService.translateTextFromJson('saveTrip.day')} ${day.dayNumber}</p>
          </div>
          <div class="day__meta">
            <p>${day.date || ''}</p>
            <p>${day.regionName || ''}</p>
            <p>${day.regionDescription || ''}</p>
          </div>
        </div>
        ${['morning', 'evening'].map(period => {
            const periodData = (day as any)[period];
            if (!periodData) return '';

            const periodTitle = period === 'morning'
                ? this.publicService.translateTextFromJson('saveTrip.morning')
                : this.publicService.translateTextFromJson('saveTrip.evening');

            return `
            <div class="period">
              <h3>${periodTitle}</h3>
              <p class="period__description">${periodData.description || ''}</p>
              <ul class="places">
                ${periodData.places.map((place: any) => `
                  <li class="place">
                    <div class="place__info">
                      <strong>${place.title}</strong>
                      <p>${place.description || ''}</p>
                    </div>
                    <span>${place.location || this.publicService.translateTextFromJson('titles.saudiArabia')}</span>
                  </li>
                `).join('')}
              </ul>
            </div>
          `;
        }).join('')}
      </section>
    `).join('');

        return `
      <!DOCTYPE html>
      <html lang="${language}">
        <head>
          <meta charset="utf-8" />
          <title>${header.title}</title>
          <style>
            body {
              font-family: 'Almarai', 'Helvetica Neue', Arial, sans-serif;
              margin: 0;
              padding: 2rem;
              background: #f6f4fb;
              color: #1d2939;
                  direction: rtl;
            }
            header {
              background: linear-gradient(135deg, #7939a7, #b86dd9);
              color: #fff;
              padding: 1.5rem;
              border-radius: 1rem;
              margin-bottom: 2rem;
            }
            header h1 { margin: 0; font-size: 1.75rem; }
            header p { margin: 0.25rem 0 0; }
            .day {
              background: #fff;
              border-radius: 1rem;
              padding: 1.5rem;
              margin-bottom: 1.5rem;
              box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            }
            .day__header {
              display: flex;
              justify-content: space-between;
              flex-wrap: wrap;
              flex-direction:column;
              gap: 1rem;
              border-bottom: 1px solid #eee;
              padding-bottom: 1rem;
              margin-bottom: 1rem;
            }
            .day__number {
              text-transform: uppercase;
              letter-spacing: 0.1em;
              color: #8064a2;
              margin: 0;
            }
            .day__header h2 {
              margin: 0.25rem 0 0;
              font-size: 1.5rem;
              color: #101828;
            }
            .day__meta {
              text-align: right;
              color: #475467;
              font-size: 0.95rem;
            }
            .period {
              margin-bottom: 1.5rem;
            }
            .period h3 {
              margin: 0 0 0.25rem;
              color: #7939a7;
            }
            .period__description {
              margin: 0 0 1rem;
              color: #475467;
            }
            .places {
              list-style: none;
              padding: 0;
              margin: 0;
              display: flex;
              flex-direction: column;
              gap: 0.75rem;
            }
            .place {
              padding: 1rem;
              border: 1px solid #eee;
              border-radius: 0.75rem;
              display: flex;
              justify-content: space-between;
              gap: 1rem;
            }
            .place__info {
              max-width: 70%;
            }
            .place strong {
              color: #101828;
            }
            .place span {
              color: #475467;
              font-size: 0.9rem;
              white-space: nowrap;
            }
          </style>
        </head>
        <body>
          <header>
            <h1>${header.title}</h1>
            <p>${header.dateRange}</p>
          </header>
          ${daySections}
          <script>
            window.onload = () => {
              window.focus();
              window.print();
            };
          </script>
        </body>
      </html>
    `;
    }
}

