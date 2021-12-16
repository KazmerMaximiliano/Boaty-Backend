<template>
    <div>
        <v-row>
            <v-col md="4">
                <v-card outlined flat min-height="300px">
                    <v-card-text>
                        <Account></Account>
                    </v-card-text>
                    <v-card-actions style="position: absolute; bottom: 0; width: 100%;">
                        <v-btn color="error" text block @click="exit()">Cerrar sesión</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import Account from '../components/auth/Account'

export default {
    components: {
        Account,
    },

    methods: {
        async exit() {
            await this.$store.dispatch("auth/logout");
        }
    }
}
</script>

<style>
</style>