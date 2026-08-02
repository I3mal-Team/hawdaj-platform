/**
 * Minimal TransferState interface (Angular 16+ safe)
 * Avoids deprecated TransferState type
 */
interface TransferStateLike {
  get<T>(key: any, defaultValue: T): T;
  set<T>(key: any, value: T): void;
  remove(key: any): void;
}

/**
 * Angular 16+ safe wrapper for TransferState string keys
 */
export function getTransferState<T>(
  state: TransferStateLike,
  key: string,
  defaultValue: T
): T {
  return state.get(key, defaultValue);
}

export function setTransferState<T>(
  state: TransferStateLike,
  key: string,
  value: T
): void {
  state.set(key, value);
}

export function removeTransferState(
  state: TransferStateLike,
  key: string
): void {
  state.remove(key);
}
