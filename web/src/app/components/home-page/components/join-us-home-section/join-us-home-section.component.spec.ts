import { ComponentFixture, TestBed } from '@angular/core/testing';

import { JoinUsHomeSectionComponent } from './join-us-home-section.component';

describe('JoinUsHomeSectionComponent', () => {
  let component: JoinUsHomeSectionComponent;
  let fixture: ComponentFixture<JoinUsHomeSectionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [JoinUsHomeSectionComponent]
    });
    fixture = TestBed.createComponent(JoinUsHomeSectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
