<template>
  <div>
    <v-container>
      <v-row justify="center">
        <v-col cols="12" lg="10">
          <v-card shaped outlined :loading="$store.state.inProcess">
            <v-card-title>Pagos con criptos</v-card-title>
            <v-divider></v-divider>
            <v-card-text class="px-0">
              <v-row justify="center" v-if="$store.state.inProcess">
                <div>
                  <v-progress-circular
                    indeterminate
                    color="primary"
                  ></v-progress-circular>
                </div>
              </v-row>
              <div v-if="$store.state.debts.debts">
                <v-expansion-panels flat>
                  <v-expansion-panel
                    v-for="(debt, i) in $store.state.debts.debts"
                    :key="i"
                  >
                    <v-expansion-panel-header>
                      {{ debt.concept }} ( $ {{ debt.amount }} ) -
                      {{ getStatus(debt.status) }}
                    </v-expansion-panel-header>
                    <v-divider></v-divider>
                    <v-expansion-panel-content class="pt-4">
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title
                            ><b>Pagado por:</b></v-list-item-title
                          >
                          <v-list-item-subtitle
                            >{{ debt.debtor.first_name }}
                            {{ debt.debtor.last_name }}</v-list-item-subtitle
                          >
                          <v-list-item-subtitle
                            ><b>Dirección: </b>{{ debt.wallet_id }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item three-line>
                        <v-list-item-content>
                          <v-list-item-title
                            ><b>Pagado a:</b></v-list-item-title
                          >
                          <v-list-item-subtitle
                            >{{ debt.creditor.first_name }}
                            {{ debt.creditor.last_name }}</v-list-item-subtitle
                          >
                          <v-list-item-subtitle
                            ><b>Dirección: </b
                            >{{ debt.creditor.crypto_address }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title><b>Fecha:</b></v-list-item-title>
                          <v-list-item-subtitle>{{
                            formatDate(debt.created_at)
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title
                            ><b>Descripción:</b></v-list-item-title
                          >
                          <v-list-item-subtitle>{{
                            debt.concept
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title><b>Monto:</b></v-list-item-title>
                          <v-list-item-subtitle>{{
                            debt.amount
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item two-line>
                        <v-list-item-content>
                          <v-list-item-title><b>Fecha:</b></v-list-item-title>
                          <v-list-item-subtitle>{{
                            formatDate(debt.created_at)
                          }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>

                      <div
                        v-if="$store.state.auth.user.roles.includes('admin')"
                        class="pt-4"
                      >
                        <div v-if="debt.status == 0">
                          <v-row justify="center">
                            <v-btn
                              text
                              color="secondary"
                              @click="confirmPay(debt)"
                              >confirmar pago</v-btn
                            >
                          </v-row>
                        </div>
                        <div v-else>
                          <p class="text-center">Pago confirmado</p>
                        </div>
                      </div>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import dayjs from 'dayjs'

export default {
  mounted() {
    this.getDebts()
  },

  methods: {
    async getDebts() {
      await this.$store.dispatch('debts/index')
    },

    formatDate(date) {
      return dayjs(date).format('DD/MM/YYYY')
    },

    getStatus(status) {
      switch (status) {
        case 0:
          return 'Pendiente'
        case 2:
          return 'Pagado'
        case 3:
          return 'Cancelado'
        default:
          return 'Referenciado'
      }
    },

    async confirmPay(debt) {
      await this.$store.dispatch('debts/pay', debt)
      await this.getDebts()
    }
  }
}
</script>

<style></style>
