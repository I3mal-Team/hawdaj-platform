import { ITripItem } from "../dtos";

export interface ITrip {
  data: ITripItem | null;
  message: string | null;
  status: boolean | null;
}
