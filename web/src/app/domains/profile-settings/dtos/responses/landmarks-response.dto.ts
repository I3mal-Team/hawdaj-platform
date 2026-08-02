import { ILandmarkPaginationData } from '../../interfaces';

export interface ILandmarksResponseDto {
  code: number;
  message: string;
  data: ILandmarkPaginationData;
}


