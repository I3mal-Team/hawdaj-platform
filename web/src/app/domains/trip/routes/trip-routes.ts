import { Route } from '@angular/router';
import { TripRouteData, TripRoutesEnum } from '../constants';

export const TRIP_ROUTES_CONFIG: Route[] = [
  {
    path: '',
    loadComponent: () =>
      import('./../containers/trip-layout/trip-layout.component').then(
        m => m.TripLayoutComponent
      ),
    data: TripRouteData.MainPage,
    children: [
      {
        path: '',
        pathMatch: 'full',
        loadComponent: () =>
          import('./../components/trips-list/trips-list.component').then(
            m => m.TripsListComponent
          ),
        data: TripRouteData.MainPage,
      },
      {
        path: TripRoutesEnum.LIST,
        loadComponent: () =>
          import('./../components/trips-list/trips-list.component').then(
            m => m.TripsListComponent
          ),
        data: TripRouteData.MainPage,
      },
      {
        path: `${TripRoutesEnum.SAVE_TRIP_DETAILS}/:token`,
        loadComponent: () =>
          import('./../components/save-trip-details/save-trip-details.component').then(
            m => m.SaveTripDetailsComponent
          ),
        data: TripRouteData.MainPage,
      },
      {
        path: ':token',
        loadComponent: () =>
          import('./../components/trip-details/trip-details.component').then(
            m => m.TripDetailsComponent
          ),
        data: TripRouteData.MainPage,
      },
      {
        path: '**',
        redirectTo: '',
      },
    ],
  },
];
