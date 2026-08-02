export interface Theme {
  name: string;
  properties: any;
}

export const light: Theme = {
  name: "light",
  properties: {
    "--text-main-color": " #84548e",
    "--bg-main-color": " #84548e",
    "--text-white-color": " #fff",
    "--bg-white-color": " #fff",
    "--text-dark-color": " #000",
    "--bg-dark-color": " #000",
  },
};

export const dark: Theme = {
  name: "dark",
  properties: {
    "--text-main-color": " #84548e",
    "--bg-main-color": " #84548e",
    "--text-white-color": " #000",
    "--bg-white-color": " #000",
    "--text-dark-color": " #fff",
    "--bg-dark-color": " #fff",
  }
}
