import { environment } from 'src/environments/environment';

/**
 * Forces https:// for hawdaj.net (and www) so og:url / canonical match production crawlers.
 * Idempotent if already https.
 */
export function normalizePublicUrlForSeo(url: string): string {
  if (url == null || typeof url !== 'string') {
    return '';
  }
  return url.replace(/^http:\/\/(www\.)?hawdaj\.net/i, 'https://$1hawdaj.net');
}

/**
 * Default image used in og:image / Twitter cards when no item image exists.
 * Must be an absolute https URL for WhatsApp, Facebook, Telegram crawlers.
 */
export function getDefaultSocialShareImageUrl(): string {
  const base = (environment.imageBaseUrl || '').replace(/\/$/, '');
  return `${base}/front_assets/imgs/logo.svg`;
}

/**
 * Converts API or asset paths to an absolute URL suitable for og:image and twitter:image.
 */
export function toAbsoluteShareImageUrl(raw: string | null | undefined): string {
  const fallback = getDefaultSocialShareImageUrl();
  if (raw == null) {
    return fallback;
  }
  const u = String(raw).trim();
  if (u === '') {
    return fallback;
  }
  if (/^https?:\/\//i.test(u)) {
    return u;
  }
  if (u.startsWith('//')) {
    return `https:${u}`;
  }

  const baseMedia = (environment.imageBaseUrl || '').replace(/\/$/, '');
  const baseSite = normalizePublicUrlForSeo((environment.publicUrl || '').replace(/\/$/, ''));

  if (u.startsWith('/')) {
    if (u.startsWith('/assets')) {
      return baseSite ? `${baseSite}${u}` : (baseMedia ? `${baseMedia}${u}` : fallback);
    }
    return baseMedia ? `${baseMedia}${u}` : fallback;
  }

  if (/^assets\//i.test(u)) {
    return baseSite ? `${baseSite}/${u}` : (baseMedia ? `${baseMedia}/${u}` : fallback);
  }

  return baseMedia ? `${baseMedia}/${u.replace(/^\/+/, '')}` : fallback;
}
