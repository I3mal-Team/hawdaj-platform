import { EmptyStateConfig } from 'src/app/Common/component/empty-state-card/empty-state-card.component';
import { ErrorStateConfig } from 'src/app/Common/component/error-state-card/error-state-card.component';

export const propertiesEmptyStateConfig: EmptyStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-properties.png',
    message: 'general.noData'
};

export const landmarksEmptyStateConfig: EmptyStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-landmarks.png',
    message: 'general.noData'
};

export const favoritesEmptyStateConfig: EmptyStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-favourites.png',
    message: 'general.noData'
};

export const propertiesErrorStateConfig: ErrorStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-properties.png',
    message: 'general.errorOccurred',
    retryLabel: 'general.retry'
};

export const landmarksErrorStateConfig: ErrorStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-landmarks.png',
    message: 'general.errorOccurred',
    retryLabel: 'general.retry'
};

export const favoritesErrorStateConfig: ErrorStateConfig = {
    imageUrl: 'assets/images-v2/empty-states/my-favourites.png',
    message: 'general.errorOccurred',
    retryLabel: 'general.retry'
};