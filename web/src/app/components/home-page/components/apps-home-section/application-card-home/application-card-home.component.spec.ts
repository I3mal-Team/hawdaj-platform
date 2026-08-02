import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplicationCardHomeComponent } from './application-card-home.component';

describe('ApplicationCardHomeComponent', () => {
  let component: ApplicationCardHomeComponent;
  let fixture: ComponentFixture<ApplicationCardHomeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ApplicationCardHomeComponent]
    });
    fixture = TestBed.createComponent(ApplicationCardHomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
