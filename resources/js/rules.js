export const rules = {
  required: v => !!v || 'Toto pole je povinné.',
  email: v => /.+@.+\..+/.test(v) || 'Toto není platý e-mail.',
  lengthSix: v => (!v || String(v).length >= 6) || 'Zadejte alespoň 6 znaků'
};
