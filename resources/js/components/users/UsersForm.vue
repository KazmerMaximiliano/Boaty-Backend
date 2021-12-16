<template>
    <div>
        <v-row justify="center">
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.first_name"
                    :rules="[rules.required]"
                    label="Nombre"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.last_name"
                    :rules="[rules.required]"
                    label="Apellido"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.phone"
                    :rules="[rules.required]"
                    label="CEL/TEL"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.address"
                    :rules="[rules.required]"
                    label="Dirección"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="pt-0 pb-8 px-5">
                <v-dialog v-model="fechaDialog" ref="fechaDialog" :return-value.sync="$store.state.users.form.birthday" persistent width="300px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="$store.state.users.form.birthday"
                            label="Fecha de nacimiento"
                            v-bind="attrs"
                            readonly
                            v-on="on"
                            outlined
                            hide-details
                        ></v-text-field>
                    </template>
                    <v-date-picker v-model="$store.state.users.form.birthday" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="error" @click="fechaDialog = false">
                        Cancelar
                    </v-btn>
                    <v-btn text color="success" @click="$refs.fechaDialog.save($store.state.users.form.birthday)">
                        Aceptar
                    </v-btn>
                    </v-date-picker>
                </v-dialog>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.email"
                    :rules="[rules.required]"
                    label="Email"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.password"
                    :rules="rulesPassword ? [rules.required, rules.max, rules.min] : []"
                    :append-icon="password ? 'fas fa-eye' : 'fas fa-eye-slash'"
                    @click:append="password = !password"
                    :type="password ? 'text' : 'password'"
                    label="Contraseña"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-text-field
                    v-model="$store.state.users.form.password_confirm"
                    :rules="rulesPassword ? [rules.required, rules.max, rules.min, confirmpass] : [confirmpass]"
                    :append-icon="confirm_password ? 'fas fa-eye' : 'fas fa-eye-slash'"
                    @click:append="confirm_password = !confirm_password"
                    :type="confirm_password ? 'text' : 'password'"
                    label="Confirmar Contraseña"
                    outlined
                ></v-text-field>
            </v-col>
            <v-col cols="12" class="py-0 px-5">
                <v-select
                    :items="['admin', 'owner', 'client']"
                    v-model="$store.state.users.form.roles"
                    label="Rol"
                    multiple
                    outlined
                ></v-select>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    data: () => ({
        fechaDialog: false,
        password: false,
        confirm_password: false,
        rules: {
            required: value => !!value || "Este campo es obligatorio",
            max: value =>
                (value && value.length <= 255) ||
                "Este campo no puede contener mas de 255 digitos",
            min: value =>
                (value && value.length >= 6) ||
                "Este campo debe contener al menos 6 digitos",
        }
    }),

    props: ["mode", "currentUser"],

    computed: {
        rulesPassword() {
            if (this.mode == "create") {
                return true;
            } else if (this.mode == "edit") {
                if (this.$store.state.users.form.password) {
                    return true;
                } else {
                    return false;
                }
            }
        },
    },

    methods: {
        confirmpass() {
            if(this.$store.state.users.form.password != this.$store.state.users.form.password_confirm) {
                return 'Las contraseñas no coinciden';
            } else {
                return true;
            }
        }
    }
};
</script>