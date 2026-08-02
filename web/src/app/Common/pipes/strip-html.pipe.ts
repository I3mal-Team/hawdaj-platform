import { Pipe, PipeTransform } from '@angular/core';

import { stripHtml } from '../functions/html.util';

/**
 * `{{ item?.description | stripHtml }}` — for cards, tooltips and any place
 * that shows a description as plain text.
 */
@Pipe({
  name: 'stripHtml',
  standalone: true,
})
export class StripHtmlPipe implements PipeTransform {
  transform(value: string | null | undefined): string {
    return stripHtml(value);
  }
}
