import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { DOCUMENT, isPlatformBrowser } from '@angular/common';
import { Meta, Title } from '@angular/platform-browser';
import { PublicService } from './public.service';
import { applicationsListTags, defaultTags, eventsListTags, HomeTags, placesListTags, restaurantsTags, storesListTags, storiesListTags, tourGuidesListTags } from 'src/app/services/meta-tags-pages';
import { normalizePublicUrlForSeo, toAbsoluteShareImageUrl } from '../utils/share-preview-image.util';
import { stripHtmlAndClamp } from 'src/app/Common/functions/html.util';

@Injectable({
  providedIn: 'root'
})
export class MetadataService {

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    @Inject(DOCUMENT) private document: Document,
    private publicService: PublicService,
    private titleService: Title,
    private metaService: Meta
  ) { }

  updateTitle(title: string): void {
    this.titleService.setTitle(title);
  }

  getTitle(): string {
    return this.titleService.getTitle();
  }

  private createTagObject(tag: { name?: string, property?: string, content: string, scheme?: string }): any {
    const newTag: any = tag.name ? { name: tag.name, content: tag.content } : { property: tag.property, content: tag.content };
    if (tag.scheme) {
      newTag['scheme'] = tag.scheme;
    }
    return newTag;
  }

  addMetaTagsName(metaTags: { name: string, content: string, scheme?: string }[]): void {
    this.metaService.addTags(metaTags.map(tag => this.createTagObject(tag)));
  }

  addMetaTagsProperty(metaTags: { property: string, content: string, scheme?: string }[]): void {
    this.metaService.addTags(metaTags.map(tag => this.createTagObject(tag)));
  }

  updateMetaTagsName(metaTags: { name: string, content: string, scheme?: string }[]): void {
    metaTags.forEach(tag => this.metaService.updateTag(this.createTagObject(tag)));
  }

  updateMetaTagsProperty(metaTags: { property: string, content: string, scheme?: string }[]): void {
    metaTags.forEach(tag => this.metaService.updateTag(this.createTagObject(tag)));
  }

  /**
   * Sets og:image, og:image:secure_url, twitter:card, and twitter:image with an absolute URL
   * (required for WhatsApp, Facebook, Telegram link previews).
   */
  setSharePreviewImage(rawUrl?: string | null): void {
    const absolute = toAbsoluteShareImageUrl(rawUrl);
    this.updateMetaTagsProperty([
      { property: 'og:image', content: absolute },
      { property: 'og:image:secure_url', content: absolute },
    ]);
    this.updateMetaTagsName([
      { name: 'twitter:card', content: 'summary_large_image' },
      { name: 'twitter:image', content: absolute },
    ]);
  }

  /**
   * Title, description, og:* and twitter:* for content detail pages.
   * Call after entity data exists (including during SSR once HTTP has resolved).
   */
  applyDetailPageShareMeta(params: {
    title?: string | null;
    description?: string | null;
    pageUrl: string;
    imageUrl?: string | null;
    ogType?: string;
  }): void {
    const rawTitle = params.title != null ? String(params.title).trim() : '';
    const title = rawTitle || 'Hawdaj';
    // Descriptions are stored as rich-text, so strip the markup before it lands in a meta tag.
    const rawDesc = stripHtmlAndClamp(params.description);
    const description = rawDesc || title;
    const ogType = (params.ogType && String(params.ogType).trim()) || 'website';
    const pageUrl = normalizePublicUrlForSeo(params.pageUrl);

    this.updateTitle(title);
    this.updateMetaTagsName([
      { name: 'title', content: title },
      { name: 'description', content: description },
      { name: 'twitter:title', content: title },
      { name: 'twitter:description', content: description },
    ]);
    this.updateMetaTagsProperty([
      { property: 'og:type', content: ogType },
      { property: 'og:url', content: pageUrl },
      { property: 'og:title', content: title },
      { property: 'og:description', content: description },
    ]);
    this.setSharePreviewImage(params.imageUrl);
  }

  removeMetaTagByName(name: string): void {
    this.metaService.removeTag(`name='${name}'`);
  }

  removeMetaTagByProperty(property: string): void {
    this.metaService.removeTag(`property='${property}'`);
  }

  getMetaTagsName(): any {
    return this.metaService.getTags('name');
  }

  getMetaTagsProperty(): any {
    return this.metaService.getTags('property');
  }

  // Update Global Meta According Current Language
  updateMetaAccordingCurrentLanguage(type?: string | null) {
    let metaTags: any;
    if (type && type == 'homePage') {
      metaTags = HomeTags;
    } else if (type && type == 'placesList') {
      metaTags = placesListTags;
    } else if (type && type == 'eventsList') {
      metaTags = eventsListTags;
    }
    else if (type && type == 'appsList') {
      metaTags = applicationsListTags;
    }
    else if (type && type == 'tourGuides') {
      metaTags = tourGuidesListTags;
    }
    else if (type && type == 'storesList') {
      metaTags = storesListTags;
    }
    else if (type && type == 'storiesList') {
      metaTags = storiesListTags;
    }
    else if (type && type == 'restauranstsList') {
      metaTags = restaurantsTags;
    }
    else {
      metaTags = defaultTags;
    }
    const { title, description, hrefLang, href, canonical } = metaTags[this.publicService.getCurrentLanguage()] || metaTags['en'];
    const seoHref = normalizePublicUrlForSeo(href);
    const seoCanonical = normalizePublicUrlForSeo(canonical);
    this.updateTitle(title);
    this.updateMetaTagsName([
      { name: 'title', content: title },
      { name: 'description', content: description },
    ]);
    this.updateMetaTagsProperty([
      { property: 'og:url', content: seoHref },
      // { property: 'og:url', content: `${environment.publicUrl}/${this.publicService.getCurrentLanguage()}/` },
      { property: 'og:title', content: title },
      { property: 'og:description', content: description },
    ]);
    this.setSharePreviewImage(null);
    this.updateLinkRelAlternate(hrefLang, seoHref);
    this.updateCanonicalLink(seoCanonical);  // Update canonical link
  }
  updateLinkRelAlternate(hreflang: string, href: string): void {
    // Ensure this only runs in the browser environment
    if (isPlatformBrowser(this.platformId)) {
      let link: HTMLLinkElement | null = this.document.querySelector(`link[rel='alternate'][hreflang='${hreflang}']`);
      if (!link) {
        link = this.document.createElement('link');
        link.setAttribute('rel', 'alternate');
        link.setAttribute('hreflang', hreflang);
        this.document.head.appendChild(link);
      }
      link.setAttribute('href', href);
    }
  }
  updateCanonicalLink(href: string): void {
    // Ensure this only runs in the browser environment
    if (isPlatformBrowser(this.platformId)) {
      let link: HTMLLinkElement | null = this.document.querySelector('link[rel="canonical"]');
      if (!link) {
        link = this.document.createElement('link');
        link.setAttribute('rel', 'canonical');
        this.document.head.appendChild(link);
      }
      link.setAttribute('href', href);
    }
  }


}
