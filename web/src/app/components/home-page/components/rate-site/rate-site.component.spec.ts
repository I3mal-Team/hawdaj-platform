import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RateSiteComponent } from './rate-site.component';

describe('RateSiteComponent', () => {
  let component: RateSiteComponent;
  let fixture: ComponentFixture<RateSiteComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [RateSiteComponent]
    });
    fixture = TestBed.createComponent(RateSiteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
