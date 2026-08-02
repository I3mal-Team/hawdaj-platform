import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideTabsComponent } from './tour-guide-tabs.component';

describe('TourGuideTabsComponent', () => {
  let component: TourGuideTabsComponent;
  let fixture: ComponentFixture<TourGuideTabsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideTabsComponent]
    });
    fixture = TestBed.createComponent(TourGuideTabsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
