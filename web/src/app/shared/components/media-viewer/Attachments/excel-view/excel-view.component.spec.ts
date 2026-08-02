import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ExcelViewComponent } from './excel-view.component';

describe('ExcelViewComponent', () => {
  let component: ExcelViewComponent;
  let fixture: ComponentFixture<ExcelViewComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ExcelViewComponent]
    });
    fixture = TestBed.createComponent(ExcelViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
