import { Route } from '@angular/router';
import { ProfileSettingsRouteData, ProfileSettingsRoutesEnum } from '../constants';

export const PROFILE_SETTINGS_CONFIG: Route[] = [
  {
    path: '',
    loadComponent: () =>
      import('./../containers/profile-settings-layout/profile-settings-layout.component')
        .then(m => m.ProfileSettingsLayoutComponent),
    data: ProfileSettingsRouteData.MainPage,
    children: [
      // ------- PERSONAL INFO -------
      {
        path: ProfileSettingsRoutesEnum.PERSONAL_INFO,
        loadComponent: () =>
          import('./../features/personal-info/personal-info-form')
            .then(m => m.PersonalInfoFormComponent),
      },

      // ------- POSTS -------
      {
        path: ProfileSettingsRoutesEnum.POSTS,
        loadComponent: () =>
          import('./../features/posts/posts-list/posts-list.component')
            .then(m => m.PostsListComponent),
      },
      {
        path: `${ProfileSettingsRoutesEnum.POSTS}/:id`,
        loadComponent: () =>
          import('./../features/posts/post-details/post-details.component')
            .then(m => m.PostDetailsComponent),
      },

      // ------- TOURIST GUIDE INFO -------
      {
        path: ProfileSettingsRoutesEnum.TOURIST_GUIDE_INFO,
        loadComponent: () =>
          import('./../features/tour-guide-info/tour-guide-details')
            .then(m => m.TourGuideDetailsComponent),
      },

      // ------- LANDMARKS -------
      {
        path: ProfileSettingsRoutesEnum.LANDMARKS,
        loadComponent: () =>
          import('./../features/landmarks/landmarks-list/landmarks-list.component')
            .then(m => m.LandmarksListComponent),
      },
      {
        path: `${ProfileSettingsRoutesEnum.LANDMARKS}/edit/:id`,
        loadComponent: () =>
          import('./../features/landmarks/landmarks-form/landmarks-form.component')
            .then(m => m.LandmarksFormComponent),
      },

      // ------- MY FAVOURITES -------
      {
        path: ProfileSettingsRoutesEnum.MY_FAVOURITES,
        loadComponent: () =>
          import('./../features/favourites/my-favourites-listing/my-favourites-listing.component')
            .then(m => m.MyFavouritesListingComponent),
      },

      // ------- MY PROPERTIES -------
      {
        path: ProfileSettingsRoutesEnum.MY_PROPERTIES,
        loadComponent: () =>
          import('./../features/my-properties/my-properties-listing/my-properties-listing.component')
            .then(m => m.MyPropertiesListingComponent),
      },
      {
        path: `${ProfileSettingsRoutesEnum.MY_PROPERTIES}/new`,
        loadComponent: () =>
          import('./../features/my-properties/create-update-property-item/create-update-property-item.component')
            .then(m => m.CreateUpdatePropertyItemComponent),
      },
      {
        path: `${ProfileSettingsRoutesEnum.MY_PROPERTIES}/edit/:id`,
        loadComponent: () =>
          import('./../features/my-properties/create-update-property-item/create-update-property-item.component')
            .then(m => m.CreateUpdatePropertyItemComponent),
      },

      // ------- DEFAULT -------
      { path: '', pathMatch: 'full', redirectTo: ProfileSettingsRoutesEnum.PERSONAL_INFO },
      { path: '**', redirectTo: ProfileSettingsRoutesEnum.PERSONAL_INFO },
    ],
  },
];
