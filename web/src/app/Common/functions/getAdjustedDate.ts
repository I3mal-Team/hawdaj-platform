export function getAdjustedDate(startDate: string | Date, days: number): Date {
  if (!startDate) return new Date();

  const newDate = new Date(startDate);
  newDate.setDate(newDate.getDate() + (days || 0));

  return newDate;
}
