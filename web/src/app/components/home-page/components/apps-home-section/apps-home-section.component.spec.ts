import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AppsHomeSectionComponent } from './apps-home-section.component';

describe('AppsHomeSectionComponent', () => {
  let component: AppsHomeSectionComponent;
  let fixture: ComponentFixture<AppsHomeSectionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AppsHomeSectionComponent]
    });
    fixture = TestBed.createComponent(AppsHomeSectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
