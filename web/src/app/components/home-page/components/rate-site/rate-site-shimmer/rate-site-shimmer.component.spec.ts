import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RateSiteShimmerComponent } from './rate-site-shimmer.component';

describe('RateSiteShimmerComponent', () => {
  let component: RateSiteShimmerComponent;
  let fixture: ComponentFixture<RateSiteShimmerComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [RateSiteShimmerComponent]
    });
    fixture = TestBed.createComponent(RateSiteShimmerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
