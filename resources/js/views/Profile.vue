<template>
  <div>
    <v-row>
      <v-col cols="12" md="4">
        <v-card outlined flat min-height="300px">
          <v-card-text>
            <Account></Account>
          </v-card-text>
          <v-card-actions style="position: absolute; bottom: 0; width: 100%;">
            <v-btn color="error" text block @click="exit()"
              >Cerrar sesión</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" md="8">
        <v-card outlined flat min-height="300px">
          <v-card-text>
            <Cripto></Cripto>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Account from '../components/auth/Account'
import Cripto from '../components/auth/Cripto'

export default {
  components: {
    Account,
    Cripto
  },

  methods: {
    async exit() {
      await this.$store.dispatch('auth/logout')
    }
  }
}
</script>

<style></style>
