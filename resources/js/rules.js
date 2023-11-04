export const rules = {
  required: v => (!!v || v === 0) || 'Toto pole je povinné.',
  email: v => /.+@.+\..+/.test(v) || 'Toto není platý e-mail.',
  chip: v => v.length === 8 || 'Čip musí mít 8 znaků.',
};
