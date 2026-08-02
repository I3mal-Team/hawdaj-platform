import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventsHomeSectionComponent } from './events-home-section.component';

describe('EventsHomeSectionComponent', () => {
  let component: EventsHomeSectionComponent;
  let fixture: ComponentFixture<EventsHomeSectionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [EventsHomeSectionComponent]
    });
    fixture = TestBed.createComponent(EventsHomeSectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
