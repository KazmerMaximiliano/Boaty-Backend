<template>
  <div style="height: 100vh" class="d-flex justify-center align-center">
    <div
      class="d-flex flex-column justify-center align-center"
      style="margin-top: -132px;"
    >
      <v-avatar class="login-avatar" size="186">
        <v-img src="./img/boaty_boat.png"></v-img>
      </v-avatar>
      <v-card width="400px" outlined style="padding-top: 72px;">
        <v-card-text>
          <v-row justify="center" align="center" style="height: 300px;">
            <v-col cols="12" pa-3 v-if="$store.state.inProcess">
              <v-row justify="center">
                <v-progress-circular
                  :size="70"
                  :width="7"
                  color="primary"
                  indeterminate
                ></v-progress-circular>
              </v-row>
            </v-col>
            <v-col cols="12" v-else>
              <v-form ref="loginForm" @submit.prevent="login()">
                <v-row justify="center">
                  <v-col cols="12" class="py-0 px-5">
                    <v-text-field
                      v-model="$store.state.auth.form.email"
                      :rules="[rules.required]"
                      label="Email"
                      color="primary"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" class="py-0 px-5">
                    <v-text-field
                      v-model="$store.state.auth.form.password"
                      :rules="[rules.required]"
                      :append-icon="
                        password_type ? 'fas fa-eye' : 'fas fa-eye-slash'
                      "
                      @click:append="password_type = !password_type"
                      :type="password_type ? 'text' : 'password'"
                      label="Contraseña"
                      color="primary"
                      outlined
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row class="px-5">
                  <v-btn
                    type="submit"
                    tile
                    block
                    class="mr-5 elevation-0"
                    color="primary"
                    >Iniciar sesión</v-btn
                  >
                </v-row>
              </v-form>
              <v-divider class="mt-6 mb-2"></v-divider>
              <v-row justify="space-around">
                <v-col cols="auto">
                  <v-btn
                    href="auth/google"
                    color="secondary"
                    tile
                    elevation="0"
                  >
                    <v-icon left>
                      fab fa-google
                    </v-icon>
                    Google
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn
                    href="auth/facebook"
                    color="secondary"
                    tile
                    elevation="0"
                  >
                    <v-icon left>
                      fab fa-facebook
                    </v-icon>
                    Facebook
                  </v-btn>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    password_type: false,
    rules: {
      required: value => !!value || 'Este campo es obligatorio'
    }
  }),

  methods: {
    login: async function() {
      if (this.$refs.loginForm.validate()) {
        await this.$store.dispatch('auth/login')
        await this.$store.dispatch('auth/user')
        this.$user.set({
          rol: this.$store.state.auth.user.roles[0],
          permissions: ['authenticated']
        })
      }
    }
  }
}
</script>

<style lang="scss">
.login-avatar {
  border: 2px solid red;
  position: absolute;
  z-index: 9;
  top: 72px;
  border: 15px solid #ebebeb;
  .v-image {
    .v-image__image--cover {
      background-color: white;
      background-size: 124px auto;
    }
  }
}
</style>
