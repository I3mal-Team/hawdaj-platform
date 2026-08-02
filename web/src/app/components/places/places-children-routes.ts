import { PlaceDetailsComponent } from "./components/place-details/place-details.component";
import { PlacesListComponent } from "./components/places-list/places-list.component";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";
import { PlacesListV2Component } from "./components/places-list-v2/places-list-v2.component";
import { PlaceDetailsV2Component } from "./components/place-details-v2/place-details-v2.component";

export const placesChildrenRoutes: any[] = [
  {
    path: '',
    component: PlacesListV2Component,
    pathMatch: 'full',
    data: {
      title: 'titles.places',
      page: 'places'
    }
  },
  {
    path: "details/:id",
    component: PlaceDetailsV2Component,
    pathMatch: 'full',
    data: {
      title: 'titles.places',
      page: 'place-details'
    }
  },

  // Errors
  {
    path: ':lang/Errors',
    loadComponent: () =>
      import('../errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes

  },

  {
    path: 'Errors',
    loadComponent: () =>
      import('../errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes
  },
  { path: '**', redirectTo: '/Errors/404' } // Redirect all unknown paths to '/Errors'
];
