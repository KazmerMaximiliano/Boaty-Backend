<template>
  <div class="d-flex justify-center" v-if="$store.state.auth.user">
    <v-form
      ref="criptoForm"
      @submit.prevent="updateAccount"
      class="mt-6"
      style="width: 90%;"
    >
      <h2>Dirección Cripto</h2>
      <br />
      <v-text-field
        v-model="form.crypto_currency"
        :rules="[rules.required]"
        label="Divisa"
        color="primary"
        outlined
        :disabled="inProcess"
      ></v-text-field>
      <v-text-field
        v-model="form.crypto_address"
        :rules="[rules.required]"
        :append-icon="password_type ? 'fas fa-eye' : 'fas fa-eye-slash'"
        @click:append="password_type = !password_type"
        :type="password_type ? 'text' : 'password'"
        label="Wallet"
        color="primary"
        outlined
        :disabled="inProcess"
      ></v-text-field>
      <v-row justify="end">
        <v-btn type="submit" tile text color="primary" :disabled="inProcess"
          >Actualizar</v-btn
        >
      </v-row>
    </v-form>

    <v-snackbar v-model="snackbar" color="success" top right>
      Datos Actualizados
    </v-snackbar>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data: () => ({
    snackbar: false,
    inProcess: false,
    form: {},
    password_type: false,
    rules: {
      required: value => !!value || 'Este campo es obligatorio'
    }
  }),

  methods: {
    async updateAccount() {
      this.inProcess = true
      axios
        .post('/api/set-crypto-address', this.form)
        .then(response => {
          this.inProcess = false
          this.snackbar = true
        })
        .catch(error => {
          this.inProcess = false
          this.snackbar = false
        })
    }
  }
}
</script>
