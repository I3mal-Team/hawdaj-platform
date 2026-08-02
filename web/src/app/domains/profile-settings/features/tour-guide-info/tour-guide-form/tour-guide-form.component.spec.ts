import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideFormComponent } from './tour-guide-form.component';

describe('TourGuideFormComponent', () => {
  let component: TourGuideFormComponent;
  let fixture: ComponentFixture<TourGuideFormComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideFormComponent]
    });
    fixture = TestBed.createComponent(TourGuideFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
