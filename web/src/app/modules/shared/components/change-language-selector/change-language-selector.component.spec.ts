import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ChangeLanguageSelectorComponent } from './change-language-selector.component';

describe('ChangeLanguageSelectorComponent', () => {
  let component: ChangeLanguageSelectorComponent;
  let fixture: ComponentFixture<ChangeLanguageSelectorComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [ChangeLanguageSelectorComponent]
    });
    fixture = TestBed.createComponent(ChangeLanguageSelectorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
