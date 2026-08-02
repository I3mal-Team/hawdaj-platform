import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OfferDetailsModalComponent } from './offer-details-modal.component';

describe('OfferDetailsModalComponent', () => {
  let component: OfferDetailsModalComponent;
  let fixture: ComponentFixture<OfferDetailsModalComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [OfferDetailsModalComponent]
    });
    fixture = TestBed.createComponent(OfferDetailsModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
