import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BasicInfoDetailsComponent } from './basic-info-details.component';

describe('BasicInfoDetailsComponent', () => {
  let component: BasicInfoDetailsComponent;
  let fixture: ComponentFixture<BasicInfoDetailsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [BasicInfoDetailsComponent]
    });
    fixture = TestBed.createComponent(BasicInfoDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
