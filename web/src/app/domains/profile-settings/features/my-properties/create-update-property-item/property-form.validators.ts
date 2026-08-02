import { AbstractControl, FormGroup, ValidationErrors, ValidatorFn } from '@angular/forms';
import { StoreAddressType, StoreType } from './property-item.model';
import { PropertyItemType } from './property-item-type.enum';

/**
 * Validates if the value is a valid URL
 */
export function isValidUrl(value: string): boolean {
  if (!value || value.trim() === '') return true;

  try {
    new URL(value.startsWith('http') ? value : `https://${value}`);
    return true;
  } catch {
    return false;
  }
}

/**
 * Validates if the value is a valid phone number or URL (for WhatsApp)
 */
export function isValidPhoneOrUrl(value: string): boolean {
  if (!value || value.trim() === '') {
    return true; // Empty is valid (optional field)
  }
  const trimmed = value.trim();
  // Saudi phone number pattern
  const phoneRegex = /^(\+?966|0)?5\d{8}$/;
  // WhatsApp URL pattern
  const urlRegex = /^(https?:\/\/)?(wa\.me|api\.whatsapp\.com)/;
  return phoneRegex.test(trimmed) || urlRegex.test(trimmed);
}

/**
 * Validates if start date is before end date
 */
export function isStartBeforeEnd(startDate: Date | null, endDate: Date | null): boolean {
  if (!startDate || !endDate) {
    return true; // If either is missing, validation passes (handled by required validators)
  }
  return startDate <= endDate;
}

/**
 * URL validator for form controls
 */
export function urlValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    if (!control.value || control.value.trim() === '') {
      return null; // Empty is valid
    }
    return isValidUrl(control.value) ? null : { invalidUrl: true };
  };
}

/**
 * Phone or URL validator for WhatsApp
 */
export function phoneOrUrlValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    if (!control.value || control.value.trim() === '') {
      return null; // Empty is valid
    }
    return isValidPhoneOrUrl(control.value) ? null : { invalidPhoneOrUrl: true };
  };
}

/**
 * Date range validator for form group
 */
export function dateRangeValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const startDate = control.get('startDate')?.value;
    const endDate = control.get('endDate')?.value;

    if (!startDate || !endDate) {
      return null; // If either is missing, validation passes
    }

    if (startDate > endDate) {
      return { dateRangeInvalid: true };
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    if (startDate < today) {
      return { startDatePast: true };
    }

    return null;
  };
}

/**
 * Store Types validator for form group
 */
export function storeRulesValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    if (!(control instanceof FormGroup)) return null;

    const formGroup = control;
    const errors: ValidationErrors = {};

    const propertyType = formGroup.get('propertyType')?.value;
    if (propertyType !== PropertyItemType.Store) return null;

    const storeType = formGroup.get('storeType')?.value as StoreType | null;
    if (!storeType) {
      errors['storeTypeRequired'] = true;
      return errors;
    }

    const storeUrl = formGroup.get('storeUrl')?.value;
    const region = formGroup.get('region')?.value;
    const city = formGroup.get('city')?.value;
    const addressType = formGroup.get('storeAddressType')?.value;
    const location = formGroup.get('location')?.value;

    // ONLINE STORE
    if (storeType === StoreType.Online) {
      if (!storeUrl || storeUrl.trim() === '') {
        errors['storeUrlRequired'] = true;
      }
    }
    // OFFLINE STORE
    else if (storeType === StoreType.Offline) {
      // Region & City are mandatory
      if (!region || !city) {
        errors['regionCityRequired'] = true;
      }

      // Address type is mandatory
      if (!addressType) {
        errors['storeAddressTypeRequired'] = true;
      } else {
        // Map address requires location
        if (addressType === StoreAddressType.Map && !location) {
          errors['mapLocationRequired'] = true;
        }
        // Link address requires storeUrl
        else if (addressType === StoreAddressType.Link && (!storeUrl || storeUrl.trim() === '')) {
          errors['storeUrlRequired'] = true;
        }
      }
    }

    return Object.keys(errors).length > 0 ? errors : null;
  };
}

export function requiredFileArray(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const value = control.value as unknown;
    return Array.isArray(value) && value.length > 0
      ? null
      : { required: true };
  };
}
