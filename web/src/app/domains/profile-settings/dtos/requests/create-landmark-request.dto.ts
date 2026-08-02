export interface ICreateLandmarkRequestDto {
  title: string;
  description: string;
  address: string;
  type: string;
  image?: File;
}

