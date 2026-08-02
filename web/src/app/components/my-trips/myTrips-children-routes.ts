import { SaveTripComponent } from "src/app/components/my-trips/components/save-trip/save-trip.component";
import { MyTripDetailsComponent } from "./components/my-trip-details/my-trip-details.component";
import { MyTripsListComponent } from "./components/my-trips-list/my-trips-list.component";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";
import { AuthGuard } from "src/app/services/auth.guard";
import { SaveTripV2Component } from "./components/save-trip-v2/save-trip-v2.component";
import { MyTripsListV2Component } from "./components/my-trips-list-v2/my-trips-list-v2.component";
import { MyTripDetailsV2Component } from "./components/my-trip-details-v2/my-trip-details-v2.component";

export const MyTripsChildrenRoutes: any[] = [
  { path: '', redirectTo: 'list', pathMatch: 'full' },
  {
    canActivate: [AuthGuard],
    path: 'list', component: MyTripsListV2Component,
    data: {
      page: 'trips',
      title: 'titles.trips',
    }
  },
  {
    path: 'save-trip/:token',
    component: SaveTripV2Component,
    data: {
      page: 'trips',
      title: 'titles.trips'
    }
  }

  ,
  {
    path: 'trip-details' + '/:id', component: MyTripDetailsV2Component,
    data: {
      page: 'trips',
      title: 'titles.tripDetails',
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
