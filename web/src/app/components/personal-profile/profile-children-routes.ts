import { MyPropertiesListingComponent } from './../../domains/profile-settings/features/my-properties/my-properties-listing/my-properties-listing.component';
import { PersonalInformationComponent } from "./personal-information/personal-information.component";
import { errorsChildrenRoutes } from "../errors/errors-children-routes";
import { BasicInfoDetailsComponent } from "./basic-info-details/basic-info-details.component";
import { TourGuideInfoComponent } from "../tour-guides/tour-guide-info/tour-guide-info.component";

export const profileChildrenRoutes: any[] = [
  { path: '', redirectTo: 'Information', pathMatch: 'full' },
  {
    path: 'Information',
    component: PersonalInformationComponent,
    pathMatch: 'full',
    data: {
      page: 'profile',
      title: 'titles.profile',
    },
  },
  {
    path: 'tour-guide-info',
    component: TourGuideInfoComponent,
    // pathMatch: 'full',
    data: {
      page: 'tourGuideInfo',
      title: 'titles.tourGuideInfo',
    }
  },
  {
    path: 'my-properties',
    component: MyPropertiesListingComponent,
    // pathMatch: 'full',
    data: {
      page: 'myProperties',
      title: 'titles.myProperties',
    }
  },
  {
    path: 'Basic-Info',
    component: BasicInfoDetailsComponent,
    pathMatch: 'full',
    data: {
      page: 'profile',
      title: 'titles.profile',
    },
  },
  // Errors
  {
    path: ':lang/Errors',
    loadComponent: () =>
      import('../../components/errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes
  },
  {
    path: 'Errors',
    loadComponent: () =>
      import('../../components/errors/errors.component').then(
        (c) => c.ErrorsComponent
      ),
    children: errorsChildrenRoutes
  },
  { path: '**', redirectTo: '/Errors' } // Redirect all unknown paths to '/Errors'
];
