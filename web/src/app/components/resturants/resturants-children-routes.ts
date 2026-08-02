import { RestaurantDetailsComponent } from "../../restaurants-management/components/restaurant-details/restaurant-details.component";
import { ResturantsListComponent } from "../../restaurants-management/components/restaurants-list/restaurants-list.component";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";

export const ResturantsChildrenRoutes: any[] = [
  { path: '', redirectTo: 'list', pathMatch: 'full' },
  {
    path: 'list',
    component: ResturantsListComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.zad',
      page: 'restaurants'
    }
  },
  {
    path: ":id",
    component: RestaurantDetailsComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.zad',
      page: 'restaurant-details'
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
