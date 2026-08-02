import { ChangeDetectorRef, Component, ElementRef, EventEmitter, Input, Output, QueryList, ViewChildren, AfterViewInit, OnChanges } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-code-input',
  templateUrl: './code-input.component.html',
  styleUrls: ['./code-input.component.scss'],
  standalone: true,
  imports: [CommonModule]
})
export class CodeInputComponent implements AfterViewInit, OnChanges {
  @ViewChildren('inputRefs') inputRefs!: QueryList<ElementRef<HTMLInputElement>>;
  inputs = Array.from({ length: 4 }, (_, i) => i + 1);
  focusedIndex: number = -1;

  @Input() clearInputs!: boolean;
  @Input() typedInput: string = 'num'; // num- char - both
  @Input() borderColor: string = '#f1f3f5';
  @Input() bgColor: string = '#f9fafb';
  @Output() codeChanged = new EventEmitter<number | string | null>();
  @Output() codeCompleted = new EventEmitter<number | string | null>();

  constructor(private cdRef: ChangeDetectorRef) { }

  ngAfterViewInit(): void {
    this.focusFirstInput();
  }

  ngOnChanges(): void {
    if (this.clearInputs) {
      this.clearAllInputs();
    }
  }

  focusFirstInput(): void {
    setTimeout(() => {
      this.inputRefs.first.nativeElement.focus();
      this.focusedIndex = 0;
      this.cdRef.detectChanges(); // Manually trigger change detection after updating view
    }, 100);
  }

  clearAllInputs(): void {
    this.inputRefs.forEach(input => {
      input.nativeElement.value = '';
    });
    this.focusFirstInput();
    this.codeChanged.emit(null);  // Emit null to indicate reset
  }

  onCodeChanged(index: number, event: KeyboardEvent): void {
    const currentInput: any = this.inputRefs.toArray()[index];
    const key: any = event.key;

    if (key === "Backspace" && currentInput.nativeElement.value === "") {
      // Handle backspace on an empty input to move to the previous input if it exists
      const previousInput: any = this.inputRefs.toArray()[index - 1];
      if (previousInput) {
        previousInput.nativeElement.focus();
        this.focusedIndex = index - 1;
      }
    } else if (key?.length === 1 && this.validateInput(key)) {
      // Only process the input if it's a single character and it is valid
      currentInput.nativeElement.value = key; // Directly set the value
      this.codeChanged.emit(this.getConcatenatedValues()); // Emit the changed code

      if (index < this.inputs?.length - 1) {
        const nextInput: any = this.inputRefs.toArray()[index + 1];
        if (nextInput) {
          nextInput.nativeElement.focus();
          this.focusedIndex = index + 1;
        }
      } else {
        this.onCodeCompleted();
        currentInput.nativeElement.focus(); // Keep focus on the last input
      }
    } else {
      // If the key is not valid, prevent the default action to avoid invalid characters being set
      event.preventDefault();
    }
  }

  getConcatenatedValues(): string {
    return this.inputRefs?.toArray()?.map(input => input?.nativeElement?.value)?.join('');
  }

  validateInput(key: string): boolean {
    switch (this.typedInput) {
      case 'num':
        return /^[0-9]$/.test(key);  // Allow only numbers
      case 'char':
        return /^[a-zA-Z]$/.test(key); // Allow only alphabet characters
      case 'both':
        return /^[0-9a-zA-Z]$/.test(key); // Allow both numbers and alphabet characters
      default:
        return false; // Default to no valid input
    }
  }

  onCodeCompleted(): void {
    let allFilled = this.inputRefs.toArray().every(input => input.nativeElement.value !== '');
    if (allFilled) {
      let concatenatedValues: number | string | null = this.getConcatenatedValues();
      this.inputRefs.last.nativeElement.blur();  // Remove focus from the last input
      this.codeCompleted.emit(concatenatedValues);
    }
  }
}
