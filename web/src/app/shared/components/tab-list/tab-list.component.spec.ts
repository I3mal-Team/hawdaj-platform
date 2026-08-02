import { ComponentFixture, TestBed } from '@angular/core/testing';
import { TabListComponent } from './tab-list.component';
import { TranslateModule } from '@ngx-translate/core';

describe('TabListComponent', () => {
  let component: TabListComponent;
  let fixture: ComponentFixture<TabListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TabListComponent, TranslateModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(TabListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should emit tabClick event when tab is clicked', () => {
    const mockTab = {
      id: 'test-tab',
      label: 'Test Tab',
      active: false
    };

    component.tabs.set([mockTab]);
    spyOn(component.tabClick, 'emit');

    component['onTabClick'](mockTab);

    expect(component.tabClick.emit).toHaveBeenCalledWith(mockTab);
  });

  it('should not emit event when disabled tab is clicked', () => {
    const mockTab = {
      id: 'test-tab',
      label: 'Test Tab',
      active: false,
      disabled: true
    };

    component.tabs.set([mockTab]);
    spyOn(component.tabChange, 'emit');

    component['onTabClick'](mockTab);

    expect(component.tabChange.emit).not.toHaveBeenCalled();
  });

  it('should handle single selection correctly', () => {
    const tabs = [
      { id: 'tab1', label: 'Tab 1', active: true },
      { id: 'tab2', label: 'Tab 2', active: false }
    ];

    component.tabs.set(tabs);
    component.config.set({ isMultiple: false, showIcons: true });

    component['onTabClick'](tabs[1]);

    const updatedTabs = component.tabs();
    expect(updatedTabs[0].active).toBeFalse();
    expect(updatedTabs[1].active).toBeTrue();
  });

  it('should handle multiple selection correctly', () => {
    const tabs = [
      { id: 'tab1', label: 'Tab 1', active: true },
      { id: 'tab2', label: 'Tab 2', active: false }
    ];

    component.tabs.set(tabs);
    component.config.set({ isMultiple: true, showIcons: true });

    component['onTabClick'](tabs[1]);

    const updatedTabs = component.tabs();
    expect(updatedTabs[0].active).toBeTrue();
    expect(updatedTabs[1].active).toBeTrue();
  });
});




