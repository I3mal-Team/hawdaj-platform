export const StoriesEndpoints = {
    base: 'swalefs',
    getAll: 'swalefs',
    getById: (id: number) => `swalefs/${id}`,
    categories: 'swalefs-categories' // Added this new endpoint

};
