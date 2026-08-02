import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InviteToVisitComponent } from './invite-to-visit.component';

describe('InviteToVisitComponent', () => {
  let component: InviteToVisitComponent;
  let fixture: ComponentFixture<InviteToVisitComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [InviteToVisitComponent]
    });
    fixture = TestBed.createComponent(InviteToVisitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
