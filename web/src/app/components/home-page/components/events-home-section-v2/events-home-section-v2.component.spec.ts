import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventsHomeSectionV2Component } from './events-home-section-v2.component';

describe('EventsHomeSectionV2Component', () => {
  let component: EventsHomeSectionV2Component;
  let fixture: ComponentFixture<EventsHomeSectionV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [EventsHomeSectionV2Component]
    });
    fixture = TestBed.createComponent(EventsHomeSectionV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
