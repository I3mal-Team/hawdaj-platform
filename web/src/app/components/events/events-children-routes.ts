import { EventDetailsComponent } from "../../events-management";
import { EventsListComponent } from "../../events-management";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";

export const EventsChildrenRoutes: any[] = [
  {
    path: '',
    component: EventsListComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.events',
      page: 'events'
    }
  },
  {
    path: "event-details/:id",
    component: EventDetailsComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.eventDetails',
      page: 'events-details'
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
