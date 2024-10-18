<template>
  <div class="status-container">
    <h1>Teste de Comunicação com o Backend</h1>
    <button @click="testarComunicacao">Testar Comunicação</button>
    <p class="message">{{ mensagem }}</p>

    <div v-if="status" :class="{'text-success': status === 'success', 'text-danger': status === 'error'}" class="status-message">
      Status: {{ status === 'success' ? 'Servidor está funcionando!' : 'Servidor está fora do ar!' }}
    </div>

    <div class="status-details">
      <p :class="{'text-success': dbStatus === 'success', 'text-danger': dbStatus === 'error'}">{{ dbStatusMessage }}</p>
      <p :class="{'text-success': emailStatus === 'success', 'text-danger': emailStatus === 'error'}">{{ emailStatusMessage }}</p>
    </div>

    <div v-if="mostraBotoes">
      <input v-model="numeroWhatsApp" placeholder="Digite seu número com DDD" />
      <div class="col s12">
        <button @click="enviarWhatsApp">Enviar Status pelo WhatsApp</button>
      </div>
      
      <div class="col s12">
        <button @click="gerarPDF">Baixar Relatório em PDF</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

export default {
  name: 'TesteBackend',
  data() {
    return {
      mensagem: '',
      status: null,
      dbStatus: null,
      emailStatus: null,
      dbStatusMessage: '',
      emailStatusMessage: '',
      numeroWhatsApp: '',
      mostraBotoes: false, 
    };
  },
  methods: {
    testarComunicacao() {
      axios.get('/api/status')
        .then(response => {
          this.mensagem = response.data.message;
          this.status = response.data.status;
          this.dbStatusMessage = response.data.db_status;
          this.emailStatusMessage = response.data.email_status;
          this.dbStatus = response.data.db_status.includes('sucesso') ? 'success' : 'error';
          this.emailStatus = response.data.email_status.includes('sucesso') ? 'success' : 'error';

          this.mostraBotoes = true;
        })
        .catch(error => {
          console.error('Erro ao se comunicar com o backend:', error);
          this.mensagem = 'Erro ao se comunicar com o backend';
          this.status = 'error';
        });
    },
    gerarPDF() {
      const pdf = new jsPDF();
      const container = this.$el;

      html2canvas(container).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 190; 
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        let position = 0;

        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
          position = heightLeft - imgHeight;
          pdf.addPage();
          pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
        }

        pdf.save('relatorio_status.pdf');
      });
    },
    enviarWhatsApp() {
      const message = `Status do Servidor: ${this.mensagem}\nStatus Geral: ${this.status === 'success' ? 'Servidor está funcionando!' : 'Servidor está fora do ar!'}\nBanco de Dados: ${this.dbStatusMessage}\nEmail: ${this.emailStatusMessage}`;
      
      if (this.numeroWhatsApp) {
        const url = `https://api.whatsapp.com/send?phone=${this.numeroWhatsApp}&text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');
      } else {
        alert('Por favor, insira um número de WhatsApp válido.');
      }
    }
  }
};
</script>

<style scoped>
.status-container {
  background-color: #f5f5f5;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: auto;
}

h1 {
  color: #333;
  margin-bottom: 20px;
}

button {
  margin: 10px 0;
  padding: 10px 15px;
  background-color: #333;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #555;
}

.message {
  color: #333;
}

.status-message {
  margin-top: 10px;
  font-weight: bold;
}

.text-success {
  color: green;
}

.text-danger {
  color: red;
}

.status-details {
  margin-top: 10px;
}

input {
  margin-top: 10px;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  width: calc(100% - 24px);
}
</style>
