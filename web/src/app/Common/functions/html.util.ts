const NAMED_ENTITIES: Record<string, string> = {
  amp: '&',
  lt: '<',
  gt: '>',
  quot: '"',
  apos: "'",
  nbsp: ' ',
  hellip: '…',
  mdash: '—',
  ndash: '–',
  laquo: '«',
  raquo: '»',
};

/**
 * Decodes the handful of entities a rich-text editor actually emits.
 * Regex-based on purpose: this runs during SSR where there is no DOM.
 */
function decodeEntities(value: string): string {
  return value
    .replace(/&#x([0-9a-f]+);/gi, (_, hex) => String.fromCodePoint(parseInt(hex, 16)))
    .replace(/&#(\d+);/g, (_, dec) => String.fromCodePoint(parseInt(dec, 10)))
    .replace(/&([a-z]+);/gi, (match, name) => NAMED_ENTITIES[name.toLowerCase()] ?? match);
}

/**
 * Turns stored rich-text into plain text for places that cannot render markup:
 * cards, tooltips, `[title]` attributes and `<meta>` tags.
 * Block-level tags become spaces so words don't run together.
 */
export function stripHtml(value: string | null | undefined): string {
  if (value == null) return '';

  return decodeEntities(
    String(value)
      .replace(/<(script|style)[^>]*>[\s\S]*?<\/\1>/gi, '')
      .replace(/<br\s*\/?>/gi, ' ')
      .replace(/<\/(p|div|li|h[1-6]|tr)>/gi, ' ')
      .replace(/<[^>]+>/g, '')
  )
    .replace(/\s+/g, ' ')
    .trim();
}

/**
 * Plain text clamped to `maxLength`, cut on a word boundary.
 * Meta descriptions get truncated by crawlers around 160 chars anyway.
 */
export function stripHtmlAndClamp(value: string | null | undefined, maxLength = 200): string {
  const text = stripHtml(value);
  if (text.length <= maxLength) return text;

  const cut = text.slice(0, maxLength);
  const lastSpace = cut.lastIndexOf(' ');
  return `${(lastSpace > maxLength * 0.6 ? cut.slice(0, lastSpace) : cut).trimEnd()}…`;
}
