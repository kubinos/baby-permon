export const rules = {
  required: v => !!v || 'Toto pole je povinné.',
  email: v => /.+@.+\..+/.test(v) || 'Toto není platý e-mail.',
};
