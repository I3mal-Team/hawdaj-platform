import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventCardHomeComponent } from './event-card-home.component';

describe('EventCardHomeComponent', () => {
  let component: EventCardHomeComponent;
  let fixture: ComponentFixture<EventCardHomeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [EventCardHomeComponent]
    });
    fixture = TestBed.createComponent(EventCardHomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
