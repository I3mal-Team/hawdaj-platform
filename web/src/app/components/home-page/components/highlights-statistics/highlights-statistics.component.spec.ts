import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HighlightsStatisticsComponent } from './highlights-statistics.component';

describe('HighlightsStatisticsComponent', () => {
  let component: HighlightsStatisticsComponent;
  let fixture: ComponentFixture<HighlightsStatisticsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HighlightsStatisticsComponent]
    });
    fixture = TestBed.createComponent(HighlightsStatisticsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
