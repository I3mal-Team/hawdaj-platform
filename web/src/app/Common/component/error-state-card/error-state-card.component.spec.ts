import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ErrorStateCardComponent } from './error-state-card.component';

describe('ErrorStateCardComponent', () => {
  let component: ErrorStateCardComponent;
  let fixture: ComponentFixture<ErrorStateCardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ErrorStateCardComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ErrorStateCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
