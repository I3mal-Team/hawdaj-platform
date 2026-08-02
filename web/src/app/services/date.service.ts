import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class DateService {

  constructor() { }

  calculateTotalNumberOfDays(fromDate: any, toDate: any): any {
    if (toDate && fromDate) {
      const oneDayInMilliseconds = 24 * 60 * 60 * 1000;
      
      const startDateObj = new Date(fromDate);
      const endDateObj = new Date(toDate);
      
      const differenceInDays = Math.round(Math.abs((endDateObj.getTime() - startDateObj.getTime()) / oneDayInMilliseconds));
      
      return differenceInDays + 1;
    }
    return 0; 
  }
  
}
