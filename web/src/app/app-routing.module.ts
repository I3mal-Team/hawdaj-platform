
import { SearchResultComponent } from './components/home-page/components/search-result/search-result.component';
import { RouterModule, Routes } from '@angular/router';
import { NgModule } from '@angular/core';

import { ResturantsChildrenRoutes } from './components/resturants/resturants-children-routes';
import { StoriesChildrenRoutes } from './components/stories/stories-children-routes';
import { errorsChildrenRoutes } from './components/errors/errors-children-routes';
import { placesChildrenRoutes } from './components/places/places-children-routes';
import { StoresChildrenRoutes } from './components/stores/stores-children-routes';
import { EventsChildrenRoutes } from './components/events/events-children-routes';
import { AuthGuard } from './services/auth.guard';
import { profileChildrenRoutes } from './components/personal-profile/profile-children-routes';
import { MyTripsChildrenRoutes } from './components/my-trips/myTrips-children-routes';
import { ProfileSettingsRoutesEnum, TripRoutesEnum } from './domains';

const routes: Routes = [
  { path: '', redirectTo: '/home', pathMatch: 'full' },
  // Home Page
  {
    path: '', loadComponent: () => import('./components/home-page/home-page.component').then(m => m.HomePageComponent),
    data: {
      title: 'titles.home',
      page: 'home'
    }
  },
  {
    path: ':lang/', loadComponent: () => import('./components/home-page/home-page.component').then(m => m.HomePageComponent),
    data: {
      title: 'titles.home',
      page: 'home'
    }
  },
  {
    path: 'home', loadComponent: () => import('./components/home-page/home-page.component').then(m => m.HomePageComponent),
    data: {
      title: 'titles.home',
      page: 'home'
    }
  },
  {
    path: ':lang/home', loadComponent: () => import('./components/home-page/home-page.component').then(m => m.HomePageComponent),
    data: {
      title: 'titles.home',
      page: 'home'
    }
  },
  // Search Result
  {
    path: 'search-result', component: SearchResultComponent,
    data: {
      title: 'titles.searchResult',
      page: 'searchResult'
    }
  },
  {
    path: ':lang/search-result', component: SearchResultComponent,
    data: {
      title: 'titles.searchResult',
      page: 'searchResult'
    }
  },
  // Places
  {
    path: 'places',
    loadComponent: () =>
      import('./components/places/places.component').then(
        (c) => c.PlacesComponent
      ),
    children: placesChildrenRoutes
  },
  {
    path: ':lang/places',
    loadComponent: () =>
      import('./components/places/places.component').then(
        (c) => c.PlacesComponent
      ),
    children: placesChildrenRoutes
  },
  // Stores
  {
    path: 'stores',
    loadComponent: () =>
      import('./components/stores/stores.component').then(
        (c) => c.StoresComponent
      ),
    children: StoresChildrenRoutes
  },
  {
    path: ':lang/stores',
    loadComponent: () =>
      import('./components/stores/stores.component').then(
        (c) => c.StoresComponent
      ),
    children: StoresChildrenRoutes
  },
  // Resturants
  {
    path: 'restaurants',
    loadComponent: () =>
      import('./components/resturants/resturants.component').then(
        (c) => c.ResturantsComponent
      ),
    children: ResturantsChildrenRoutes
  },
  {
    path: ':lang/restaurants',
    loadComponent: () =>
      import('./components/resturants/resturants.component').then(
        (c) => c.ResturantsComponent
      ),
    children: ResturantsChildrenRoutes
  },
  // Stories
  {
    path: 'stories',
    loadComponent: () =>
      import('./components/stories/stories.component').then(
        (c) => c.StoriesComponent
      ),
    children: StoriesChildrenRoutes
  },
  {
    path: ':lang/stories',
    loadComponent: () =>
      import('./components/stories/stories.component').then(
        (c) => c.StoriesComponent
      ),
    children: StoriesChildrenRoutes
  },
  // Events
  {
    path: 'events',
    loadComponent: () =>
      import('./components/events/events.component').then(
        (c) => c.EventsComponent
      ),
    children: EventsChildrenRoutes
  },
  {
    path: ':lang/events',
    loadComponent: () =>
      import('./components/events/events.component').then(
        (c) => c.EventsComponent
      ),
    children: EventsChildrenRoutes
  },

  // My Trips
  // {
  //   path: 'trips',
  //   loadComponent: () =>
  //     import('./components/my-trips/my-trips.component').then(
  //       (c) => c.MyTripsComponent
  //     ),
  //   children: MyTripsChildrenRoutes
  // },
  // {
  //   path: ':lang/trips',
  //   loadComponent: () =>
  //     import('./components/my-trips/my-trips.component').then(
  //       (c) => c.MyTripsComponent
  //     ),
  //   children: MyTripsChildrenRoutes
  // },


  // Trip Module
  {
    path: TripRoutesEnum.TRIP1,
    loadChildren: () =>
      import('./domains/trip').then(m => m.TRIP_ROUTES_CONFIG)
  },
  {
    path: TripRoutesEnum.TRIP2,
    loadChildren: () =>
      import('./domains/trip').then(m => m.TRIP_ROUTES_CONFIG)
  },
  {
    path: `:lang/${TripRoutesEnum.TRIP1}`,
    loadChildren: () =>
      import('./domains/trip').then(m => m.TRIP_ROUTES_CONFIG)
  },
  {
    path: `:lang/${TripRoutesEnum.TRIP2}`,
    loadChildren: () =>
      import('./domains/trip').then(m => m.TRIP_ROUTES_CONFIG)
  },

  // Profile Settings Module
  {
    path: ProfileSettingsRoutesEnum.ROOT,
    loadChildren: () =>
      import('./domains/profile-settings').then(m => m.PROFILE_SETTINGS_CONFIG)
  },
  {
    path: `:lang/${ProfileSettingsRoutesEnum.ROOT}`,
    loadChildren: () =>
      import('./domains/profile-settings').then(m => m.PROFILE_SETTINGS_CONFIG)
  },

  // Applications
  {
    path: 'applications',
    loadComponent: () =>
      import('./components/applications/application-list-v2/application-list-v2.component').then(
        (c) => c.ApplicationListV2Component
      ),
    data: {
      title: 'titles.applications',
      page: 'applications'
    }
  },
  {
    path: ':lang/applications',
    loadComponent: () =>
      import('./components/applications/application-list-v2/application-list-v2.component').then(
        (c) => c.ApplicationListV2Component
      ),
    data: {
      title: 'titles.applications',
      page: 'applications'
    }
  },
  // Tour Guides
  {
    path: 'tour-guides',
    loadComponent: () =>
      import('./tour-guides-management/components/tour-guides-list/tour-guides-list.component').then(
        (c) => c.TourGuidesListComponent
      ),
    data: {
      title: 'titles.tourGuides',
      page: 'tourGuides'
    }
  },
  {
    path: ':lang/tour-guides',
    loadComponent: () =>
      import('./tour-guides-management/components/tour-guides-list/tour-guides-list.component').then(
        (c) => c.TourGuidesListComponent
      ),
    data: {
      title: 'titles.tourGuides',
      page: 'tourGuides'
    }
  },
  {
    path: 'tour-guides/:id',
    loadComponent: () =>
      import('./tour-guides-management/components/tour-guide-details/tour-guide-details.component').then(
        (c) => c.TourGuideDetailsComponent
      ),
    data: {
      title: 'titles.tourGuideDetails',
      page: 'tourGuideDetails'
    }
  },
  {
    path: ':lang/tour-guides/:id',
    loadComponent: () =>
      import('./tour-guides-management/components/tour-guide-details/tour-guide-details.component').then(
        (c) => c.TourGuideDetailsComponent
      ),
    data: {
      title: 'titles.tourGuideDetails',
      page: 'tourGuideDetails'
    }
  },

  // Profile
  {
    path: 'Profile',
    canActivate: [AuthGuard],
    loadComponent: () =>
      import('./components/personal-profile/personal-profile.component').then(
        (c) => c.PersonalProfileComponent
      ),
    children: profileChildrenRoutes,
  },
  {
    path: ':lang/Profile',
    canActivate: [AuthGuard],
    loadComponent: () =>
      import('./components/personal-profile/personal-profile.component').then(
        (c) => c.PersonalProfileComponent
      ),
    children: profileChildrenRoutes,

  },

  // { path: '', loadChildren: () => import('./modules/layout/layout.module').then(m => m.LayoutModule) },

  // Errors
  {
    path: 'Errors',
    loadComponent: () =>
      import('./components/errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes
  },
  {
    path: ':lang/Errors',
    loadComponent: () =>
      import('./components/errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes
  },
  { path: '**', redirectTo: '/Errors' } // Redirect all unknown paths to '/Errors'
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {
    useHash: false,
    scrollPositionRestoration: 'top',
    enableTracing: false,
    initialNavigation: 'enabledBlocking',
  },
  )],
  exports: [RouterModule]
})
export class AppRoutingModule { }
