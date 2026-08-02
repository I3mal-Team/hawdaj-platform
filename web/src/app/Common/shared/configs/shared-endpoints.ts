export const SharedEndpoints = {
    regions: 'places/regions',
    cities: (regionId: number) => `places/regions/${regionId}/cities`,
    languages: 'places/languages',
    feedback: 'places/feedback',
};
