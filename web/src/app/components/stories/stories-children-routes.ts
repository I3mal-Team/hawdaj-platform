import { errorsChildrenRoutes } from "../errors/errors-children-routes";
import { StoriesListComponent } from "../../stories-management";
import { StoryDetailsComponent } from "../../stories-management";

export const StoriesChildrenRoutes: any[] = [
  { path: '', redirectTo: 'list', pathMatch: 'full' },
  {
    path: 'list',
    component: StoriesListComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.swalefs',
      page: 'stories'
    }
  },
  {
    path: ":id",
    component: StoryDetailsComponent,
    pathMatch: 'full',
    data: {
      title: 'titles.storyDetails',
      page: 'story-details'
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
