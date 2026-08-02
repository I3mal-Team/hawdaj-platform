export enum Map {
    'trip.AlRiyad' = 1,
    'trip.Makkah' = 2,
    'trip.AlMadinah' = 3,
    'trip.AlJawf' = 4,
    'trip.AlBahah' = 5,
    'trip.Najran' = 6,
    'trip.Jizan' = 7,
    'trip.AlHududShamaliyah' = 8,
    'trip.Hail' = 9,
    'trip.Tabuk' = 10,
    'trip.Asir' = 11,
    'trip.AlQuassim' = 12,
    'trip.AshSharqiyah' = 13
  }
  
export function getKeyFromValue(value: number): string | undefined {
    // Iterate through the keys of the enum
    for (const key in Map) {
      // Check if the value matches and it's a number
      if (Map[key as keyof typeof Map] === value) {
        return key;
      }
    }
    return undefined; // If no match found
  }
  