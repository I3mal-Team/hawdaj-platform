export const patterns = {
  email: /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
  password: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/,
  phone: /^[0-9]{0,20}/,
}
