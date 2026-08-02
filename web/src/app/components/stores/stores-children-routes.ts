import { StoreDetailsComponent } from "../../stores-management/components/store-details/store-details.component";
import { StoresListComponent } from "../../stores-management/components/stores-list/stores-list.component";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";
export const StoresChildrenRoutes: any[] = [
  { path: '', redirectTo: 'list', pathMatch: 'full' },
  {
    path: 'list',
    component: StoresListComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.stores',
      page: 'stores'
    }
  },
  {
    path: ":id",
    component: StoreDetailsComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.storeDetails',
      page: 'store-details'
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
