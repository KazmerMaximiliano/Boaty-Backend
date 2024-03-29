  <template>
    <div>
        <v-simple-table class="hidden-xs-only" v-if="users">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Apellido</th>
                        <th class="text-left">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="(item, id) in users" :key="id" 
                        @contextmenu="deploy($event, item)"    
                        style="cursor: pointer;"
                    >
                        <td>{{ item.first_name }}</td>
                        <td>{{ item.last_name }}</td>
                        <td>{{ item.email }}</td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
        <div class="hidden-sm-and-up" v-if="users">
            <div v-for="(item, id) in users" :key="id">
                <v-list 
                    dense 
                    @click="deploy($event, item)"
                >
                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <div class="d-flex justify-space-between">
                                <b>Nombre: </b>
                                <p>{{ item.first_name }}</p>
                            </div>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <div class="d-flex justify-space-between">
                                <b>Nombre: </b>
                                <p>{{ item.last_name }}</p>
                            </div>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <div class="d-flex justify-space-between">
                                <b>Email: </b>
                                <p>{{ item.email }}</p>
                            </div>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
            </div>
        </div>
        <v-menu v-model="menu" :position-x="menuX" :position-y="menuY" absolute offset-y>
            <v-list v-if="selectedItem">
                <v-list-item @click="edit()">
                    <v-list-item-title>Editar</v-list-item-title>
                </v-list-item>
                <v-list-item @click="deleteDialog = true">
                    <v-list-item-title>Eliminar</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-dialog v-model="deleteDialog" persistent width="450px">
            <v-card v-if="inProcess">
                <v-row justify="center">
                    <v-progress-circular
                        :size="70"
                        :width="7"
                        color="primary"
                        indeterminate
                        style="margin: 32px 0 32px 0;"
                    ></v-progress-circular>
                </v-row>
            </v-card>
            <v-card v-else>
                <v-card-title class="headline">¿Estas seguro?</v-card-title>
                <v-card-text>
                    Se eliminará el usuario seleccionado, esta acción es irreversible.
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="error"
                        text
                        @click="deleteDialog = false; selectedItem = null"
                        :disabled="inProcess"
                    >Cancelar</v-btn>
                    <v-btn
                        color="success"
                        text
                        @click="deleteUsuario()"
                        :disabled="inProcess"
                    >Aceptar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data: () => ({
        inProcess: false,
        deleteDialog: false,
        menu: false,
        menuX: 0,
        menuY: 0,
        selectedItem: null,
    }),

    props: ['users'],

    methods: {
        deploy(event, item) {
            event.preventDefault();
            this.menu = false;
            this.selectedItem = item;
            this.menuX = event.clientX;
            this.menuY = event.clientY;
            this.$nextTick(() => {
                this.menu = true;
            }) 
        },

        edit() {
            this.$store.dispatch("users/edit", { data: this.selectedItem });
            this.$router.push("users/editar/0");
        },

        async deleteUsuario() {
            this.inProcess = true;
            await this.$store.dispatch("users/destroy", { id: this.selectedItem.id });
            await this.$store.dispatch('users/index');
            this.inProcess = false;
            this.deleteDialog = false;
        }
    }
}
</script>

<style>
</style>