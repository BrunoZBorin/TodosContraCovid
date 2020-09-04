<template>
  <div>
    <template>
      <v-row justify="center">
        <v-dialog
          v-model="dialog"
          persistent
          hide-overlay
          max-width="40%"
        >
          <v-card>
            <v-card-title class="headline">
              Relatório de Paciente
            </v-card-title>
            <v-card-text>
              
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="success"
                @click="gerarExcel"
              >
                <v-icon>description</v-icon>
                Gerar Excel
              </v-btn>
              <v-btn
                color="error"
                @click="gerarPdf"
              >
                <v-icon>picture_as_pdf</v-icon>
                Gerar Pdf
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
    </template>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        dialog: true
      }
    },

    mounted() {
      this.dialog = true;
    },

    methods: {
      async gerarExcel()
      {
        const response = await this.axios.get('pacientes_export_excel/' + this.$store.getters.getDadosUser.id, {responseType: 'blob'});

        var fileURL = window.URL.createObjectURL(new Blob([response.data]));

        var fileLink = document.createElement('a');

        fileLink.href = fileURL;

        fileLink.setAttribute('download', 'relatorio_pacientes_' + this.moment(new Date).format('DD_MM_YYYY') + '.xls');

        document.body.appendChild(fileLink);

        fileLink.click();
      },

      async gerarPdf()
      {
        const response = await this.axios.get('pacientes_export_pdf/' + this.$store.getters.getDadosUser.id, {responseType: 'blob'});
        
        var fileURL = window.URL.createObjectURL(new Blob([response.data], {type: 'application/pdf'}));

        var fileLink = document.createElement('a');

        fileLink.href = fileURL;

        fileLink.setAttribute('download', 'relatorio_pacientes_' + this.moment(new Date).format('DD_MM_YYYY') + '.pdf');

        document.body.appendChild(fileLink);

        fileLink.click();
      }
    }
  }
</script>